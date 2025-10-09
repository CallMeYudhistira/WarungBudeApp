@extends('layouts.app')
@section('title', 'Barang || List')
@section('content')
    <h1 style="margin-left: -5px;">List Barang</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <button type="button" class="btn btn-primary m-1 mt-2 mb-2" data-bs-toggle="modal"
            data-bs-target="#tambahBarang">Tambah</button>
        @include('barang.modal.create')
        <a href="/barang/expired" class="btn btn-secondary m-1 mt-2 mb-2">Expired</a>
        <form class="d-flex ms-auto mt-2 mb-2" action="/barang/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </div>
    <div>
        <div class="row row-cols-1 row-cols-md-5 g-5">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100" style="width: 280px;">
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
                        </table>
                        <div class="card-body">
                            <div class="d-flex pb-2 mb-1" style="justify-content: space-between;">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editBarang{{ $product->product_id }}"
                                    style="width: 100%; margin-right: 0.5rem;">Edit</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusBarang{{ $product->product_id }}"
                                    style="width: 100%; margin-left: 0.5rem;">Hapus</button>
                            </div>
                            <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-success w-100">Detail
                                Barang</a>
                        </div>
                    </div>
                </div>
                @include('barang.modal.update')
                @include('barang.modal.delete')
            @endforeach
        </div>

        <div class="p-4" style="width:300px; margin: auto; margin-top: 2vh;">
            {{ $products->links() }}
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
