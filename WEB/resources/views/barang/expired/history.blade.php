@extends('layouts.app')
@section('title', 'Transaksi || Riwayat')
@section('content')
    <h1>Riwayat Kedaluwarsa</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/barang/expired" class="btn btn-dark m-2">Kembali</a>
        <form class="d-flex m-2 ms-auto" action="/barang/expired/history/filter" method="get">
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
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Jumlah Stok Yang Dibuang</th>
                    <th scope="col" style="width: 20%;">Catatan</th>
                    <th scope="col">Tanggal Dibuang</th>
                    <th scope="col">Nama Pembuang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $i => $log)
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td>{{ $log->product_name }}</td>
                        <td>{{ $log->quantity }} {{ $log->unit_name }}</td>
                        <td>{{ $log->note ? $log->note : '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->disposed_date)->translatedFormat('l, d/F/Y') }}</td>
                        <td>{{ $log->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
