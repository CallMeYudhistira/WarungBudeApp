@extends('layouts.app')
@section('title', 'Users || List')
@section('content')
    <h1>List User</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/users/create" class="btn btn-primary m-2">Tambah</a></li>
        <form class="d-flex m-2 ms-auto" action="/users/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword" @isset($keyword) value="{{ $keyword }}" @endisset/>
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </ul>
    <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col">Username</th>
                    <th scope="col">Hak</th>
                    <th scope="col" colspan="2" class="text-center" style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 0;
                @endphp
                @foreach ($users as $user)
                    <tr @if($user->name == 'guest') style="display: none;" @endif>
                        <th scope="row">{{ $no++ }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="/users/edit/{{ $user->user_id }}" class="btn btn-warning">Edit</a>
                        </td>
                        <td>
                            <form action="/users/delete/{{ $user->user_id }}" method="post">@csrf
                                @method('delete')<button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah anda ingin menghapus {{ $user->name }}?');">Hapus</button>
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
