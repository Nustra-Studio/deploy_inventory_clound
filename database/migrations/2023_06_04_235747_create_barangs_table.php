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
            $table->string('uuid')->unique();
            $table->string('category_id');
            $table->string('id_supplier');
            $table->string('kode_barang')->nullable();
            $table->string('harga')->nullable();
            $table->string('harga_jual')->nullable();
            $table->string('harga_pokok')->nullable();
            $table->string('harga_grosir')->nullable();
            $table->integer('stok');
            $table->string('keterangan')->nullable();
            $table->string('name');
            $table->string('merek_barang')->nullable();
            $table->string('type_barang_id')->nullable();
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
