@extends('layouts.app')
@section('title', 'Barang || Tambah Stok')
@section('content')
    <h1>Tambah Stok Barang : {{ $product->product_name }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger m-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mt-3 p-4" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <form action="/barang/refillStock/store" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <input type="hidden" name="detail_id" value="{{ $product->product_detail_id }}">
            <div class="mb-3">
                <label for="unit_id" class="form-label">Satuan</label>
                <select class="form-select" disabled>
                    <option selected>{{ $product->unit_name }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" class="form-control" name="total" value="0" id="total"
                    autocomplete="off" min="0">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Kuantitas</label>
                <input type="number" class="form-control" name="quantity" value="0" id="qty"
                    autocomplete="off" min="0">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" name="price" value="0" id="price" readonly
                    autocomplete="off" min="0">
            </div>
            <div class="mb-3">
                <label for="expired_date" class="form-label">Tanggal Kedaluwarsa</label>
                <input type="date" class="form-control" name="expired_date" placeholder="Tanggal kedaluwarsa..."
                    autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Tambah</button>
            <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
        </form>
    </div>

    <script>
        const qty = document.getElementById('qty');
        const price = document.getElementById('price');
        const total = document.getElementById('total');

        qty.addEventListener('input', e => {
            price.value = Math.ceil(total.value / e.target.value);
        });

        total.addEventListener('input', e => {
            price.value = Math.ceil(e.target.value / qty.value);
        });
    </script>
@endsection
