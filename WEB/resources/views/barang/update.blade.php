@extends('layouts.app')
@section('title', 'Barang || Update')
@section('content')
    <h1>Edit Barang</h1>
    @if ($errors->any())
        <div class="alert alert-danger m-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($check == false)
        <div class="alert alert-danger m-4" role="alert">
            <ul>
                <li>Kategori Sudah Dihapus!</li>
            </ul>
        </div>
    @endif
    <div class="mt-3 p-4" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <form action="/barang/update" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <input type="hidden" name="id" value="{{ $product->product_id }}">
            <div class="mb-3">
                <label for="product_name" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="product_name" placeholder="Nama barang..."
                    value="{{ $product->product_name }}" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="pict" class="form-label">Foto Barang</label>
                <input type="file" class="form-control" name="pict" id="imageInput" autocomplete="off"
                    accept="image/*">
            </div>
            <img id="previewImage" src="{{ asset('images/' . $product->pict) }}" alt="Preview Gambar"
                style="display: block; max-width: 350px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" name="category_id">
                    <option selected disabled>Kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}"
                            {{ $product->category_id == $category->category_id && $check == true ? 'selected' : '' }}>
                            {{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning mt-3">Edit</button>
            <a href="/barang" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
        </form>
    </div>

    <script>
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('previewImage');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
