@extends('layouts.app')
@section('title', 'Barang || Riwayat Isi Stok')
@section('content')
    <h1 style="padding-bottom: 12px; margin-left: -5px;">Riwayat Isi Stok</h1>
    <h2 style="padding-top: 12px; border-top: 1px solid #ccc;">{{ $product->product_name }} ({{ $product->unit_name }}) || History Isi Stok</h2>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <button type="button" class="btn btn-primary m-1 mt-2 mb-2" data-bs-toggle="modal" data-bs-target="#isiStokBarang">Tambah</button>
        @include('barang.stok.modal.create')
        <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-dark m-1 mt-2 mb-2">Kembali</a>

        <form class="d-flex m-1 mt-2 mb-2 ms-auto" action="/barang/refillStock/{{ $product->product_detail_id }}/filter" method="get">
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
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col" style="width: 21%">Harga Beli</th>
                    <th scope="col" style="width: 21%">Kuantitas</th>
                    <th scope="col" style="width: 21%">Total</th>
                    <th scope="col" style="width: 16%">Tanggal Masuk</th>
                    <th scope="col" style="width: 16%">Tanggal Kedaluwarsa</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($refillStocks as $refillStock)
                    <tr>
                        <th scope="row">{{ $no++ }}</th>
                        <td scope="row">{{ 'Rp ' . number_format($refillStock->price, 0, ',', '.') }}</td>
                        <td scope="row">{{ $refillStock->quantity }}</td>
                        <td scope="row">{{ 'Rp ' . number_format($refillStock->total, 0, ',', '.') }}</td>
                        <td scope="row">{{ $refillStock->entry_date }}</td>
                        <td scope="row">{{ $refillStock->expired_date ? $refillStock->expired_date : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($pesan = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "success",
            });
        </script>
    @endif
@endsection
