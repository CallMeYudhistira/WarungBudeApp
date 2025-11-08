<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use Carbon\Carbon;
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

    public function exportExcel($id, Request $request)
    {
        $product = ProductDetail::join('products', 'products.product_id', 'product_details.product_id')->where('product_detail_id', $id)->first();
        $fileName = 'stok_' . $product->product_name . '_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('refill_stocks.product_detail_id', $id)->orderBy('refill_stocks.refill_stock_id', 'desc')->get();
        } else {
            $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('refill_stocks.product_detail_id', $id)->whereBetween('entry_date', [$first, $second])->orderBy('refill_stocks.refill_stock_id', 'desc')->get();
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($refillStocks) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Nama Barang</th>
                <th>Total Beli</th>
                <th>Jumlah Barang</th>
                <th>Harga Per Satuan</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Status</th>
            </tr>";
            foreach ($refillStocks as $r) {
                echo "<tr>
                    <td>{$r->product_name}</td>
                    <td>{$r->total}</td>
                    <td>{$r->quantity}</td>
                    <td>{$r->price} / {$r->unit_name}</td>
                    <td>" . Carbon::parse($r->entry_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>" . Carbon::parse($r->expired_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>{$r->status}</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }

    public function history(){
        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->orderBy('refill_stocks.refill_stock_id', 'desc')->get();

        return view('barang.stok.history', compact('refillStocks'));
    }

    public function historyFilter(Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if(!$first && !$second){
            return redirect('/barang/refillStock/history');
        }

        $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->whereBetween('entry_date', [$first, $second])->orderBy('refill_stocks.refill_stock_id', 'desc')->get();

        return view('barang.stok.history', compact('refillStocks', 'first', 'second'));
    }

    public function exportExcelHistory(Request $request)
    {
        $fileName = 'stok_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->orderBy('refill_stocks.refill_stock_id', 'desc')->get();
        } else {
            $refillStocks = RefillStock::join('product_details', 'product_details.product_detail_id', 'refill_stocks.product_detail_id')->join('products', 'product_details.product_id', 'products.product_id')->join('units', 'units.unit_id', 'product_details.unit_id')->whereBetween('entry_date', [$first, $second])->orderBy('refill_stocks.refill_stock_id', 'desc')->get();
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($refillStocks) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Nama Barang</th>
                <th>Total Beli</th>
                <th>Jumlah Barang</th>
                <th>Harga Per Satuan</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Status</th>
            </tr>";
            foreach ($refillStocks as $r) {
                echo "<tr>
                    <td>{$r->product_name}</td>
                    <td>{$r->total}</td>
                    <td>{$r->quantity}</td>
                    <td>{$r->price} / {$r->unit_name}</td>
                    <td>" . Carbon::parse($r->entry_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>" . Carbon::parse($r->expired_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>{$r->status}</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'detail_id' => 'required|numeric',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:500',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tanggal_kedaluwarsa = $request->expired_date;

        if(!$tanggal_kedaluwarsa || $tanggal_kedaluwarsa == null){
            $tanggal_kedaluwarsa = null;
        }

        if($tanggal_kedaluwarsa && $tanggal_kedaluwarsa <= now()->format('Y-m-d')){
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
            'updated_stock' => $request->quantity,
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
