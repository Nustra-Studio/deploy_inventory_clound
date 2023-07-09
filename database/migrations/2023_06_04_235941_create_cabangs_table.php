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
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            // string nama kepala_cabang telepon alamat category_id keterangan
            $table->string('nama');
            $table->string('kepala_cabang');
            $table->string('telepon');
            $table->string('alamat');
            $table->string('category_id');
            $table->string('keterangan');
            $table->string('database');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabangs');
    }
};
