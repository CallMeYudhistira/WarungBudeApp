<?php

namespace App\Http\Controllers;

use App\Models\CreditPayment;
use App\Models\Customer;
use App\Models\RefillStock;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            return redirect('/beranda/admin');
        } else if (Auth::user()->role == 'gudang') {
            return redirect('/beranda/gudang');
        } else if (Auth::user()->role == 'kasir') {
            return redirect('/beranda/kasir');
        }
    }

    public function gudang()
    {
        $stok = count(DB::select("SELECT * FROM CekStok"));
        $expired = count(DB::select("SELECT * FROM CekExpired WHERE expired_date <= '" . now()->format('Y-m-d') . "'"));
        $barang_masuk = RefillStock::whereMonth('entry_date', now()->format('m'))->count();
        $products = collect(DB::select("SELECT * FROM CekStok"));
        return view('beranda.gudang', compact('stok', 'expired', 'barang_masuk', 'products'));
    }

    public function admin()
    {
        $stok = count(DB::select("SELECT * FROM CekStok"));
        $expired = count(DB::select("SELECT * FROM CekExpired WHERE expired_date <= '" . now()->format('Y-m-d') . "'"));
        $barang_masuk = RefillStock::whereMonth('entry_date', now()->format('m'))->count();
        $products = collect(DB::select("SELECT * FROM CekStok"));

        #Omset Hari Ini
        $omsetHariIni = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('Omset');
        $omsetHariIniTunai = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('OmsetTunai');
        $omsetHariIniKredit = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('OmsetKredit');

        #Laba Hari Ini
        $labaHariIni = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('Laba');
        $labaHariIniTunai = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('LabaTunai');
        $labaHariIniKredit = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('LabaKredit');

        #Omset Bulan Ini
        $omsetBulanIni = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('Omset');
        $omsetBulanIniTunai = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('OmsetTunai');
        $omsetBulanIniKredit = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('OmsetKredit');

        #Laba Bulan Ini
        $labaBulanIni = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('Laba');
        $labaBulanIniTunai = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('LabaTunai');
        $labaBulanIniKredit = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('LabaKredit');

        #Data Omset (Chart) Per Bulan
        $bulan = date('m');
        $tahun = date('Y');
        $modalBulan = [];
        $omsetBulan = [];
        $labaBulan = [];
        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $record = DB::table('RekapBulan')
                ->select('Modal', 'Omset', 'Laba')
                ->where('Bulan', $i)
                ->orderByDesc('Bulan')
                ->first();

            $modalBulan[] = $record ? $record->Modal : 0;
            $omsetBulan[] = $record ? $record->Omset : 0;
            $labaBulan[] = $record ? $record->Laba : 0;
            $dataBulan[] = Carbon::parse('01-' . $i . '-2000')->translatedFormat('F');
        }

        return view(
            'beranda.admin',
            compact(
                'omsetHariIni',
                'omsetHariIniTunai',
                'omsetHariIniKredit',
                'labaHariIni',
                'labaHariIniTunai',
                'labaHariIniKredit',
                'omsetBulanIni',
                'omsetBulanIniTunai',
                'omsetBulanIniKredit',
                'labaBulanIni',
                'labaBulanIniTunai',
                'labaBulanIniKredit',
                'modalBulan',
                'omsetBulan',
                'labaBulan',
                'dataBulan',
                'stok',
                'expired',
                'barang_masuk',
                'products'
            )
        );
    }

    public function kasir()
    {
        #Omset Hari Ini
        $omsetHariIni = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('Omset');
        $omsetHariIniTunai = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('OmsetTunai');
        $omsetHariIniKredit = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('OmsetKredit');

        #Laba Hari Ini
        $labaHariIni = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('Laba');
        $labaHariIniTunai = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('LabaTunai');
        $labaHariIniKredit = collect(DB::select("SELECT * FROM RekapHari WHERE date = '" . now()->format('Y-m-d') . "'"))->sum('LabaKredit');

        #Omset Bulan Ini
        $omsetBulanIni = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('Omset');
        $omsetBulanIniTunai = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('OmsetTunai');
        $omsetBulanIniKredit = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('OmsetKredit');

        #Laba Bulan Ini
        $labaBulanIni = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('Laba');
        $labaBulanIniTunai = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('LabaTunai');
        $labaBulanIniKredit = collect(DB::select("SELECT * FROM RekapBulan WHERE Bulan = '" . now()->format('m') . "'"))->sum('LabaKredit');

        #Data Omset (Chart) Per Bulan
        $bulan = date('m');
        $tahun = date('Y');
        $modalBulan = [];
        $omsetBulan = [];
        $labaBulan = [];
        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $record = DB::table('RekapBulan')
                ->select('Modal', 'Omset', 'Laba')
                ->where('Bulan', $i)
                ->orderByDesc('Bulan')
                ->first();

            $modalBulan[] = $record ? $record->Modal : 0;
            $omsetBulan[] = $record ? $record->Omset : 0;
            $labaBulan[] = $record ? $record->Laba : 0;
            $dataBulan[] = Carbon::parse('01-' . $i . '-2000')->translatedFormat('F');
        }

        return view(
            'beranda.kasir',
            compact(
                'omsetHariIni',
                'omsetHariIniTunai',
                'omsetHariIniKredit',
                'labaHariIni',
                'labaHariIniTunai',
                'labaHariIniKredit',
                'omsetBulanIni',
                'omsetBulanIniTunai',
                'omsetBulanIniKredit',
                'labaBulanIni',
                'labaBulanIniTunai',
                'labaBulanIniKredit',
                'modalBulan',
                'omsetBulan',
                'labaBulan',
                'dataBulan',
            )
        );
    }
}
