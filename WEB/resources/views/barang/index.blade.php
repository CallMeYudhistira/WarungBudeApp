@extends('layouts.app')
@section('title', 'Barang || List')
@section('content')
    <h1>List Barang</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/barang/create" class="btn btn-primary m-2">Tambah</a></li>
        <form class="d-flex m-2 ms-auto" action="/barang/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </ul>
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100">
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
                            <div class="d-flex pb-2 mb-2" style="justify-content: space-between;">
                            <a href="/barang/edit/{{ $product->product_id }}" class="btn btn-warning" style="width: 120px;">Edit</a>
                            <form action="/barang/delete/{{ $product->product_id }}" method="post">@csrf
                                @method('delete')<button type="submit" class="btn btn-danger" style="width: 120px;"
                                    onclick="return confirm('Apakah anda ingin menghapus {{ $product->product_name }}?');">Hapus</button>
                            </form>
                            </div>
                            <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-success w-100">Detail Barang</a>
                            <a href="/barang/refillStock/{{ $product->product_id }}" class="btn btn-secondary w-100 mt-3">isi Stok</a>
                        </div>
                    </div>
                </div>
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
@endsection
