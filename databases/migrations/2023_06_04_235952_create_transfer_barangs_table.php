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
        Schema::create('transfer_barangs', function (Blueprint $table) {
            $table->id();
            // string product status keterangan transction_cabang_id
            $table->string('product');
            $table->string('status');
            $table->string('keterangan');
            $table->string('transction_cabang_id');
            // interger jumlah transction
            $table->integer('jumlah');
            $table->integer('id_transction');
            // id_cabang berhubungan dengan tabel cabangs
            $table->foreignId('id_cabang')->constrained('cabangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_barangs');
    }
};
