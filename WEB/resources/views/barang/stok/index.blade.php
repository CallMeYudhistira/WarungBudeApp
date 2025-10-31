@extends('layouts.app')
@section('title', 'Barang || Stok || Riwayat')
@section('content')
    <h1 style="padding-bottom: 12px; margin-left: -5px;">Isi Stok Barang : {{ $product->product_name }}</h1>
    <h2 style="padding-top: 12px; border-top: 1px solid #ccc;">Satuan : {{ $product->unit_name }}</h2>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <button type="button" class="btn btn-primary m-1 mt-2 mb-2" data-bs-toggle="modal" data-bs-target="#isiStokBarang">Tambah</button>
        @include('barang.stok.modal.create')
        <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-dark m-1 mt-2 mb-2">Kembali</a>
        <form action="/barang/refillStock/{{ $product->product_detail_id }}/export" method="get">
            <input type="hidden" name="first" @isset($first) value="{{ $first }}" @endisset />
            <input type="hidden" name="second" @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-success m-1 mt-2 mb-2 pb-2" type="submit">Cetak</button>
        </form>

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
                    <th scope="col" style="width: 4%">#</th>
                    <th scope="col" style="width: 19%">Harga Beli</th>
                    <th scope="col" style="width: 13%">Kuantitas</th>
                    <th scope="col" style="width: 13%">Total</th>
                    <th scope="col" style="width: 18%">Tanggal Masuk</th>
                    <th scope="col" style="width: 18%">Tanggal Kedaluwarsa</th>
                    <th scope="col" style="width: 10%">Stok Terbaru</th>
                    <th scope="col" style="width: 5%">Status</th>
                </tr>
            </thead>
            <tbody>
                @if (!$refillStocks->isEmpty())
                @foreach ($refillStocks as $i => $refillStock)
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td scope="row">{{ 'Rp ' . number_format($refillStock->price, 0, ',', '.') }}</td>
                        <td scope="row">{{ $refillStock->quantity }}</td>
                        <td scope="row">{{ 'Rp ' . number_format($refillStock->total, 0, ',', '.') }}</td>
                        <td scope="row">{{ \Carbon\Carbon::parse($refillStock->entry_date)->translatedFormat('d/F/Y') }}</td>
                        <td scope="row">{{ $refillStock->expired_date ? \Carbon\Carbon::parse($refillStock->expired_date)->translatedFormat('d/F/Y') : '-' }}</td>
                        <td scope="row">{{ $refillStock->updated_stock }}</td>
                        <td scope="row">{{ $refillStock->status == 'pending' ? 'menunggu' : $refillStock->status }}</td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-primary p-3 text-center" role="alert"
                                style="width: 500px; margin: auto; margin-top: 2rem; margin-bottom: 2rem;">
                                ❌ Riwayat isi stok kosong / tidak ditemukan. ❌
                            </div>
                        </td>
                    </tr>
                @endif
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
