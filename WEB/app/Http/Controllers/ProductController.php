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
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->simplePaginate('10');

        return view('barang.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/barang');
        }

        $categories = Category::all();
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->where('product_name', 'like', '%' . $keyword . '%')->simplePaginate('10');

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
}
