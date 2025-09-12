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
        Schema::create('credit_details', function (Blueprint $table) {
            $table->increments('credit_detail_id');
            $table->integer('credit_id');
            $table->integer('amount_of_paid')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer("remaining_debt");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_details');
    }
};
