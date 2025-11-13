@extends('layouts.app')
@section('title', 'Beranda || Gudang')
@section('content')
@section('script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js"
        integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

<div class="row row-cols-1 row-cols-md-4 g-4" style="align-items: center; justify-content: space-between;">
    <a class="card text-center m-2" style="width: 450px; height: auto; padding: 2rem; text-decoration: none;"
        href="/barang">
        <h3>⚠️ Barang Stok Rendah</h3>
        <div style="font-size: 30px;">{{ $stok }}</div>
    </a>
    <a class="card text-center m-2" style="width: 450px; height: auto; padding: 2rem; text-decoration: none;"
        href="/barang/expired">
        <h3>🗓️ Barang Kedaluwarsa</h3>
        <div style="font-size: 30px;">{{ $expired }}</div>
    </a>
    <a class="card text-center m-2" style="width: 450px; height: auto; padding: 2rem; text-decoration: none;"
        href="/barang/refillStock/history">
        <h3>➕ Barang Masuk Bulan Ini</h3>
        <div style="font-size: 30px;">{{ $barang_masuk }}</div>
    </a>
</div>

<hr class="m-4">
<div class="card border-0">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Barang dengan Stok Rendah</h5>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $i => $product)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->category_name }}</td>
                            <td>{{ $product->unit_name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-center">
                                <a href="/barang/refillStock/{{ $product->id }}" class="btn btn-success">Cek</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($message = Session::get('error'))
    <script>
        Swal.fire({
            title: "{{ $message }}",
            icon: "error",
            draggable: false
        });
    </script>
@endif
@endsection
