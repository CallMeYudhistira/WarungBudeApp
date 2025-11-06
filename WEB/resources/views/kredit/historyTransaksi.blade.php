@extends('layouts.app')
@section('title', 'Kredit || Transaksi || Riwayat')
@section('content')
    <h1>Riwayat Transaksi Kredit</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/kredit" class="btn btn-dark m-2">Kembali</a>
        <form action="/kredit/history/transaksi/export" method="get">
            <input type="hidden" name="first" @isset($first) value="{{ $first }}" @endisset />
            <input type="hidden" name="second" @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-success m-2" type="submit">Cetak</button>
        </form>
        <form class="d-flex m-2 ms-auto" action="/kredit/history/transaksi/filter" method="get">
            <input class="form-control me-2" type="date" name="first"
                @isset($first) value="{{ $first }}" @endisset />
            <label for="second" class="form-label m-2">=></label>
            <input class="form-control me-2" type="date" name="second"
                @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>
    <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="width: 10%;">#</th>
                    <th scope="col" style="width: 20%;">Tanggal Transaksi</th>
                    <th scope="col" style="width: 20%;">Nama Pelanggan</th>
                    <th scope="col" style="width: 20%;">Total</th>
                    <th scope="col" style="width: 20%;">Nama Kasir</th>
                    <th scope="col" style="width: 10%;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!$credits->isEmpty())
                    @foreach ($credits as $i => $credit)
                        <tr>
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>{{ \Carbon\Carbon::parse($credit->payment_date)->translatedFormat('l, d/F/Y') }}</td>
                            <td>{{ $credit->customer_name }}</td>
                            <td>{{ $credit->total }}</td>
                            <td>{{ $credit->name }}</td>
                            <td class="text-center"><a
                                    href="/transaksi/detail/{{ $credit->transaction_id }}/print"
                                    class="btn btn-success">Detail</a></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <div class="alert alert-primary p-3 text-center" role="alert"
                                style="width: 500px; margin: auto; margin-top: 2rem; margin-bottom: 2rem;">
                                ❌ Riwayat transaksi kredit kosong / tidak ditemukan. ❌
                            </div>
                        </td>
                    </tr>
                @endif
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
