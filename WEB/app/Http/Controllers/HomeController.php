<?php

namespace App\Http\Controllers;

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
        return view('home', compact('periode', 'total'));
    }
}
