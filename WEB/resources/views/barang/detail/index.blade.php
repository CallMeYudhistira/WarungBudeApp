@extends('layouts.app')
@section('title', 'Barang || Detail')
@section('content')
    <h1>Detail Barang : {{ $product->product_name }}</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#tambahDetailBarang">Tambah</button>
        @include('barang.detail.modal.create')

        <a href="/barang/" class="btn btn-dark m-2">Kembali</a>
    </div>
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($product_details as $product_detail)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('images/' . $product_detail->pict) }}" class="card-img-top" alt="foto barang">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product_detail->product_name }}</h5>
                        </div>
                        <table class="table p-2">
                            <tr style="border-top: 0.5px solid #ccc;">
                                <th>Kategori</th>
                                <td>:</td>
                                <td>{{ $product_detail->category_name }}</td>
                            </tr>
                            <tr>
                                <th>Harga Awal</th>
                                <td>:</td>
                                <td>{{ 'Rp ' . number_format($product_detail->purchase_price, 0, ',', '.') }}/{{ $product_detail->unit_name }}</td>
                            </tr>
                            <tr>
                                <th>Harga Jual</th>
                                <td>:</td>
                                <td>{{ 'Rp ' . number_format($product_detail->selling_price, 0, ',', '.') }}/{{ $product_detail->unit_name }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>:</td>
                                <td>{{ $product_detail->stock }}</td>
                            </tr>
                        </table>
                        <div class="card-body">
                            <div class="d-flex pb-2 mb-2" style="justify-content: space-between;">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editDetailBarang{{ $product_detail->product_detail_id }}" style="width: 100%; margin-right: 0.5rem;">Edit</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusDetailBarang{{ $product_detail->product_detail_id }}" style="width: 100%; margin-left: 0.5rem;">Hapus</button>
                            </div>
                            <a href="/barang/refillStock/{{ $product_detail->product_detail_id }}" class="btn btn-secondary w-100">isi Stok</a>
                        </div>
                    </div>
                </div>
                @include('barang.detail.modal.update')
                @include('barang.detail.modal.delete')
            @endforeach
        </div>
    </div>

    @if ($pesan = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "success",
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error Validation ⚠️</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
                myModal.show();
            });
        </script>
    @endif
@endsection