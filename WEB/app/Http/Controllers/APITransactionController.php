<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APITransactionController extends Controller
{
    public function show_products()
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->select('product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'product_details.selling_price', 'product_details.stock')->get();
        $customers = Customer::get(['customer_id', 'customer_name']);

        return response()->json(['status' => 'success', 'products' => $products, 'customers' => $customers], 200);
    }

    public function show_carts($user_id)
    {
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', $user_id)->select('carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name')->get();

        return response()->json(['status' => 'success', 'carts' => $carts], 200);
    }
}
