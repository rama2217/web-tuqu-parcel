<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\ReviewNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Daftar kata kasar / tidak pantas.
     * Tambah kata di sini sesuai kebutuhan.
     */
    private array $blacklist = [
        // Kata kasar umum bahasa Indonesia
        'bangsat', 'bajingan', 'brengsek', 'anjing', 'anjir', 'babi',
        'tolol', 'bodoh', 'goblok', 'idiot', 'tai', 'kampret', 'keparat',
        'sialan', 'celaka', 'bedebah', 'setan', 'iblis', 'laknat',
        'ngentot', 'memek', 'kontol', 'pepek', 'jancok', 'cok',
        'asu', 'asem', 'ndas', 'dancuk',

        // Kata spam / tidak relevan
        'spam', 'scam', 'tipu', 'penipu', 'bohong', 'palsu',
        'judi', 'slot', 'casino', 'togel', 'bet',
    ];

    public function store(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'reviewer_name'  => 'required|string|max:100',
            'reviewer_email' => 'nullable|email|max:150',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'required|string|min:10|max:1000',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ], [
            'product_id.required'    => 'Produk tidak valid.',
            'reviewer_name.required' => 'Nama wajib diisi.',
            'rating.required'        => 'Rating wajib dipilih.',
            'comment.required'       => 'Ulasan wajib diisi.',
            'comment.min'            => 'Ulasan minimal 10 karakter.',
            'comment.max'            => 'Ulasan maksimal 1000 karakter.',
        ]);

        // Cek kata kasar di nama dan komentar
        $isClean = $this->isClean($request->reviewer_name)
                && $this->isClean($request->comment);

        // Handle upload foto (opsional)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reviews', 'public');
        }

        $review = Review::create([
            'product_id'     => $request->product_id,
            'reviewer_name'  => $request->reviewer_name,
            'reviewer_email' => $request->reviewer_email,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
            'photo'          => $photoPath,
            'is_approved'    => $isClean,
        ]);

        // Kirim notifikasi email ke admin
        $adminEmail = Setting::get('admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ReviewNotification($review));
            } catch (\Exception $e) {
                // Gagal kirim email tidak mengganggu proses review
                Log::error('ReviewNotification email failed: ' . $e->getMessage());
            }
        }

        if ($isClean) {
            return back()->with('review_success', 'Terima kasih! Ulasan kamu sudah dipublikasikan.');
        }

        return back()->with('review_pending', 'Terima kasih! Ulasan kamu sedang ditinjau oleh admin.');
    }

    /**
     * Cek apakah teks bebas dari kata blacklist.
     */
    private function isClean(string $text): bool
    {
        $lower = strtolower($text);
        foreach ($this->blacklist as $word) {
            if (str_contains($lower, $word)) {
                return false;
            }
        }
        return true;
    }
}
