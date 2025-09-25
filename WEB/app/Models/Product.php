<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'product_id';
    protected $fillable = ['product_name', 'pict', 'category_id', 'purchase_price', 'selling_price', 'stock', 'unit_id'];
}
