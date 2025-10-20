<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class APITransactionController extends Controller
{
    public function show_products(Request $request)
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->where('products.product_name', 'LIKE', '%' . $request->product_name . '%')->select('product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'product_details.purchase_price', 'product_details.selling_price', 'product_details.stock')->get();
        $customers = Customer::get(['customer_id', 'customer_name']);

        return response()->json(['status' => 'success', 'products' => $products, 'customers' => $customers], 200);
    }

    public function show_carts(Request $request)
    {
        $user_id = $request->user()->user_id;
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', $user_id)->select('carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name')->get();

        return response()->json(['status' => 'success', 'carts' => $carts], 200);
    }

    public function cartStore(Request $request)
    {
        $user_id = $request->user()->user_id;
        $product = ProductDetail::find($request->product_detail_id);
        $cekCart = Cart::where('product_detail_id', $request->product_detail_id)->first();

        $validation = Validator::make($request->all(), [
            'product_detail_id' => 'required',
            'quantity' => 'numeric|required|max:' . $product->stock,
            'selling_price' => 'required',
            'purchase_price' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['status' => 'error', 'message' => $validation->errors()->all()], 400);
        }

        if ($cekCart || $cekCart != null) {
            $newQuantity = $cekCart->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                Cart::where('product_detail_id', $request->product_detail_id)->update([
                    'quantity' => $product->stock,
                    'subtotal' => ($product->stock * $request->selling_price),
                ]);
                return response()->json(['status' => 'error', 'message' => 'Kuantitas sudah maksimal!'], 400);
            }
            Cart::where('product_detail_id', $request->product_detail_id)->update([
                'quantity' => $newQuantity,
                'subtotal' => ($newQuantity * $request->selling_price),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Kuantitas bertambah +' . $request->quantity], 200);
        }

        Cart::create([
            'product_detail_id' => $request->product_detail_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'quantity' => $request->quantity,
            'subtotal' => ($request->quantity * $request->selling_price),
            'user_id' => $user_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Produk ditambahkan ke keranjang'], 201);
    }
}
