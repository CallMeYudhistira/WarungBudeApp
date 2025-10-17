@extends('layouts.app')
@section('title', 'Kategori || List')
@section('content')
    <h1>List Kategori</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal"
            data-bs-target="#tambahKategori">Tambah</button>
        @include('kategori.modal.create')

        <form class="d-flex m-2 ms-auto" action="/kategori/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </div>
    <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="width: 10%;">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col" colspan="2" class="text-center" style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!$categories->isEmpty())
                    @foreach ($categories as $i => $category)
                        <tr>
                            <th scope="row">{{ $categories->firstItem() + $i }}</th>
                            <td>{{ $category->category_name }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editKategori{{ $category->category_id }}">Edit</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusKategori{{ $category->category_id }}">Hapus</button>
                            </td>
                        </tr>
                        @include('kategori.modal.update')
                        @include('kategori.modal.delete')
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-primary p-3 text-center" role="alert"
                                style="width: 500px; margin: auto; margin-top: 2rem; margin-bottom: 2rem;">
                                ❌ Kategori kosong / tidak ditemukan. ❌
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="p-4" style="width:300px; margin: auto; margin-top: 2vh;">
        {{ $categories->links() }}
    </div>

    @if ($pesan = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "success",
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error Validation ⚠️</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
                myModal.show();
            });
        </script>
    @endif
@endsection
