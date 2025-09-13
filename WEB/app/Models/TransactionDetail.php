<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_detail_id';
    protected $fillable = ['transaction_id', 'product_detail_id', 'selling_price', 'quantity', 'subtotal'];
}
