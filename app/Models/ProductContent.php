<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductContent extends Model
{
    protected $fillable = ['product_id', 'item'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
