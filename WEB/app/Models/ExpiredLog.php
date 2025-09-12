<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiredLog extends Model
{
    use HasFactory;

    protected $fillable = ['product_detail_id', 'unit_id', 'quantity', 'disposed_date', 'note', 'user_id'];
}
