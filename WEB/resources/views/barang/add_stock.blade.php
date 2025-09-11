@extends('layouts.app')
@section('title', 'Barang || Tambah Stok')
@section('content')
    <h1>Tambah Stok Barang</h1>
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
        <form action="/barang/detail/store" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <div class="mb-3">
                <label for="purchase_price" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" name="purchase_price" placeholder="Harga beli..."
                    autocomplete="off" min="0">
            </div>
            <div class="mb-3">
                <label for="unit_id" class="form-label">Satuan</label>
                <select class="form-select" name="unit_id">
                    <option selected disabled>Satuan...</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity_of_unit" class="form-label">Kuantitas dari Satuan</label>
                <input type="number" class="form-control" name="quantity_of_unit" placeholder="kuantitas dari satuan..."
                    autocomplete="off" min="0">
            </div>
            <div class="mb-3">
                <label for="amount_per_unit" class="form-label">Jumlah Per Satuan</label>
                <input type="number" class="form-control" name="amount_per_unit" placeholder="Jumlah per satuan..."
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
@endsection
