@extends('layouts.app')
@section('title', 'Transaksi || Pendapatan')
@section('content')
    <h1>Rekap Pendapatan</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/transaksi" class="btn btn-dark m-2">Kembali</a>
        <form action="/transaksi/pendapatan/export" method="get">
            <input type="hidden" name="first" @isset($first) value="{{ $first }}" @endisset />
            <input type="hidden" name="second" @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-success m-2" type="submit">Cetak</button>
        </form>
        <form class="d-flex m-2 ms-auto" action="/transaksi/pendapatan/filter" method="get">
            <input class="form-control me-2" type="date" name="first"
                @isset($first) value="{{ $first }}" @endisset />
            <label for="second" class="form-label m-2">=></label>
            <input class="form-control me-2" type="date" name="second"
                @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>
    <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" style="width: 20%;">Tanggal</th>
                    <th scope="col">Modal (Rp.)</th>
                    <th scope="col">Omset (Rp.)</th>
                    <th scope="col">Laba (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                @if (count($transactions) > 0)
                    @foreach ($transactions as $i => $transaction)
                        <tr class="head">
                            <th scope="row" rowspan="3">{{ $i + 1 }}</th>
                            <td rowspan="3">{{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('l, d/F/Y') }}</td>
                            <td rowspan="3">{{ 'Rp ' . number_format($transaction->Modal, 0, ',', '.') }}</td>
                            <td style="border: none;">{{ 'Rp ' . number_format($transaction->Omset, 0, ',', '.') }}</td>
                            <td style="border: none;">{{ 'Rp ' . number_format($transaction->Laba, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="body">
                            <td>Tunai : {{ 'Rp ' . number_format($transaction->OmsetTunai, 0, ',', '.') }}</td>
                            <td>Tunai : {{ 'Rp ' . number_format($transaction->LabaTunai, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="footer">
                            <td>Kredit : {{ 'Rp ' . number_format($transaction->OmsetKredit, 0, ',', '.') }}</td>
                            <td>Kredit : {{ 'Rp ' . number_format($transaction->LabaKredit, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">
                            <div class="alert alert-primary p-3 text-center" role="alert"
                                style="width: 500px; margin: auto; margin-top: 2rem; margin-bottom: 2rem;">
                                ❌ Laporan pendapatan tidak ditemukan. ❌
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
