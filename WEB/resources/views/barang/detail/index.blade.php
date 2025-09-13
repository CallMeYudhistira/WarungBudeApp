@extends('layouts.app')
@section('title', 'Barang || Detail')
@section('content')
    <h1>Detail Barang : {{ $product->product_name }}</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/barang/detail/create/{{ $product->product_id }}" class="btn btn-primary m-2">Tambah</a></li>
        <li><a href="/barang/" class="btn btn-dark m-2">Kembali</a></li>
    </ul>
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
                                <td>{{ $product_detail->purchase_price }}/{{ $product_detail->unit_name }}</td>
                            </tr>
                            <tr>
                                <th>Harga Jual</th>
                                <td>:</td>
                                <td>{{ $product_detail->selling_price }}/{{ $product_detail->unit_name }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>:</td>
                                <td>{{ $product_detail->stock }}</td>
                            </tr>
                        </table>
                        <div class="card-body">
                            <div class="d-flex pb-2 mb-2" style="justify-content: space-between;">
                                <a href="/barang/detail/edit/{{ $product_detail->product_detail_id }}" class="btn btn-warning" style="width: 120px;">Edit</a>
                                <form action="/barang/detail/delete/{{ $product_detail->product_detail_id }}" method="post">@csrf
                                    @method('delete') <input type="hidden" name="id" value="{{ $product->product_id }}"> <button type="submit" class="btn btn-danger" style="width: 120px;"
                                        onclick="return confirm('Apakah anda ingin menghapus {{ $product_detail->product_name }} (satuan = {{ $product_detail->unit_name }}) dengan semua riwayat isi stok nya?');">Hapus</button>
                                </form>
                            </div>
                            <a href="/barang/refillStock/{{ $product_detail->product_detail_id }}" class="btn btn-secondary w-100">isi Stok</a>
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
