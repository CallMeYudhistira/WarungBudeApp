<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiredLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'expired_id';
    protected $fillable = ['refill_stock_id', 'disposed_date', 'note', 'user_id'];
}
