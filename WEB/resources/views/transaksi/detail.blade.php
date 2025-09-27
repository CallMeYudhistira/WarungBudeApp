@extends('layouts.app')
@section('title', 'Transaksi || Riwayat')
@section('content')
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/transaksi/history" class="btn btn-dark m-2">Kembali</a></li>
        <li><a class="btn btn-success m-2" href="/transaksi/detail/{{ $transaction->transaction_id }}/print">Cetak</a></li>
    </ul>
    <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <h1 style="padding: 12px; text-align: center; margin: 0.5rem;">Detail Transaksi</h1>
        <div style="margin: auto; margin-top: 3vh; margin-bottom: 6vh;">
            <div class="row align-items-center m-2">
                <div class="col-2">
                    ID Transaksi
                </div>
                <div class="col-6">
                    : {{ $transaction->transaction_id }}
                </div>
                <div class="col-2">
                    Nama Kasir
                </div>
                <div class="col-2">
                    : {{ $transaction->name }}
                </div>
            </div>
            <div class="row align-items-center m-2">
                <div class="col-2">
                    Tanggal Transaksi
                </div>
                <div class="col-6">
                    : {{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('l, d / F / Y') }}
                </div>
                <div class="col-2">
                    Nama Pelanggan
                </div>
                <div class="col-2">
                    : {{ $transaction->customer_name ? $transaction->customer_name : '-' }}
                </div>
            </div>
            <div class="row align-items-center m-2">
                <div class="col-2">
                    Metode Pembayaran
                </div>
                <div class="col-2">
                    : {{ $transaction->payment }}
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr style="border-top: 1px solid #ddd;">
                    <th colspan="7" class="text-center">
                        <h3 class="mt-2">Produk Yang Dibeli</h3>
                    </th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Harga (Rp.)</th>
                    <th scope="col">Kuantitas</th>
                    <th scope="col">Subtotal</th>
                </tr>
            </thead>
            @php
                $i = 1;
            @endphp
            <tbody>
                @foreach ($transaction_details as $transaction_detail)
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>{{ $transaction_detail->product_name }}</td>
                        <td><img src="{{ asset('images/' . $transaction_detail->pict) }}" alt="foto barang"
                                style="width: 90px; border-radius: 8px;"></td>
                        <td>{{ $transaction_detail->category_name }}</td>
                        <td>{{ 'Rp ' . number_format($transaction_detail->selling_price, 0, ',', '.') }}/{{ $transaction_detail->unit_name }}</td>
                        <td>{{ $transaction_detail->quantity }} {{ $transaction_detail->unit_name }}</td>
                        <td>{{ 'Rp ' . number_format($transaction_detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin: auto; margin-top: 6vh; margin-bottom: 3vh;">
            <div class="row align-items-center m-2">
                <div class="col-8"></div>
                <div class="col-2">
                    Total Belanja
                </div>
                <div class="col-2">
                    : {{ 'Rp ' . number_format($transaction->total, 0, ',', '.') }}
                </div>
            </div>
            <div class="row align-items-center m-2">
                <div class="col-8"></div>
                <div class="col-2">
                    Bayar
                </div>
                <div class="col-2">
                    : {{ 'Rp ' . number_format($transaction->pay, 0, ',', '.') }}
                </div>
            </div>
            <div class="row align-items-center m-2">
                <div class="col-8"></div>
                <div class="col-2">
                    Kembalian
                </div>
                <div class="col-2">
                    : {{ 'Rp ' . number_format($transaction->change, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
@endsection
