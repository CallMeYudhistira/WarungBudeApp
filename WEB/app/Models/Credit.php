<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $primaryKey = 'credit_id';
    protected $fillable = ['transaction_id', 'customer_id', 'total'];
}
