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
        Schema::create('harga_khusus', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('id_barang');
            // interger harga jumlah_minimal diskon and 
            // string keterangan satuan
            $table->integer('harga');
            $table->integer('jumlah_minimal');
            $table->integer('diskon');
            $table->string('keterangan')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_khususes');
    }
};
