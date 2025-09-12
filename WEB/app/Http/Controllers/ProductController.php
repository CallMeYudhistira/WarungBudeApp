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
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->get();

        return view('barang.index', compact('products'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/barang');
        }

        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->where('product_name', 'like', '%' . $keyword . '%')->get();

        return view('barang.index', compact('products', 'keyword'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('barang.create', compact('categories'));
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

    public function edit(string $id)
    {
        $product = Product::where('product_id', $id)->first();
        $categories = Category::all();

        return view('barang.update', compact('product', 'categories'));
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
        RefillStock::where('product_id', $id)->delete();

        return redirect('/barang')->with('success', 'Barang Berhasil Dihapus!');
    }

    public function detail($id){
        $product = Product::where('product_id', $id)->first();
        $details = ProductDetail::join('units', 'units.unit_id', 'product_details.unit_id')->where('product_id', $id)->get();

        return view('barang.detail', compact('product', 'details'));
    }

    public function filter($id, Request $request){
        $first = $request->first;
        $second = $request->second;
        $product = Product::where('product_id', $id)->first();
        $details = ProductDetail::join('units', 'units.unit_id', 'product_details.unit_id')->where('product_id', $id)->whereBetween('entry_date', [$first, $second])->get();

        return view('barang.detail', compact('product', 'details', 'first', 'second'));
    }

    public function add_stock($id){
        $product = Product::where('product_id', $id)->first();
        $units = Unit::all();

        return view('barang.add_stock', compact('product', 'units'));
    }

    public function store_stock(Request $request){
        $request->validate([
            'product_id' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'quantity_of_unit' => 'required|numeric',
            'amount_per_unit' => 'required|numeric',
            'expired_date' => 'required|date',
        ]);

        ProductDetail::create([
            'product_id' => $request->product_id,
            'purchase_price' => $request->purchase_price,
            'unit_id' => $request->unit_id,
            'quantity_of_unit' => $request->quantity_of_unit,
            'amount_per_unit' => $request->amount_per_unit,
            'entry_date' => now(),
            'expired_date' => $request->expired_date,
        ]);

        $new_detail = ProductDetail::where('product_id', $request->product_id)->orderBy('product_detail_id', 'desc')->first();
        $product = Product::where('product_id', $request->product_id)->first();
        $margin = 0.20;
        $price_per_pcs = ($new_detail->purchase_price / $new_detail->quantity_of_unit) / $new_detail->amount_per_unit;
        $profit = $price_per_pcs * $margin;

        $add_stock = $new_detail->amount_per_unit * $new_detail->quantity_of_unit;

        Product::where('product_id', $request->product_id)->update([
            'stock' => $product->stock + $add_stock,
            'selling_price' => round(($price_per_pcs + $profit) / 500) * 500,
        ]);

        return redirect('/barang/detail/' . $request->product_id)->with('success', 'Stok Berhasil Ditambahkan!');
    }
}
