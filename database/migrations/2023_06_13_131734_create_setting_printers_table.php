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
        Schema::create('setting_printers', function (Blueprint $table) {
            $table->id();
            // buat semua tabel di bawah menjadi nullable
            $table->string('nama_printer')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('port')->nullable();
            $table->string('type_printer')->nullable();
            $table->string('status_printer')->nullable();
            // string jenis kertas kolom yg di print dan with dan hight
            $table->string('jenis_kertas')->nullable();
            $table->string('width')->nullable();
            $table->string('hight')->nullable();
            $table->string('margin_top')->nullable();
            $table->string('margin_bottom')->nullable();
            $table->string('margin_left')->nullable();
            $table->string('margin_right')->nullable();
            $table->string('keterangan')->nullable();
            
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
        Schema::dropIfExists('setting_printers');
    }
};
