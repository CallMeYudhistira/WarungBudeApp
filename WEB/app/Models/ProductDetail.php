<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'purchase_price', 'unit_id', 'quantity_of_unit', 'amount_per_unit', 'entry_date', 'expired_date'];
}
