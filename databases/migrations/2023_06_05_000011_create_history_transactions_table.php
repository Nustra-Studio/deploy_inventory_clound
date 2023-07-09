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
        Schema::create('history_transactions', function (Blueprint $table) {
            $table->id();
            // string name satuan  kode_brang kode_transcation_suppliers  
            $table->string('name');
            $table->string('satuan');
            $table->string('kode_barang');
            $table->string('kode_transaksi_suppliers');
            // interger harga_pokok harga_jual supllayer
            $table->integer('harga_pokok');
            $table->integer('harga_jual');
            $table->integer('id_supllayer');
            // time date masuk barang
            $table->time('masuk_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_transactions');
    }
};
