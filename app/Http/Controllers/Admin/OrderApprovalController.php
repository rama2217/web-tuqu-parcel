<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'approved'  => Order::where('status', 'approved')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function approve(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Pesanan ini belum melakukan pembayaran.');
        }

        $order->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', "Pesanan {$order->order_number} berhasil disetujui!");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,approved,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Status pesanan diubah ke: {$order->status_label}");
    }

    public function viewProof(Order $order)
    {
        if (!$order->payment_proof) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $order->payment_proof);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
