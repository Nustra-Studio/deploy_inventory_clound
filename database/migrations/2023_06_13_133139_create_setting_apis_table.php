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
        Schema::create('setting_apis', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('nama_api');
            $table->string('status_api')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('id_cronjob')->nullable();
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
        Schema::dropIfExists('setting_apis');
    }
};
