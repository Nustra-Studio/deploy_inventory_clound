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
        Schema::create('kondisi_barangs', function (Blueprint $table) {
            $table->id();
            // string name keterangan
            $table->string('name');
            $table->string('keterangan');
            // id_type_barang yang berhubungan dengan tabel type_barangs
            $table->foreignId('id_type_barang')->constrained('type_barangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_barangs');
    }
};
