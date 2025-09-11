@extends('layouts.app')
@section('title', 'Kategori || Update')
@section('content')
<h1>Edit Kategori</h1>
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
    <form action="/kategori/update" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $category->category_id }}">
        <div class="mb-3">
            <label for="category_name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" name="category_name" placeholder="Nama kategori..." autocomplete="off" value="{{ $category->category_name }}">
        </div>
        <button type="submit" class="btn btn-warning mt-3">Edit</button>
        <a href="/kategori" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
    </form>
</div>
@endsection