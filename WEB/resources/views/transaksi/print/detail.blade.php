<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        @font-face {
            font-family: 'Nata Sans';
            src: url("{{ asset('fonts/NataSans_Regular.ttf') }}");
        }

        body {
            font-family: 'Nata Sans', sans-serif;
            margin: auto;
            font-size: 12px;
            color: #333;
            width: 1000px;
        }

        h1,
        h3 {
            text-align: center;
            margin: 10px 0;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 16px;
        }

        .row {
            display: flex;
            margin: 6px 0;
        }

        .col-2 {
            width: 16%;
        }

        .col-6 {
            width: 50%;
        }

        .col-8 {
            width: 66%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #f9f9f9;
        }

        td,
        th {
            word-wrap: break-word;
            max-width: 120px;
        }


        img {
            width: 80px;
            border-radius: 8px;
        }

        .bold {
            font-weight: bold;
        }

        .info td,
        .info th {
            border: none;
            text-align: left;
            width: 25%;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Detail Transaksi</h1>

        <!-- Info Transaksi -->
        <table style="width:100%; margin-bottom: 20px;" class="info">
            <tr>
                <td class="bold">ID Transaksi</td>
                <td>: {{ $transaction->transaction_id }}</td>
                <td class="bold">Nama Kasir</td>
                <td>: {{ $transaction->name }}</td>
            </tr>
            <tr>
                <td class="bold">Tanggal Transaksi</td>
                <td>: {{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('l, d F Y') }}</td>
                <td class="bold">Nama Pelanggan</td>
                <td>: {{ $transaction->customer_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="bold">Metode Pembayaran</td>
                <td>: {{ $transaction->payment }}</td>
            </tr>
        </table>


        <!-- Produk -->
        <table>
            <thead>
                <tr>
                    <th colspan="6">
                        <h3>Produk Yang Dibeli</h3>
                    </th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga (Rp.)</th>
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction_details as $i => $transaction_detail)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $transaction_detail->product_name }}</td>
                        <td>{{ $transaction_detail->category_name }}</td>
                        <td>Rp. {{ number_format($transaction_detail->selling_price, 0, ',', '.') }}/{{ $transaction_detail->unit_name }}
                        </td>
                        <td>{{ $transaction_detail->quantity }} {{ $transaction_detail->unit_name }}</td>
                        <td>Rp. {{ number_format($transaction_detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <table style="width:100%; margin-bottom: 20px;" class="info">
            <tr>
                <td style="width: 50%"></td>
                <td class="bold">Total Belanja</td>
                <td>: Rp. {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td class="bold">Bayar</td>
                <td>: Rp. {{ number_format($transaction->pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 50%"></td>
                <td class="bold">Kembalian</td>
                <td>: Rp. {{ number_format($transaction->change, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
