<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    

    public function index($id)
    {
        $product = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->where('product_id', $id)->first();
        $units = Unit::all();
        $product_details = ProductDetail::join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('products.product_id', $id)->get();

        return view('barang.detail.index', compact('product',  'product_details', 'units'));
    }

    public function store(Request $request)
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

    public function update(Request $request)
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

    public function delete($id, Request $request)
    {
        ProductDetail::where('product_detail_id', $id)->delete();
        RefillStock::where('product_detail_id', $id)->delete();

        return redirect('/barang/detail/' . $request->id)->with('success', 'Detail Barang Berhasil Dihapus!');
    }
}
