@extends('layouts.app')
@section('title', 'Satuan || List')
@section('content')
    <h1>List Satuan</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/satuan/create" class="btn btn-primary m-2">Tambah</a></li>
        <form class="d-flex m-2 ms-auto" action="/satuan/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword" @isset($keyword) value="{{ $keyword }}" @endisset/>
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </ul>
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
                @php
                    $no = 1;
                @endphp
                @foreach ($units as $unit)
                    <tr>
                        <th scope="row">{{ $no++ }}</th>
                        <td>{{ $unit->unit_name }}</td>
                        <td>
                            <a href="/satuan/edit/{{ $unit->unit_id }}" class="btn btn-warning">Edit</a>
                        </td>
                        <td>
                            <form action="/satuan/delete/{{ $unit->unit_id }}" method="post">@csrf
                                @method('delete')<button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah anda ingin menghapus {{ $unit->unit_name }}?');">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($pesan = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "success",
            });
        </script>
    @endif
@endsection