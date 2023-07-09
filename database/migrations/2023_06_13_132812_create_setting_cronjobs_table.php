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
        Schema::create('setting_cronjobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('nama_cronjob')->nullable();
            $table->string('status_cronjob')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('waktu')->nullable();
            $table->string('hari')->nullable();
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
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
        Schema::dropIfExists('setting_cronjobs');
    }
};
