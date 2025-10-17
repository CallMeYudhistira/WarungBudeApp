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
        Schema::create('expired_logs', function (Blueprint $table) {
            $table->increments('expired_id');
            $table->integer('refill_stock_id');
            $table->dateTime('disposed_date');
            $table->integer('quantity');
            $table->string('note')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expired_logs');
    }
};
