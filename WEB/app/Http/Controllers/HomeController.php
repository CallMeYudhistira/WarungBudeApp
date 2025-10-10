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
        $bulan = date('m');
        $tahun = date('Y');
        $totalBulan = [];
        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) { 
            $totalBulan[] = Transaction::whereYear('date', '=', $tahun)->whereMonth('date', '=', $i)->sum('total');
            $dataBulan[] = Carbon::parse('01-' . $i . '-2000')->translatedFormat('F');
        }

        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->where('transactions.date', '=', now()->format('Y-m-d'))->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $omsetHariIni = Transaction::where('date', now()->format('Y-m-d'))->sum('total');
        $labaHariIni = 0;
        foreach ($todays as $today) {
            $labaHariIni += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }

        $months = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $omsetBulanIni = Transaction::whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->sum('total');
        $labaBulanIni = 0;
        foreach ($months as $month) {
            $labaBulanIni += ($month->selling_price - $month->purchase_price) * $month->quantity;
        }

        return view('home', compact('omsetHariIni', 'labaHariIni', 'omsetBulanIni', 'labaBulanIni', 'totalBulan', 'dataBulan'));
    }
}
