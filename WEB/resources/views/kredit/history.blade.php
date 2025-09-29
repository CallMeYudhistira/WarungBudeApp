@extends('layouts.app')
@section('title', 'Kredit || Riwayat')
@section('content')
    <h1>Riwayat Pembayaran Kredit</h1>
    <div class="d-flex" style="margin: -0.3rem; margin-top: 1rem; margin-bottom: 1rem;">
        <a href="/kredit" class="btn btn-dark m-2">Kembali</a>
        <form class="d-flex m-2 ms-auto" action="/kredit/history/filter" method="get">
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
                    <th scope="col" style="width: 5%;">#</th>
                    <th scope="col" style="width: 10%;">Nama</th>
                    <th scope="col" style="width: 20%;">Total Hutang</th>
                    <th scope="col" style="width: 18%;">Bayar</th>
                    <th scope="col" style="width: 15%;">Sisa</th>
                    <th scope="col" style="width: 15%;">Kembali</th>
                    <th scope="col" style="width: 17%;">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $i => $customer)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->total }}</td>
                    <td>{{ $customer->amount_of_paid }}</td>
                    <td>{{ $customer->remaining_debt }}</td>
                    <td>{{ $customer->change }}</td>
                    <td>{{ $customer->payment_date }}</td>
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