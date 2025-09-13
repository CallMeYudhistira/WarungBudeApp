@extends('layouts.app')
@section('title', 'Barang || Tambah Detail')
@section('content')
    <h1>Tambah Detail Barang</h1>
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
            <input type="hidden" name="id" value="{{ $product->product_id }}">
            <div class="mb-3">
                <label for="product_name" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" value="{{ $product->product_name }}" disabled>
            </div>
            <div class="mb-3">
                <label for="pict" class="form-label">Foto Barang</label>
            </div>
            <img id="previewImage" src="{{ asset('images/' . $product->pict) }}" alt="Preview Gambar"
                style="display: block; max-width: 350px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" name="category_id" disabled>
                    <option selected disabled>{{ $product->category_name }}</option>
                </select>
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
            <button type="submit" class="btn btn-primary mt-3">Tambah</button>
            <a href="/barang/detail/{{ $product->product_id }}" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
        </form>
    </div>

    @if ($pesan = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "error",
            });
        </script>
    @endif
@endsection
