<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_city',
        'notes',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'payment_proof',
        'paid_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'paid_at'     => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Menunggu Konfirmasi',
            'approved'   => 'Disetujui',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#f59e0b',
            'paid'       => '#3b82f6',
            'approved'   => '#8b5cf6',
            'processing' => '#06b6d4',
            'shipped'    => '#f97316',
            'completed'  => '#22c55e',
            'cancelled'  => '#ef4444',
            default      => '#6b7280',
        };
    }

    // Generate order number unik
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'TQP-' . strtoupper(substr(uniqid(), -6)) . '-' . date('dmY');
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
