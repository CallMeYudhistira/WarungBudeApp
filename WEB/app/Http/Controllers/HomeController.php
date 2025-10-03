<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        Carbon::setLocale('id');

        $datas = DB::select("SELECT transactions.date, SUM(transactions.total) as total FROM transactions INNER JOIN transaction_details ON transactions.transaction_id = transaction_details.transaction_id GROUP BY transactions.date");
        $periode = [];
        $total = [];
        foreach ($datas as $data) {
            $periode[] = Carbon::parse($data->date)->translatedFormat('l, d F Y');
            $total[] = $data->total;
        }

        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->where('transactions.date', '=', now()->format('Y-m-d'))->get(['transactions.total', 'transaction_details.selling_price', 'product_details.purchase_price', 'transaction_details.quantity']);
        $omsetHariIni = 0;
        $labaHariIni = 0;
        foreach ($todays as $today) {
            $omsetHariIni += $today->total;
            $labaHariIni += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }

        $months = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->whereBetween('transactions.date', [now()->format('Y-m-1'), now()->format('Y-m-31')])->get(['transactions.total', 'transaction_details.selling_price', 'product_details.purchase_price', 'transaction_details.quantity']);
        $omsetBulanIni = 0;
        $labaBulanIni = 0;
        foreach ($months as $month) {
            $omsetBulanIni += $month->total;
            $labaBulanIni += ($month->selling_price - $month->purchase_price) * $month->quantity;
        }

        return view('home', compact('periode', 'total', 'omsetHariIni', 'labaHariIni', 'omsetBulanIni', 'labaBulanIni'));
    }
}
