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
        Schema::create('transaction_members', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('nama_barang')->nullable();
            $table->string('jumlah_barang')->nullable();
            $table->string('harga')->nullable();
            $table->string('id_member')->nullable();
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
        Schema::dropIfExists('transaction_members');
    }
};
