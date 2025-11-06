<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'credit_payment_id';
    protected $fillable = ['customer_id', 'amount_of_debt', 'amount_of_paid', 'payment_date', 'remaining_debt', 'change', 'user_id'];
}
