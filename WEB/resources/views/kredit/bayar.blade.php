@extends('layouts.app')
@section('title', 'Pembayaran || Kredit')
@section('content')
    <h1>Pembayaran Hutang</h1>
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
        <form action="/kredit/bayar/store/{{ $customer->customer_id }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control" autocomplete="off" value="{{ $customer->customer_name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="amount_of_debt" class="form-label">Total Hutang</label>
                <input type="number" class="form-control" name="amount_of_debt" autocomplete="off"
                    value="{{ $customer->amount_of_debt }}" readonly id="total">
            </div>
            <div class="mb-3">
                <label for="amount_of_paid" class="form-label">Hutang Yang Dibayar</label>
                <input type="number" class="form-control" name="amount_of_paid" placeholder="Hutang yang dibayar..."
                    autocomplete="off" id="bayar" oninput="sisaHutang(this.value)" min="0">
            </div>
            <div class="mb-3">
                <label for="remaining_debt" class="form-label">Sisa Hutang</label>
                <input type="number" class="form-control" name="remaining_debt" autocomplete="off" id="sisa" readonly
                    value="{{ $customer->amount_of_debt }}" min="0">
            </div>
            <div class="mb-3">
                <label class="form-label" for="change">Kembalian</label>
                <input type="number" class="form-control" autocomplete="off" id="kembali" name="change" readonly
                    value="0" min="0">
            </div>
            <button type="submit" class="btn btn-warning mt-3">Bayar</button>
            <a href="/kredit" class="btn btn-dark mt-3" style="margin-left: 8px;">Kembali</a>
        </form>
    </div>

    <script>
        function sisaHutang(bayar) {
            const total = document.getElementById('total').value;
            const sisa = document.getElementById('sisa');
            const kembali = document.getElementById('kembali');

            kembali.value = (bayar - total);
            if (kembali.value < 0) {
                kembali.value = 0;
            }

            sisa.value = (total - bayar);
            if (sisa.value < 0) {
                sisa.value = 0;
            }
        }
    </script>

    @if ($errors->any())
        <script>
            let errorMessages = `
            @foreach ($errors->all() as $error)
                <p style="text-align:center; margin:0;"> {{ $error }} </p>
            @endforeach
        `;

            Swal.fire({
                title: "Validation Error",
                html: errorMessages,
                icon: "error",
                confirmButtonText: "OK",
                confirmButtonColor: "#1f4ed8",
                background: "#fff",
                color: "#333"
            });
        </script>
    @endif

    @if ($pesan = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "error",
            });
        </script>
    @endif
@endsection
