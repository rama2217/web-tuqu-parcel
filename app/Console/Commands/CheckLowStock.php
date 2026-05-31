<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Setting;
use App\Mail\LowStockNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Kirim notifikasi email jika ada stok rendah';

    public function handle()
    {
        $minStock   = (int) Setting::get('low_stock_threshold', 5);
        $adminEmail = Setting::get('admin_email');

        if (!$adminEmail) {
            $this->error('Email admin belum diatur di pengaturan!');
            return;
        }

        $lowProducts = Product::where('stock', '<=', $minStock)
                              ->where('is_published', true)
                              ->get();

        if ($lowProducts->isNotEmpty()) {
            try {
                Mail::to($adminEmail)->send(new LowStockNotification($lowProducts));
                $this->info('✅ Email stok rendah terkirim ke ' . $adminEmail);
                $this->info('   Produk bermasalah: ' . $lowProducts->count() . ' produk');
            } catch (\Exception $e) {
                $this->error('❌ Gagal kirim email: ' . $e->getMessage());
                Log::error('LowStockNotification email failed: ' . $e->getMessage());
            }
        } else {
            $this->info('✅ Semua stok aman, tidak ada email terkirim.');
        }
    }
}
