<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use App\Models\Unit;
use DateTime;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->get();

        return view('barang.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/barang');
        }

        $categories = Category::all();
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->where('product_name', 'like', '%' . $keyword . '%')->get();

        return view('barang.index', compact('products', 'keyword', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'pict' => 'image|mimes:jpg,png,jpeg',
            'category_id' => 'required|numeric',
        ]);

        $namagambar = 'photo.png';

        if ($request->hasFile('pict')) {
            $datetime = now()->format('d-m-Y_H-i-s');
            $gambar = $request->file('pict');
            $namagambar = $datetime . '_' . $gambar->hashName();
            $gambar->move(public_path('images'), $namagambar);
        }

        Product::create([
            'product_name' => $request->product_name,
            'pict' => $namagambar,
            'category_id' => $request->category_id,
        ]);

        return redirect('/barang')->with('success', 'Barang Berhasil Ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'product_name' => 'required',
            'pict' => 'image|mimes:jpg,png,jpeg',
            'category_id' => 'required|numeric',
        ]);

        $data = Product::where('product_id', $request->id)->first();
        $namagambar = $data->pict;

        if ($request->hasFile('pict')) {
            if ($data->pict && file_exists(public_path('images/' . $data->pict))) {
                unlink(public_path('images/' . $data->pict));
            }

            $datetime = now()->format('d-m-Y_H-i-s');
            $gambar = $request->file('pict');
            $namagambar = $datetime . '_' . $gambar->hashName();
            $gambar->move(public_path('images'), $namagambar);
        }

        Product::where('product_id', $request->id)->update([
            'product_name' => $request->product_name,
            'pict' => $namagambar,
            'category_id' => $request->category_id,
        ]);

        return redirect('/barang')->with('success', 'Barang Berhasil Diedit!');
    }

    public function destroy(string $id)
    {
        $data = Product::where('product_id', $id)->first();

        if ($data->pict && file_exists(public_path('images/' . $data->pict))) {
            unlink(public_path('images/' . $data->pict));
        }

        Product::where('product_id', $id)->delete();
        ProductDetail::where('product_id', $id)->delete();
        RefillStock::join('product_details', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->where('product_details.product_id', $id)->delete();

        return redirect('/barang')->with('success', 'Barang Berhasil Dihapus!');
    }

    public function index_stock($id)
    {
        $product = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('product_detail_id', $id)->first();
        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->where('refill_stocks.product_detail_id', $id)->get();

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
        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->where('refill_stocks.product_detail_id', $id)->whereBetween('entry_date', [$first, $second])->get();

        return view('barang.stok.index', compact('product', 'refillStocks', 'first', 'second'));
    }

    public function add_stock($id)
    {
        $product = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('product_detail_id', $id)->first();

        return view('barang.stok.add_stock', compact('product'));
    }

    public function store_stock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
            'detail_id' => 'required|numeric',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        RefillStock::create([
            'product_detail_id' => $request->detail_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'entry_date' => now(),
            'expired_date' => $request->expired_date,
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
