@extends('layouts.app')
@section('title', 'Barang || Stok || Riwayat')
@section('content')
    <h1 style="margin-left: -5px;">Riwayat Isi Stok</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/barang" class="btn btn-dark m-1 mt-2 mb-2">Kembali</a>
        <form action="/barang/refillStock/history/export" method="get">
            <input type="hidden" name="first" @isset($first) value="{{ $first }}" @endisset />
            <input type="hidden" name="second" @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-success m-2" type="submit">Cetak</button>
        </form>
        <form class="d-flex m-1 mt-2 mb-2 ms-auto" action="/barang/refillStock/filter" method="get">
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
                    <th scope="col" style="width: 14%">Nama Barang</th>
                    <th scope="col" style="width: 16%">Harga Beli</th>
                    <th scope="col" style="width: 12%">Kuantitas</th>
                    <th scope="col" style="width: 16%">Total</th>
                    <th scope="col" style="width: 16%">Tanggal Masuk</th>
                    <th scope="col" style="width: 16%">Tanggal Kedaluwarsa</th>
                    <th scope="col" style="width: 5%">Status</th>
                </tr>
            </thead>
            <tbody>
                @if (!$refillStocks->isEmpty())
                @foreach ($refillStocks as $i => $refillStock)
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td>{{ $refillStock->product_name }}</td>
                        <td>{{ 'Rp ' . number_format($refillStock->price, 0, ',', '.') }}</td>
                        <td>{{ $refillStock->quantity }} {{ $refillStock->unit_name }}</td>
                        <td>{{ 'Rp ' . number_format($refillStock->total, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($refillStock->entry_date)->translatedFormat('l, d/F/Y') }}</td>
                        <td>{{ $refillStock->expired_date ? \Carbon\Carbon::parse($refillStock->expired_date)->translatedFormat('l, d/F/Y') : '-' }}</td>
                        <td>{{ $refillStock->status }}</td>
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
