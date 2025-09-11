@extends('layouts.app')
@section('title', 'Satuan || Update')
@section('content')
<h1>Edit Satuan</h1>
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
    <form action="/satuan/update" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $unit->unit_id }}">
        <div class="mb-3">
            <label for="unit_name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" name="unit_name" placeholder="Nama kategori..." autocomplete="off" value="{{ $unit->unit_name }}">
        </div>
        <button type="submit" class="btn btn-warning mt-3">Edit</button>
        <a href="/satuan" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
    </form>
</div>
@endsection