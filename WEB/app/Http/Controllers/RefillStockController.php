<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefillStockController extends Controller
{
    public function index($id)
    {
        $product = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('product_detail_id', $id)->first();
        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->where('refill_stocks.product_detail_id', $id)->orderBy('refill_stocks.refill_stock_id', 'desc')->get();

        return view('barang.stok.index', compact('product', 'refillStocks'));
    }

    public function filter($id, Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if(!$first && !$second){
            return redirect('/barang/refillStock/' . $id);
        }

        $product = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('product_detail_id', $id)->first();
        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->where('refill_stocks.product_detail_id', $id)->whereBetween('entry_date', [$first, $second])->orderBy('refill_stocks.refill_stock_id', 'desc')->get();

        return view('barang.stok.index', compact('product', 'refillStocks', 'first', 'second'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'detail_id' => 'required|numeric',
            'price' => 'required|numeric|min:500',
            'quantity' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:500',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->expired_date <= now()->format('Y-m-d')){
            $validator->errors()->add('expired_date', 'Tanggal expired sudah lewat!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        RefillStock::create([
            'product_detail_id' => $request->detail_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'entry_date' => now(),
            'expired_date' => $request->expired_date,
            'status' => 'baik',
        ]);

        $new_refill = RefillStock::where('product_detail_id', $request->detail_id)->orderBy('refill_stock_id', 'desc')->first();
        $product = ProductDetail::where('product_detail_id', $request->detail_id)->first();
        $margin = 0.20;
        $price_per_unit = $new_refill->total / $new_refill->quantity;
        $profit = $new_refill->price * $margin;

        ProductDetail::where('product_detail_id', $request->detail_id)->update([
            'stock' => $product->stock + $new_refill->quantity,
            'purchase_price' => $new_refill->price,
            'selling_price' => round(($price_per_unit + $profit) / 500) * 500,
        ]);

        return redirect('/barang/refillStock/' . $request->detail_id)->with('success', 'Stok Berhasil Ditambahkan!');
    }
}
