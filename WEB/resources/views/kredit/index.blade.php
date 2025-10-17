@extends('layouts.app')
@section('title', 'Kredit || List')
@section('content')
    <h1>List Kredit</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/kredit/history" class="btn btn-primary m-2">Riwayat Kredit</a>
        <form class="d-flex m-2 ms-auto" action="/kredit/search" method="get">
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
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Total Hutang</th>
                    <th scope="col">Status</th>
                    <th scope="col" colspan="2" class="text-center" style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!$customers->isEmpty())
                    @foreach ($customers as $i => $customer)
                        <tr>
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->phone_number }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->amount_of_debt }}</td>
                            <td style="text-transform: capitalize;">{{ $customer->status }}</td>
                            @if ($customer->status != 'lunas')
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editPelanggan{{ $customer->customer_id }}">Edit</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#bayarKredit{{ $customer->customer_id }}">Bayar</button>
                            </td>
                            @include('kredit.modal.pay')
                            @else
                            <td colspan="2" class="text-center">
                                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#editPelanggan{{ $customer->customer_id }}">Edit</button>
                            </td>
                            @endif
                        </tr>
                        @include('kredit.modal.update')
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-primary p-3 text-center" role="alert"
                                style="width: 350px; margin: auto; margin-top: 2rem; margin-bottom: 2rem;">
                                ❌ Data pelanggan tidak ditemukan. ❌
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="p-4" style="width:300px; margin: auto; margin-top: 2vh; margin-bottom: 2vh;">
        {{ $customers->links() }}
    </div>

    @if ($pesan = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "error",
            });
        </script>
    @endif

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
