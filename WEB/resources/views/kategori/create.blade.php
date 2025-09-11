@extends('layouts.app')
@section('title', 'Kategori || Create')
@section('content')
<h1>Tambah Kategori</h1>
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
    <form action="/kategori/store" method="post">
        @csrf
        <div class="mb-3">
            <label for="category_name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" name="category_name" placeholder="Nama kategori..." autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Tambah</button>
        <a href="/kategori" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
    </form>
</div>
@endsection