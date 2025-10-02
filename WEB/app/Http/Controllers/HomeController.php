<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $datas = DB::select("SELECT transactions.date, SUM(transactions.total) as total FROM transactions INNER JOIN transaction_details ON transactions.transaction_id = transaction_details.transaction_id GROUP BY transactions.date");
        $periode = [];
        $total = [];
        foreach ($datas as $data) {
            $periode[] = Carbon::parse($data->date)->translatedFormat('l, d F Y');
            $total[] = $data->total;
        }

        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->where('date', '=', now()->format('Y-m-d'))->get(['transactions.total', 'transaction_details.selling_price', 'product_details.purchase_price', 'transaction_details.quantity']);
        $omsetHariIni = 0;
        $labaHariIni = 0;
        foreach($todays as $today){
            $omsetHariIni += $today->total;
            $labaHariIni += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }
        return view('home', compact('periode', 'total', 'omsetHariIni', 'labaHariIni'));
    }

    public function filter(Request $request){
        $first = $request->first;
        $second = $request->second;

        if(!$first && !$second){
            return redirect('/home');
        }

        $datas = DB::select("SELECT transactions.date, SUM(transactions.total) as total FROM transactions INNER JOIN transaction_details ON transactions.transaction_id = transaction_details.transaction_id WHERE transactions.date BETWEEN '$first' AND '$second' GROUP BY transactions.date");
        $periode = [];
        $total = [];
        foreach ($datas as $data) {
            $periode[] = Carbon::parse($data->date)->translatedFormat('l, d F Y');
            $total[] = $data->total;
        }

        return view('home', compact('periode', 'total', 'first', 'second'));
    }
}
