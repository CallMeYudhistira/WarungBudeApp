@extends('layouts.app')
@section('title', 'Users || Create')
@section('content')
<h1>Tambah User</h1>
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
    <form action="/users/store" method="post">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama User</label>
            <input type="text" class="form-control" name="name" placeholder="Nama user..." autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Nomor Telepon</label>
            <input type="number" class="form-control" name="phone_number" placeholder="Nomor telepon..." autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username..." autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password..." autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Hak</label>
            <select class="form-select" name="role">
                <option selected disabled>Hak...</option>
                <option value="admin">Admin</option>
                <option value="kasir">Kasir</option>
                <option value="gudang">Gudang</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Tambah</button>
        <a href="/users" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
    </form>
</div>
@endsection