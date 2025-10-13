@extends('layouts.app')
@section('title', 'Transaksi || Riwayat')
@section('content')
    <h1>Riwayat Transaksi</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/transaksi" class="btn btn-dark m-2">Kembali</a>
        <form action="/transaksi/history/export" method="get">
            <input type="hidden" name="first" @isset($first) value="{{ $first }}" @endisset />
            <input type="hidden" name="second" @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-success m-2" type="submit">Cetak</button>
        </form>
        <form class="d-flex m-2 ms-auto" action="/transaksi/history/filter" method="get">
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
                    <th scope="col" style="width: 18%;">Tanggal</th>
                    <th scope="col">Total (Rp.)</th>
                    <th scope="col">Bayar (Rp.)</th>
                    <th scope="col">Kembalian (Rp.)</th>
                    <th scope="col">Metode Pembayaran</th>
                    <th scope="col">Nama Kasir</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $i => $transaction)
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td scope="row">{{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('l, d/F/Y') }}</td>
                        <td scope="row">{{ 'Rp ' . number_format($transaction->total, 0, ',', '.') }}</td>
                        <td scope="row">{{ 'Rp ' . number_format($transaction->pay, 0, ',', '.') }}</td>
                        <td scope="row">{{ 'Rp ' . number_format($transaction->change, 0, ',', '.') }}</td>
                        <td scope="row"><span style="background: #eee; border-radius: 6px; border: 1px solid #ccc; padding: 2px 14px; color: black;">{{ $transaction->payment }}</span></td>
                        <td scope="row">{{ $transaction->name }}</td>
                        <td scope="row">{{ $transaction->customer_name ? $transaction->customer_name : '-' }}</td>
                        <td scope="row" class="text-center"><a href="/transaksi/detail/{{ $transaction->transaction_id }}" class="btn btn-success">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
