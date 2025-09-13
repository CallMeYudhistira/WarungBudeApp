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
        Schema::create('refill_stocks', function (Blueprint $table) {
            $table->increments("refill_stock_id");
            $table->integer('product_detail_id');
            $table->integer('purchase_price');
            $table->integer('quantity');
            $table->integer('total');
            $table->date('entry_date');
            $table->date('expired_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refill_stocks');
    }
};
