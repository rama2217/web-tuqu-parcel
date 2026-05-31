<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $lowStock      = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStock    = Product::where('stock', 0)->count();
        $categories    = \App\Models\Category::count();

        $lowStockProducts = Product::with(['category'])
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(10)
            ->get();

        $pendingReviews = Review::where('is_approved', false)->count();

        return view('admin.dashboard', compact(
            'totalProducts', 'lowStock', 'outOfStock',
            'categories', 'lowStockProducts', 'pendingReviews'
        ));
    }
}
