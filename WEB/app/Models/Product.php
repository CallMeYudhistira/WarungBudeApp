<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    protected $fillable = ['product_name', 'pict', 'category_id', 'purchase_price', 'selling_price', 'stock', 'unit_id'];
}
