<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah fitur auto logout aktif
        $enabled = Setting::get('admin_auto_logout', '0');
        if (!$enabled || $enabled === '0') {
            return $next($request);
        }

        // Timeout dalam menit (default 30)
        $timeout = (int) Setting::get('admin_session_timeout', '30');

        if (Auth::check()) {
            $lastActivity = session('admin_last_activity');

            if ($lastActivity && (time() - $lastActivity) > ($timeout * 60)) {
                // Session expired — logout dan redirect
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')
                    ->with('error', 'Sesi kamu telah berakhir karena tidak ada aktivitas. Silakan login kembali.');
            }

            // Update waktu aktivitas terakhir
            session(['admin_last_activity' => time()]);
        }

        return $next($request);
    }
}
