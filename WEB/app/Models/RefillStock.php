<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefillStock extends Model
{
    use HasFactory;

    protected $primaryKey = 'refill_stock_id';
    protected $fillable = ['product_detail_id', 'price', 'quantity', 'total', 'entry_date', 'expired_date'];
}
