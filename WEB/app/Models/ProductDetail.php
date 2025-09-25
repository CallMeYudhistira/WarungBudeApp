<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'product_detail_id';
    protected $fillable = ['product_id', 'selling_price', 'purchase_price', 'unit_id', 'stock'];
}
