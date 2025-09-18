@extends('layouts.app')
@section('title', 'Pelanggan || Update')
@section('content')
<h1>Edit Pelanggan</h1>
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
    <form action="/kredit/update" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" name="customer_name" placeholder="Nama pelanggan..." autocomplete="off" value="{{ $customer->customer_name }}">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Nomor Telepon</label>
            <input type="number" class="form-control" name="phone_number" placeholder="Nomor telepon..." autocomplete="off" value="{{ $customer->phone_number }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <input type="text" class="form-control" name="address" placeholder="Alamat..." autocomplete="off" value="{{ $customer->address }}">
        </div>
        <button type="submit" class="btn btn-warning mt-3">Edit</button>
        <a href="/kredit" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
    </form>
</div>
@endsection