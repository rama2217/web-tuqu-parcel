<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // Tampilkan halaman keranjang
    public function index()
    {
        $customer  = $this->customer();
        $cartItems = $customer->cartItems()->with('product.images')->get();
        $subtotal  = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    // Tambah ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1|max:99',
        ]);

        $customer = $this->customer();
        $product  = Product::findOrFail($request->product_id);

        // Cek stok
        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, produk ini sedang habis.');
        }

        // Cek apakah sudah ada di keranjang
        $cart = Cart::where('customer_id', $customer->id)
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            $newQty = $cart->quantity + ($request->quantity ?? 1);
            $cart->update(['quantity' => min($newQty, $product->stock)]);
        } else {
            Cart::create([
                'customer_id' => $customer->id,
                'product_id'  => $product->id,
                'quantity'    => $request->quantity ?? 1,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $count = $customer->cartItems()->count();
            return response()->json(['success' => true, 'cart_count' => $count]);
        }

        // ✅ Tombol Checkout dari katalog: langsung ke halaman checkout tanpa mampir keranjang
        if ($request->input('redirect_checkout') == '1') {
            $cartItem = Cart::where('customer_id', $customer->id)
                            ->where('product_id', $product->id)
                            ->first();

            if ($cartItem) {
                return redirect()->route('checkout.index', ['items' => $cartItem->id]);
            }

            return redirect()->route('cart.index');
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update quantity
    public function update(Request $request, Cart $cart)
    {
        if ($cart->customer_id !== $this->customer()->id) {
            abort(403);
        }

        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        $cart->update(['quantity' => $request->quantity]);

        if ($request->ajax()) {
            $subtotal  = $cart->product->price * $cart->quantity;
            $cartItems = $this->customer()->cartItems()->with('product')->get();
            $total     = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            return response()->json([
                'success'      => true,
                'item_subtotal' => number_format($subtotal, 0, ',', '.'),
                'total'        => number_format($total, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // Hapus item
    public function remove(Cart $cart)
    {
        if ($cart->customer_id !== $this->customer()->id) {
            abort(403);
        }
        $cart->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // Hapus semua
    public function clear()
    {
        $this->customer()->cartItems()->delete();
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
