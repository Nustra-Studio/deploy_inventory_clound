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
        Schema::create('opnames', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullabel();
            $table->string('barcode')->nullabel();
            $table->string('stock')->nullabel();
            $table->string('perubahan')->nullabel();
            $table->string('id_toko')->nullabel();
            $table->string('status')->nullabel();
            $table->string('keterangan')->nullabel();
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
        Schema::dropIfExists('opnames');
    }
};
