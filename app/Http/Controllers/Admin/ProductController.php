<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Search
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($qb) => $qb->where('name', 'like', "%$q%")
                                        ->orWhere('sku', 'like', "%$q%"));
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

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        match ($request->get('sort', '')) {
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'stock_asc'  => $query->orderBy('stock', 'asc'),
            'stock_desc' => $query->orderBy('stock', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        $totalAll       = Product::count();
        $totalAvailable = Product::where('stock', '>', 10)->count();
        $totalLow       = Product::whereBetween('stock', [1, 10])->count();
        $totalOut       = Product::where('stock', 0)->count();

        return view('admin.inventaris.index', compact(
            'products', 'categories',
            'totalAll', 'totalAvailable', 'totalLow', 'totalOut'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.inventaris.tambah', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'required|integer|min:0',
            'badge'            => 'nullable|string|max:50',
            'is_published'     => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'contents'         => 'nullable|array',
            'contents.*'       => 'string|max:255',
            'images'           => 'nullable|array',
            'images.*'         => 'image|max:10240',
        ]);

        // Auto generate SKU
        $lastProduct = Product::orderByDesc('id')->first();
        $nextNum = $lastProduct ? $lastProduct->id + 1 : 1;
        $sku = 'TP-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $product = Product::create([
            'sku'              => $sku,
            'name'             => $validated['name'],
            'slug'             => Str::slug($validated['name']) . '-' . time(),
            'description'      => $validated['description'] ?? '',
            'category_id'      => $validated['category_id'],
            'price'            => $validated['price'],
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'stock'            => $validated['stock'],
            'badge'            => $validated['badge'] ?? null,
            'is_published'     => $request->boolean('is_published', true),
            'is_featured'      => $request->boolean('is_featured', false),
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main'    => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        // Contents
        if (!empty($validated['contents'])) {
            foreach ($validated['contents'] as $item) {
                if (trim($item) !== '') {
                    ProductContent::create(['product_id' => $product->id, 'item' => $item]);
                }
            }
        }

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'contents', 'category']);
        $categories = Category::all();
        return view('admin.inventaris.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'required|integer|min:0',
            'badge'            => 'nullable|string|max:50',
            'contents'         => 'nullable|array',
            'contents.*'       => 'string|max:255',
            'new_images'       => 'nullable|array',
            'new_images.*'     => 'image|max:10240',
            'existing_images'  => 'nullable|array',
        ]);

        $product->update([
            'name'             => $validated['name'],
            'slug'             => Str::slug($validated['name']),
            'description'      => $validated['description'] ?? '',
            'category_id'      => $validated['category_id'],
            'price'            => $validated['price'],
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'stock'            => $validated['stock'],
            'badge'            => $validated['badge'] ?? null,
            'is_published'     => $request->boolean('is_published', true),
            'is_featured'      => $request->boolean('is_featured', false),
        ]);

        // Hapus foto yang dihapus user (tidak ada di existing_images)
        $keepIds = $request->input('existing_images', []);
        $deletedImages = $product->images()->whereNotIn('id', $keepIds)->get();
        foreach ($deletedImages as $img) {
            if (!str_starts_with($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        }

        // Upload foto baru (new_images[])
        if ($request->hasFile('new_images')) {
            $hasMain = $product->images()->where('is_main', true)->exists();
            foreach ($request->file('new_images') as $i => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main'    => !$hasMain && $i === 0,
                    'sort_order' => $product->images()->count() + $i,
                ]);
            }
        }

        // Update contents
        if ($request->has('contents')) {
            $product->contents()->delete();
            foreach ($validated['contents'] as $item) {
                if (trim($item) !== '') {
                    ProductContent::create(['product_id' => $product->id, 'item' => $item]);
                }
            }
        }

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function export(Request $request)
    {
        $query = Product::with(['category'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($qb) => $qb->where('name', 'like', "%$q%")
                                        ->orWhere('sku', 'like', "%$q%"));
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'available' => $query->where('stock', '>', 10),
                'low'       => $query->whereBetween('stock', [1, 10]),
                'out'       => $query->where('stock', 0),
                default     => null,
            };
        }

        $products = $query->get();

        $filename = 'inventaris-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel agar bisa baca UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($file, ['SKU', 'Nama Produk', 'Kategori', 'Harga', 'Diskon (%)', 'Stok', 'Status', 'Badge', 'Dipublikasi', 'Unggulan']);

            foreach ($products as $p) {
                $status = $p->stock > 10 ? 'Tersedia' : ($p->stock > 0 ? 'Stok Rendah' : 'Habis');
                fputcsv($file, [
                    $p->sku,
                    $p->name,
                    $p->category->name ?? '-',
                    $p->price,
                    $p->discount_percent,
                    $p->stock,
                    $status,
                    $p->badge ?? '-',
                    $p->is_published ? 'Ya' : 'Tidak',
                    $p->is_featured ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Product $product)
    {
        // Delete images from storage
        foreach ($product->images as $img) {
            if (!str_starts_with($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
