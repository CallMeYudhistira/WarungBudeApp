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
        #Omset Hari Ini
        $omsetHariIni = Transaction::where('date', now()->format('Y-m-d'))->sum('total');
        $omsetHariIniTunai = Transaction::where('date', now()->format('Y-m-d'))->where('payment', 'tunai')->sum('total');
        $omsetHariIniKredit = Transaction::where('date', now()->format('Y-m-d'))->where('payment', 'kredit')->sum('total');

        #Laba Hari Ini
        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->where('transactions.date', '=', now()->format('Y-m-d'))->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaHariIni = 0;
        foreach ($todays as $today) {
            $labaHariIni += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }
        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->where('transactions.date', '=', now()->format('Y-m-d'))->where('transactions.payment', 'tunai')->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaHariIniTunai = 0;
        foreach ($todays as $today) {
            $labaHariIniTunai += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }
        $todays = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->where('transactions.date', '=', now()->format('Y-m-d'))->where('transactions.payment', 'kredit')->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaHariIniKredit = 0;
        foreach ($todays as $today) {
            $labaHariIniKredit += ($today->selling_price - $today->purchase_price) * $today->quantity;
        }

        #Omset Bulan Ini
        $omsetBulanIni = Transaction::whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->sum('total');
        $omsetBulanIniTunai = Transaction::whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->where('payment', 'tunai')->sum('total');
        $omsetBulanIniKredit = Transaction::whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->where('payment', 'kredit')->sum('total');

        #Laba Bulan Ini
        $months = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaBulanIni = 0;
        foreach ($months as $month) {
            $labaBulanIni += ($month->selling_price - $month->purchase_price) * $month->quantity;
        }
        $months = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->where('transactions.payment', 'tunai')->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaBulanIniTunai = 0;
        foreach ($months as $month) {
            $labaBulanIniTunai += ($month->selling_price - $month->purchase_price) * $month->quantity;
        }
        $months = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->whereYear('date', '=', now()->format('Y'))->whereMonth('date', '=', now()->format('m'))->where('transactions.payment', 'kredit')->get(['transactions.total', 'transaction_details.selling_price', 'transaction_details.purchase_price', 'transaction_details.quantity']);
        $labaBulanIniKredit = 0;
        foreach ($months as $month) {
            $labaBulanIniKredit += ($month->selling_price - $month->purchase_price) * $month->quantity;
        }

        #Data Omset (Chart) Per Bulan
        $bulan = date('m');
        $tahun = date('Y');
        $totalBulan = [];
        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) { 
            $totalBulan[] = Transaction::whereYear('date', '=', $tahun)->whereMonth('date', '=', $i)->sum('total');
            $dataBulan[] = Carbon::parse('01-' . $i . '-2000')->translatedFormat('F');
        }

        return view('home',
        compact('omsetHariIni', 'omsetHariIniTunai', 'omsetHariIniKredit', 
        'labaHariIni', 'labaHariIniTunai', 'labaHariIniKredit',
        'omsetBulanIni', 'omsetBulanIniTunai', 'omsetBulanIniKredit', 
        'labaBulanIni', 'labaBulanIniTunai', 'labaBulanIniKredit', 
        'totalBulan', 'dataBulan'));
    }
}
