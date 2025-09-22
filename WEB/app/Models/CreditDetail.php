<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'credit_detail_id';
    protected $fillable = ['customer_id', 'amount_of_paid', 'payment_date', 'remaining_debt', 'change'];
}
