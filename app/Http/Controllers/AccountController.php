<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // ── Halaman Profil / Pengaturan Akun ─────────────────
    public function profile()
    {
        $customer = $this->customer();
        return view('account.profile', compact('customer'));
    }

    // ── Update Profil ─────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $customer = $this->customer();

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email'   => 'required|email|unique:customers,email,' . $customer->id,
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan akun lain.',
        ]);

        $customer->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Update Password ───────────────────────────────────
    public function updatePassword(Request $request)
    {
        $customer = $this->customer();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $customer->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // ── Riwayat Pesanan ───────────────────────────────────
    public function orders()
    {
        $customer = $this->customer();
        $orders   = $customer->orders()
                             ->with('items.product')
                             ->latest()
                             ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    // ── Detail Pesanan ────────────────────────────────────
    public function orderDetail(Order $order)
    {
        if ($order->customer_id !== $this->customer()->id) {
            abort(403);
        }

        $order->load('items.product');
        return view('account.order-detail', compact('order'));
    }
}
