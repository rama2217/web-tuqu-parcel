<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Data penerima
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->string('recipient_city');
            $table->text('notes')->nullable();

            // Harga
            $table->bigInteger('subtotal');
            $table->bigInteger('shipping_cost')->default(0);
            $table->bigInteger('total');

            // Status
            $table->enum('status', [
                'pending',      // Menunggu pembayaran
                'paid',         // Sudah bayar, menunggu konfirmasi admin
                'approved',     // Admin sudah approve
                'processing',   // Sedang diproses
                'shipped',      // Sedang dikirim
                'completed',    // Selesai
                'cancelled',    // Dibatalkan
            ])->default('pending');

            // Pembayaran
            $table->string('payment_proof')->nullable(); // path file bukti bayar
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
