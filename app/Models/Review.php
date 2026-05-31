<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'product_id', 'reviewer_name', 'reviewer_email',
        'rating', 'comment', 'photo', 'is_approved', 'show_on_landing'
    ];

    protected $casts = [
        'is_approved'     => 'boolean',
        'show_on_landing' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    // Scope: hanya review yang sudah disetujui
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope: review yang menunggu
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Scope: review yang ditampilkan di landing page
    public function scopeFeatured($query)
    {
        return $query->where('is_approved', true)->where('show_on_landing', true);
    }
}
