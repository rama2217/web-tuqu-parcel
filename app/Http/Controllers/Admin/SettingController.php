<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.pengaturan.index', compact('settings'));
    }

    // Simpan pengaturan branding & kontak
    public function update(Request $request)
    {
        $allowed = [
            'site_name', 'site_tagline', 'whatsapp', 'instagram',
            'instagram_url', 'tiktok_url', 'email_contact', 'address', 'address_2',
            'low_stock_threshold', 'privacy_policy', 'terms_of_service',
            'why_title',
            'why_card1_title', 'why_card1_desc',
            'why_card2_title', 'why_card2_desc',
            'why_card3_title', 'why_card3_desc',
            'admin_email',
            // Bank 1 – BCA (atau bank apapun)
            'bank_name_1', 'bank_number_1', 'bank_holder_1',
            // Bank 2 – Mandiri
            'bank_name_2', 'bank_number_2', 'bank_holder_2',
            // Bank 3 – Bank Jatim
            'bank_name_3', 'bank_number_3', 'bank_holder_3',
            // Bank 4 – opsional
            'bank_name_4', 'bank_number_4', 'bank_holder_4',
            'admin_auto_logout', 'admin_session_timeout', 'admin_login_notification',
            'notif_low_stock', 'notif_new_review', 'notif_weekly_report',
        ];

        // Daftar key yang bertipe checkbox (nilai: '1' atau '0')
        $checkboxKeys = [
            'admin_auto_logout', 'admin_login_notification',
            'notif_low_stock', 'notif_new_review', 'notif_weekly_report',
        ];

        foreach ($allowed as $key) {
            if (in_array($key, $checkboxKeys)) {
                // Checkbox tidak terkirim saat tidak dicentang → simpan '0'
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, (string) $request->input($key, ''));
            }
        }

        // Simpan label & link occasion (1-12)
        for ($i = 1; $i <= 12; $i++) {
            Setting::set("occasion_{$i}_label", (string) $request->input("occasion_{$i}_label", ''));
            Setting::set("occasion_{$i}_link",  (string) $request->input("occasion_{$i}_link",  ''));
        }

        // Handle upload logo footer/default
        if ($request->hasFile('site_logo')) {
            $request->validate(['site_logo' => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('site_logo')->store('logos', 'public');
            Setting::set('site_logo', $path);
        }

        // Handle upload logo navbar
        if ($request->hasFile('site_logo_navbar')) {
            $request->validate(['site_logo_navbar' => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
            $oldLogoNav = Setting::get('site_logo_navbar');
            if ($oldLogoNav && Storage::disk('public')->exists($oldLogoNav)) {
                Storage::disk('public')->delete($oldLogoNav);
            }
            $path = $request->file('site_logo_navbar')->store('logos', 'public');
            Setting::set('site_logo_navbar', $path);
        }

        // Hapus logo navbar jika checkbox "hapus" dicentang
        if ($request->input('remove_logo_navbar')) {
            $oldLogoNav = Setting::get('site_logo_navbar');
            if ($oldLogoNav && Storage::disk('public')->exists($oldLogoNav)) {
                Storage::disk('public')->delete($oldLogoNav);
            }
            Setting::set('site_logo_navbar', '');
        }

        // Handle foto keunggulan kami (1-3)
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("keunggulan_photo_{$i}")) {
                $request->validate(["keunggulan_photo_{$i}" => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
                $oldPath = Setting::get("keunggulan_photo_{$i}");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file("keunggulan_photo_{$i}")->store('keunggulan', 'public');
                Setting::set("keunggulan_photo_{$i}", $path);
            }
            if ($request->input("remove_keunggulan_photo_{$i}")) {
                $oldPath = Setting::get("keunggulan_photo_{$i}");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                Setting::set("keunggulan_photo_{$i}", '');
            }
        }

        // Handle foto tim untuk halaman Tentang Kami
        foreach ([1, 2, 3, 4, 5] as $n) {
            if ($request->hasFile("team_photo_{$n}")) {
                $request->validate(["team_photo_{$n}" => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
                $oldPath = Setting::get("team_photo_{$n}");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file("team_photo_{$n}")->store('team', 'public');
                Setting::set("team_photo_{$n}", $path);
            }
            if ($request->input("remove_team_photo_{$n}")) {
                $oldPath = Setting::get("team_photo_{$n}");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                Setting::set("team_photo_{$n}", '');
            }
            Setting::set("team_photo_{$n}_zoom",  (string) $request->input("team_photo_{$n}_zoom",  '100'));
            Setting::set("team_photo_{$n}_pos_x", (string) $request->input("team_photo_{$n}_pos_x", '50'));
            Setting::set("team_photo_{$n}_pos_y", (string) $request->input("team_photo_{$n}_pos_y", '20'));
        }

        // Handle upload foto hero/banner
        if ($request->hasFile('hero_photo')) {
            $request->validate(['hero_photo' => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
            $oldPath = Setting::get('hero_photo');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('hero_photo')->store('hero', 'public');
            Setting::set('hero_photo', $path);
        }
        if ($request->input('remove_hero_photo')) {
            $oldPath = Setting::get('hero_photo');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            Setting::set('hero_photo', '');
        }

        // Handle upload foto occasion (1-12)
        for ($i = 1; $i <= 12; $i++) {
            if ($request->hasFile("occasion_{$i}_photo")) {
                $request->validate(["occasion_{$i}_photo" => 'image|mimes:jpg,jpeg,png,gif,webp|max:10240']);
                $oldPath = Setting::get("occasion_{$i}_photo");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file("occasion_{$i}_photo")->store('occasions', 'public');
                Setting::set("occasion_{$i}_photo", $path);
            }
            if ($request->input("remove_occasion_{$i}_photo")) {
                $oldPath = Setting::get("occasion_{$i}_photo");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                Setting::set("occasion_{$i}_photo", '');
            }
        }

        // Handle upload gambar QRIS
        if ($request->hasFile('qris_image')) {
            $request->validate(['qris_image' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120']);
            $oldQris = Setting::get('qris_image');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }
            $path = $request->file('qris_image')->store('qris', 'public');
            Setting::set('qris_image', $path);
        }
        if ($request->input('remove_qris_image')) {
            $oldQris = Setting::get('qris_image');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }
            Setting::set('qris_image', '');
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    // Update profil admin (nama & email)
    public function updateAkun(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil admin berhasil diperbarui!');
    }

    // Ganti password admin
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', 'Password berhasil diperbarui!');
    }
}
