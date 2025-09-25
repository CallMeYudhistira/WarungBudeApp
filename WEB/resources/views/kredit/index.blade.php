@extends('layouts.app')
@section('title', 'Kredit || List')
@section('content')
    <h1>List Kredit</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/kredit/riwayat" class="btn btn-primary m-2">Riwayat Kredit</a></li>
        <form class="d-flex m-2 ms-auto" action="/kredit/search" method="get">
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
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Total Hutang</th>
                    <th scope="col">Status</th>
                    <th scope="col" colspan="2" class="text-center" style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $i => $customer)
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->amount_of_debt }}</td>
                    <td style="text-transform: capitalize;">{{ $customer->status }}</td>
                    <td>
                        <a href="/kredit/edit/{{ $customer->customer_id }}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        <a href="/kredit/payment/{{ $customer->customer_id }}" class="btn btn-success">Bayar</a>
                    </td>
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