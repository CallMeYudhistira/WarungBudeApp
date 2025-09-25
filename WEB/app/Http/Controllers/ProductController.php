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

        $check = false;

        foreach($categories as $category){
            if($product->category_id == $category->category_id){
                $check = true;
            }
        }

        return view('barang.update', compact('product', 'categories', 'check'));
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
        RefillStock::where('product_id', $id)->delete();

        return redirect('/barang')->with('success', 'Barang Berhasil Dihapus!');
    }

    public function index_detail($id)
    {
        $product = Product::where('product_id', $id)->first();
        $product_details = ProductDetail::join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('products.product_id', $id)->get();

        return view('barang.detail.index', compact('product', 'product_details'));
    }

    public function create_detail($id)
    {
        $units = Unit::all();
        $product = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->where('product_id', $id)->first();

        return view('barang.detail.create', compact('units', 'product'));
    }

    public function store_detail(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'unit_id' => 'required',
        ]);

        $cekDetail = ProductDetail::where('product_id', $request->id)->where('unit_id', $request->unit_id)->first();

        if (!$cekDetail && $cekDetail == null) {
            ProductDetail::create([
                'product_id' => $request->id,
                'unit_id' => $request->unit_id,
                'purchase_price' => 0,
                'selling_price' => 0,
                'stock' => 0,
            ]);
        } else {
            return redirect()->back()->with('error', 'Satuan Ini Sudah Tersedia!');
        }

        return redirect('/barang/detail/' . $request->id)->with('success', 'Detail Barang Berhasil Ditambah!');
    }

    public function edit_detail($id)
    {
        $units = Unit::all();
        $product = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->where('product_detail_id', $id)->first();

        $check = false;

        foreach($units as $unit){
            if($product->unit_id == $unit->unit_id){
                $check = true;
            }
        }

        return view('barang.detail.update', compact('units', 'product', 'check'));
    }

    public function update_detail(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'detail_id' => 'required',
            'unit_id' => 'required',
            'cek_unit' => 'required',
        ]);

        if ($request->cek_unit == $request->unit_id) {
            ProductDetail::where('product_detail_id', $request->detail_id)->update([
                'unit_id' => $request->unit_id,
            ]);
        } else {
            $cekDetail = ProductDetail::where('product_id', $request->id)->where('unit_id', $request->unit_id)->first();
            if ($cekDetail && $cekDetail != null) {
                return redirect()->back()->with('error', 'Satuan Ini Sudah Tersedia!');
            } else {
                ProductDetail::where('product_detail_id', $request->detail_id)->update([
                    'unit_id' => $request->unit_id,
                ]);
            }
        }

        return redirect('/barang/detail/' . $request->id)->with('success', 'Detail Barang Berhasil Diedit!');
    }

    public function delete_detail($id, Request $request)
    {
        ProductDetail::where('product_detail_id', $id)->delete();
        RefillStock::where('product_detail_id', $id)->delete();

        return redirect('/barang/detail/' . $request->id)->with('success', 'Detail Barang Berhasil Dihapus!');
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
