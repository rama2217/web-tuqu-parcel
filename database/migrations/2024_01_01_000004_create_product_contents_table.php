<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('item'); // e.g. "12x Red Roses", "Baby's Breath"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_contents');
    }
};
