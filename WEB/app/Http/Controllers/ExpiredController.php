<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use Illuminate\Http\Request;

class ExpiredController extends Controller
{
    public function index(){
        $expired_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '<=', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'baik')->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'refill_stocks.quantity', 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);
        $normal_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '>', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'baik')->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'refill_stocks.quantity', 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);

        return view('barang.expired.index', compact('expired_products', 'normal_products'));
    }

    public function delete(Request $request){
        $request->validate([
            'product_detail_id' => 'required',
            'refill_stock_id' => 'required',
            'quantity' => 'required'
        ]);

        $product = ProductDetail::find($request->product_detail_id);
        $refill = RefillStock::find($request->refill_stock_id);

        $product->update([
            'stock' => $product->stock - $request->quantity,
        ]);

        $refill->update([
            'status' => 'expired',
        ]);

        return redirect('/barang/expired')->with('success', 'Stok Barang Kedaluwarsa Telah Dibuang!');
    }
}
