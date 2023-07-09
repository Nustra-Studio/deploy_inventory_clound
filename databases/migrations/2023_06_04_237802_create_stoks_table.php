<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            // id_barang diambil dari tabel barang
            $table->foreignId('id_barang')->constrained('barangs');
            // interger in_stock stock out_stock min_stock min_out_stock
            $table->integer('in_stock');
            $table->integer('out_stock');
            $table->integer('min_stock');
            $table->integer('min_out_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};
