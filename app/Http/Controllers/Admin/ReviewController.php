<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('product')->latest();

        if ($request->filter === 'pending') {
            $query->pending();
        } elseif ($request->filter === 'approved') {
            $query->approved();
        }

        $reviews       = $query->paginate(12)->withQueryString();
        $totalAll      = Review::count();
        $totalPending  = Review::pending()->count();
        $totalApproved = Review::approved()->count();
        $avgRating     = Review::approved()->avg('rating') ?? 0;

        return view('admin.review.index', compact(
            'reviews', 'totalAll', 'totalPending', 'totalApproved', 'avgRating'
        ));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review berhasil disetujui.');
    }

    /**
     * Approve semua review yang pending sekaligus
     */
    public function approveAll()
    {
        $count = Review::pending()->update(['is_approved' => true]);
        return back()->with('success', "$count review berhasil disetujui semua.");
    }

    public function toggleLanding(Review $review)
    {
        if (!$review->is_approved) {
            return back()->with('error', 'Review harus disetujui dulu sebelum ditampilkan di landing page.');
        }
        $review->update(['show_on_landing' => !$review->show_on_landing]);
        $msg = $review->show_on_landing
            ? 'Review ditampilkan di landing page.'
            : 'Review disembunyikan dari landing page.';
        return back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }
}
