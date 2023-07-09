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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            // string user_id gudang_id inventory_id printer bahasa
            $table->string('user_id')->nullable();
            $table->string('gudang_id')->nullable();;
            $table->string('inventory_id')->nullable();;
            $table->string('printer')->nullable();;
            $table->string('bahasa')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
