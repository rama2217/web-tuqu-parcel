<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // Halaman checkout — tampilkan form data penerima
    public function checkout(Request $request)
    {
        $customer = $this->customer();

        // Ambil item yang dipilih (dari keranjang atau langsung)
        if ($request->has('items')) {
            $itemIds   = explode(',', $request->items);
            $cartItems = Cart::whereIn('id', $itemIds)
                             ->where('customer_id', $customer->id)
                             ->with('product')
                             ->get();
        } else {
            $cartItems = $customer->cartItems()->with('product')->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);

        return view('checkout.index', compact('cartItems', 'subtotal'));
    }

    // Proses simpan order (dari form checkout)
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name'    => 'required|string|max:255',
            'recipient_phone'   => 'required|string|max:20',
            'recipient_address' => 'required|string',
            'recipient_city'    => 'required|string|max:100',
            'notes'             => 'nullable|string|max:500',
            'cart_ids'          => 'required|string',
        ], [
            'recipient_name.required'    => 'Nama penerima wajib diisi.',
            'recipient_phone.required'   => 'No. telepon penerima wajib diisi.',
            'recipient_address.required' => 'Alamat pengiriman wajib diisi.',
            'recipient_city.required'    => 'Kota wajib diisi.',
        ]);

        $customer = $this->customer();
        $cartIds  = explode(',', $request->cart_ids);
        $cartItems = Cart::whereIn('id', $cartIds)
                         ->where('customer_id', $customer->id)
                         ->with('product')
                         ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $total    = $subtotal; // bisa tambah ongkir nanti

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'      => Order::generateOrderNumber(),
                'customer_id'       => $customer->id,
                'recipient_name'    => $request->recipient_name,
                'recipient_phone'   => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'recipient_city'    => $request->recipient_city,
                'notes'             => $request->notes,
                'subtotal'          => $subtotal,
                'shipping_cost'     => 0,
                'total'             => $total,
                'status'            => 'pending',
            ]);

            foreach ($cartItems as $item) {
                // Cek stok cukup
                if ($item->product->stock < $item->quantity) {
                    DB::rollBack();
                    return back()->with('error', 'Stok produk "' . $item->product->name . '" tidak mencukupi. Sisa stok: ' . $item->product->stock)->withInput();
                }

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'product_price' => $item->product->price,
                    'quantity'      => $item->quantity,
                    'subtotal'      => $item->product->price * $item->quantity,
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            // Hapus item dari keranjang
            Cart::whereIn('id', $cartIds)->delete();

            DB::commit();

            return redirect()->route('checkout.payment', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.')->withInput();
        }
    }

    // Halaman pembayaran
    public function payment(Order $order)
    {
        if ($order->customer_id !== $this->customer()->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('account.orders')
                ->with('info', 'Pesanan ini sudah diproses.');
        }

        $order->load('items.product');

        // Ambil nomor rekening dari setting
        $bankAccounts = [
            [
                'bank'    => \App\Models\Setting::get('bank_name_1', 'BCA'),
                'number'  => \App\Models\Setting::get('bank_number_1', '-'),
                'name'    => \App\Models\Setting::get('bank_holder_1', 'TuquParcel'),
            ],
            [
                'bank'    => \App\Models\Setting::get('bank_name_2', ''),
                'number'  => \App\Models\Setting::get('bank_number_2', ''),
                'name'    => \App\Models\Setting::get('bank_holder_2', ''),
            ],
        ];
        $bankAccounts = array_filter($bankAccounts, fn($b) => !empty($b['number']));

        return view('checkout.payment', compact('order', 'bankAccounts'));
    }

    // Upload bukti pembayaran
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->customer_id !== $this->customer()->id) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'payment_proof.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'paid_at'       => now(),
            'status'        => 'paid',
        ]);

        return redirect()->route('account.orders')
            ->with('success', 'Bukti pembayaran berhasil diupload! Kami akan segera memverifikasi pembayaran kamu.');
    }

    // Batalkan pesanan — hanya jika status masih 'pending'
    public function cancel(Request $request, Order $order)
    {
        $customer = $this->customer();

        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        // Hanya boleh cancel jika belum bayar
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok ke masing-masing produk
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('account.orders')
                ->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi.');
        }
    }
}
