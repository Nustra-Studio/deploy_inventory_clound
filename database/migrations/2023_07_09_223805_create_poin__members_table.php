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
        Schema::create('poin_members', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('id_member')->nullable();
            $table->string('poin')->nullable();
            $table->string('status')->nullable();
            $table->string('expaid')->nullable();
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
        Schema::dropIfExists('poin_members');
    }
};
