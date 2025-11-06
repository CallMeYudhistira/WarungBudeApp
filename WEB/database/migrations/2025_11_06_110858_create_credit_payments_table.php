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
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->increments('credit_payment_id');
            $table->integer('customer_id');
            $table->integer('amount_of_debt');
            $table->integer('amount_of_paid');
            $table->integer('remaining_debt');
            $table->integer('change');
            $table->integer('user_id');
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_payments');
    }
};
