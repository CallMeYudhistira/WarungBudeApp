<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ExpiredController extends Controller
{
    public function index(){
        $products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->where('refill_stocks.expired_date', '<=', now()->format('Y-m-d'))->get();
        dd($products);
        return view('barang.expired.index', compact('products'));
    }
}
