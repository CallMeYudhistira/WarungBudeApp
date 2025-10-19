<?php

namespace App\Http\Controllers;

use App\Models\ExpiredLog;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpiredController extends Controller
{
    public function index()
    {
        $expired_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '<=', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'pending')->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', DB::raw('refill_stocks.updated_stock AS quantity'), 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);
        $normal_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '>', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'baik')->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', DB::raw('refill_stocks.updated_stock AS quantity'), 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);

        return view('barang.expired.index', compact('expired_products', 'normal_products'));
    }

    public function search(Request $request)
    {
        $expired = $request->expired;
        $normal = $request->normal;

        if (!$expired && !$normal) {
            return redirect('/barang/expired');
        }

        $expired_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '<=', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'baik')->where('products.product_name', 'LIKE', "%$expired%")->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', DB::raw('refill_stocks.updated_stock AS quantity'), 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);
        $normal_products = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('refill_stocks', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('refill_stocks.expired_date', '>', now()->format('Y-m-d'))->where('refill_stocks.status', '=', 'baik')->where('products.product_name', 'LIKE', "%$normal%")->select(['refill_stocks.refill_stock_id', 'product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', DB::raw('refill_stocks.updated_stock AS quantity'), 'refill_stocks.expired_date', 'refill_stocks.entry_date'])->simplePaginate(3);

        return view('barang.expired.index', compact('expired_products', 'normal_products', 'expired', 'normal'));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required',
            'refill_stock_id' => 'required',
            'quantity' => 'required'
        ]);

        $refill = RefillStock::find($request->refill_stock_id);

        $refill->update([
            'updated_stock' => $refill->updated_stock - $request->quantity,
            'status' => 'expired',
        ]);

        DB::statement("UPDATE product_details SET stock = (SELECT SUM(updated_stock) FROM refill_stocks WHERE product_detail_id = '$request->product_detail_id') WHERE product_detail_id = '$request->product_detail_id'");

        ExpiredLog::create([
            'refill_stock_id' => $request->refill_stock_id,
            'disposed_date' => now()->format('Y-m-d'),
            'quantity' => $request->quantity,
            'note' => $request->note,
            'user_id' => Auth::user()->user_id,
        ]);

        return redirect('/barang/expired')->with('success', 'Stok Barang Kedaluwarsa Telah Dibuang!');
    }

    public function history()
    {
        $logs = ExpiredLog::join('users', 'users.user_id', '=', 'expired_logs.user_id')->join('refill_stocks', 'expired_logs.refill_stock_id', '=', 'refill_stocks.refill_stock_id')->join('product_details', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('products', 'products.product_id', '=', 'product_details.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->orderBy('expired_logs.disposed_date', 'desc')->get(['expired_logs.expired_id', 'expired_logs.disposed_date', 'expired_logs.note', 'products.product_name', 'units.unit_name', 'expired_logs.quantity', 'users.name']);

        return view('barang.expired.history', compact('logs'));
    }

    public function filter(Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            return redirect('/barang/expired/history');
        }

        $logs = ExpiredLog::join('users', 'users.user_id', '=', 'expired_logs.user_id')->join('refill_stocks', 'expired_logs.refill_stock_id', '=', 'refill_stocks.refill_stock_id')->join('product_details', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('products', 'products.product_id', '=', 'product_details.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->orderBy('expired_logs.disposed_date', 'desc')->whereBetween('expired_logs.disposed_date', [$first, $second])->get(['expired_logs.expired_id', 'expired_logs.disposed_date', 'expired_logs.note', 'products.product_name', 'units.unit_name', 'expired_logs.quantity', 'users.name']);

        return view('barang.expired.history', compact('logs', 'first', 'second'));
    }

    public function exportExcelHistory(Request $request)
    {
        $fileName = 'barang_expired_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $logs = ExpiredLog::join('users', 'users.user_id', '=', 'expired_logs.user_id')->join('refill_stocks', 'expired_logs.refill_stock_id', '=', 'refill_stocks.refill_stock_id')->join('product_details', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('products', 'products.product_id', '=', 'product_details.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->orderBy('expired_logs.disposed_date', 'desc')->get(['expired_logs.expired_id', 'expired_logs.disposed_date', 'expired_logs.note', 'products.product_name', 'units.unit_name', 'expired_logs.quantity', 'users.name']);
        } else {
            $logs = ExpiredLog::join('users', 'users.user_id', '=', 'expired_logs.user_id')->join('refill_stocks', 'expired_logs.refill_stock_id', '=', 'refill_stocks.refill_stock_id')->join('product_details', 'product_details.product_detail_id', '=', 'refill_stocks.product_detail_id')->join('products', 'products.product_id', '=', 'product_details.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->orderBy('expired_logs.disposed_date', 'desc')->whereBetween('expired_logs.disposed_date', [$first, $second])->get(['expired_logs.expired_id', 'expired_logs.disposed_date', 'expired_logs.note', 'products.product_name', 'units.unit_name', 'expired_logs.quantity', 'users.name']);
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($logs) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah Stok Yang Dibuang</th>
                <th>Catatan</th>
                <th>Nama Pembuang</th>
            </tr>";
            foreach ($logs as $l) {
                echo "<tr>
                    <td>" . Carbon::parse($l->disposed_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>{$l->product_name}</td>
                    <td>{$l->quantity}</td>
                    <td>{$l->note}</td>
                    <td>{$l->name}</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }
}
