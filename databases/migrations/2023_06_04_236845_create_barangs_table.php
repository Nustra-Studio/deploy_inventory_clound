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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_barangs');
            $table->string('sub_id');
            $table->string('kode_barang');
            $table->string('harga');
            $table->string('harga_jual');
            $table->string('harga_pokok');
            $table->string('harga_grosir');
            $table->integer('stok');
            $table->string('keterangan');
            $table->string('name');
            $table->string('merek_barang');
            $table->foreignId('type_barang_id')->constrained('type_barangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
