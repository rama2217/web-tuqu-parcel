<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'slug', 'description', 'price',
        'discount_percent', 'stock', 'is_published', 'is_featured',
        'badge', 'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function mainImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_main', true)->orderBy('sort_order');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ProductContent::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Helper: harga setelah diskon
    public function getFinalPriceAttribute(): float
    {
        if ($this->discount_percent > 0) {
            return $this->price * (1 - $this->discount_percent / 100);
        }
        return $this->price;
    }

    // Helper: format harga rupiah
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'out';
        if ($this->stock <= 10) return 'low';
        return 'available';
    }
}