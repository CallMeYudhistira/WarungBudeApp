@extends('layouts.app')
@section('title', 'Barang || Kedaluwarsa || List')
@section('content')
    <h1 style="margin-left: -5px;">Cek Barang</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/barang/expired/history" class="btn btn-primary m-1 mt-2 mb-2">Riwayat Kedaluwarsa</a>
        <a href="/barang" class="btn btn-dark m-1 mt-2 mb-2">Kembali</a>
    </div>
    <div>
        <h2 class="text-center mb-3">Barang Kedaluwarsa</h2>
        @if (isset($expired) || !$expired_products->isEmpty())
        <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
            <form class="d-flex ms-auto mt-2 mb-2" action="/barang/expired/search" method="get">
                <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="expired"
                    @isset($expired) value="{{ $expired }}" @endisset />
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
            <div style="align-content: center; align-items: center; justify-content: space-between; margin-top: 2rem;">
                <div class="row row-cols-1 row-cols-md-3 g-5">
                    @foreach ($expired_products as $product)
                        <div class="col">
                            <div class="card h-100" style="width: 450px;">
                                <img src="{{ asset('images/' . $product->pict) }}" class="card-img-top" alt="foto barang">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->product_name }}</h5>
                                </div>
                                <table class="table p-2">
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Kategori</th>
                                        <td>:</td>
                                        <td>{{ $product->category_name }}</td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Jumlah Barang</th>
                                        <td>:</td>
                                        <td>{{ $product->quantity }} {{ $product->unit_name }}</td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Tanggal Masuk</th>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($product->entry_date)->translatedFormat('l, d/F/Y') }}
                                        </td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Tanggal Kedaluwarsa</th>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($product->expired_date)->translatedFormat('l, d/F/Y') }}
                                        </td>
                                    </tr>
                                </table>
                                <div class="card-body">
                                    <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal"
                                        data-bs-target="#hapusStok{{ $product->product_detail_id }}">Buang Stok</button>
                                </div>
                            </div>
                        </div>
                        @include('barang.expired.modal.delete')
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-primary p-3 text-center" role="alert" style="width: 350px; margin: auto; margin-top: 2rem;">
                ✅ Tidak Ada Barang Kedaluwarsa. ✅
            </div>
        @endif
        @if ($expired_products->isEmpty() && Request::has('expired'))
            <div class="alert alert-primary p-3 text-center" role="alert"
                style="width: 500px; margin: auto; margin-top: 2rem;">
                ❌ Barang expired tidak ditemukan. ❌
            </div>
        @endif

        <div class="p-4" style="width:300px; margin: auto; margin-top: 1vh;">
            {{ $expired_products->links() }}
        </div>
    </div>

    <hr class="m-4">

    <div>
        <h2 class="text-center mb-3">Barang Aman</h2>
        @if (isset($normal) || !$normal_products->isEmpty())
        <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
            <form class="d-flex ms-auto mt-2 mb-2" action="/barang/expired/search" method="get">
                <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="normal"
                    @isset($normal) value="{{ $normal }}" @endisset />
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
            <div style="align-content: center; align-items: center; justify-content: space-between; margin-top: 2rem;">
                <div class="row row-cols-1 row-cols-md-3 g-5">
                    @foreach ($normal_products as $product)
                        <div class="col">
                            <div class="card h-100" style="width: 450px;">
                                <img src="{{ asset('images/' . $product->pict) }}" class="card-img-top" alt="foto barang">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->product_name }}</h5>
                                </div>
                                <table class="table p-2">
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Kategori</th>
                                        <td>:</td>
                                        <td>{{ $product->category_name }}</td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Jumlah Barang</th>
                                        <td>:</td>
                                        <td>{{ $product->quantity }} {{ $product->unit_name }}</td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Tanggal Masuk</th>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($product->entry_date)->translatedFormat('l, d/F/Y') }}
                                        </td>
                                    </tr>
                                    <tr style="border-top: 0.5px solid #ccc;">
                                        <th>Tanggal Kedaluwarsa</th>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($product->expired_date)->translatedFormat('l, d/F/Y') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-primary p-3 text-center" role="alert" style="width: 350px; margin: auto; margin-top: 2rem;">
                ❌ Tidak Ada Barang Aman. ❌
            </div>
        @endif
        @if ($normal_products->isEmpty() && Request::has('normal'))
            <div class="alert alert-primary p-3 text-center" role="alert"
                style="width: 500px; margin: auto; margin-top: 2rem;">
                ❌ Barang aman tidak ditemukan. ❌
            </div>
        @endif

        <div class="p-4" style="width:300px; margin: auto; margin-top: 1vh;">
            {{ $normal_products->links() }}
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
