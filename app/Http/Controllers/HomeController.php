<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function landing()
    {
        $featuredProducts = Product::with(['images', 'category'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->get();

        $reviews = Review::with('product')
            ->where('is_approved', true)
            ->where('show_on_landing', true)
            ->latest()
            ->take(9)
            ->get();

        return view('pages.landing', compact('featuredProducts', 'categories', 'reviews'));
    }

    public function katalog(Request $request)
    {
        $query = Product::with(['images', 'category'])
            ->where('is_published', true);

        // Filter kategori (support array dari checkbox)
        if ($request->filled('kategori')) {
            $kategori = (array) $request->kategori;
            $query->whereHas('category', fn($q) => $q->whereIn('slug', $kategori));
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter status
        if ($request->filled('status')) {
            match ($request->status) {
                'available' => $query->where('stock', '>', 10),
                'low'       => $query->whereBetween('stock', [1, 10]),
                'out'       => $query->where('stock', 0),
                default     => null,
            };
        }

        // Sort
        match ($request->get('sort', 'terbaru')) {
            'harga_asc'  => $query->orderBy('price', 'asc'),
            'harga_desc' => $query->orderBy('price', 'desc'),
            'terlama'    => $query->oldest(),
            default      => $query->latest(),  // terbaru / featured
        };

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('pages.katalog', compact('products', 'categories'));
    }

    public function detail(string $slug)
    {
        $product = Product::with(['images', 'category', 'contents', 'approvedReviews'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Product::with(['images', 'category'])
            ->where('is_published', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $avgRating = $product->approvedReviews->avg('rating');

        return view('pages.detail-produk', compact('product', 'related', 'avgRating'));
    }

    public function tentang()
    {
        return view('pages.tentang-kami');
    }
}
