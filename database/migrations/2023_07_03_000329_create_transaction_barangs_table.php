<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('status')->nullable();
            $table->string('id_member')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('harga_pokok')->nullable();
            $table->integer('harga_jual')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_barangs');
    }
};
