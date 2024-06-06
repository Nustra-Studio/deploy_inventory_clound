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
        Schema::create('supliers', function (Blueprint $table) {
            $table->id();
            // string nama alamat telepon suplayer product 
            $table->string('nama');
            $table->string('uuid')->unique();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('product')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->nullable();
            // category_barang berhubungan dengan tabel category_barang
            $table->string('category_barang_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supliers');
    }
};
