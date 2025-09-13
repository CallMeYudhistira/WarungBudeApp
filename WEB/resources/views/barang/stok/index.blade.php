@extends('layouts.app')
@section('title', 'Barang || Riwayat Isi Stok')
@section('content')
    <h1 style="padding: 12px;">Riwayat Isi Stok</h1>
    <h2 style="padding-top: 12px; border-top: 1px solid #ccc;">{{ $product->product_name }} ({{ $product->unit_name }}) || History Isi Stok</h2>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/barang/refillStock/create/{{ $product->product_detail_id }}" class="btn btn-primary m-2">Tambah</a></li>
        <li><a href="/barang/detail/{{ $product->product_id }}" class="btn btn-dark m-2">Kembali</a></li>
        <form class="d-flex m-2 ms-auto" action="/barang/refillStock/{{ $product->product_detail_id }}/filter" method="get">
            <input class="form-control me-2" type="date" placeholder="Dari tanggal..." name="first"
                @isset($first) value="{{ $first }}" @endisset />
            <label for="second" class="form-label m-2">=></label>
            <input class="form-control me-2" type="date" placeholder="Sampai tanggal..." name="second"
                @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </ul>
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
                        <td scope="row">{{ $refillStock->price }}</td>
                        <td scope="row">{{ $refillStock->quantity }}</td>
                        <td scope="row">{{ $refillStock->total }}</td>
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
