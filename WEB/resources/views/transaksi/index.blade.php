@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
    <h1>List Barang</h1>
    <ul class="m-4 d-flex" style="list-style-type: none;">
        <li><a href="/transaksi/history" class="btn btn-primary m-2">Riwayat Transaksi</a></li>
        <form class="d-flex m-2 ms-auto" action="/transaksi/search" method="get">
            <input class="form-control me-2" type="text" placeholder="Search...🔎" autocomplete="off" name="keyword"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
    </ul>
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($products as $product)
                <form action="transaksi/cart/store" method="post">
                    @csrf
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset('images/' . $product->pict) }}" class="card-img-top" alt="foto barang">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->product_name }}</h5>
                            </div>
                            <table class="table p-2">
                                <tr style="border-top: 0.5px solid #ccc;">
                                    <th>Kategori</th>
                                    <td>:</td>
                                    <td>{{ $product->category_name }}</td>
                                </tr>
                                <tr>
                                    <th>Harga (Rp.)</th>
                                    <td>:</td>
                                    <td>{{ $product->selling_price }}/{{ $product->unit_name }}</td>
                                </tr>
                                <tr>
                                    <th>Kuantitas</th>
                                    <td>:</td>
                                    <td><input type="number" name="quantity" min="1" max="{{ $product->stock }}"
                                            value="1" class="form-control"></td>
                                </tr>
                            </table>
                            <div class="card-body">
                                <input type="hidden" name="selling_price" class="form-control m-2"
                                    value="{{ $product->selling_price }}">
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <button class="btn btn-success w-100" type="submit">Beli</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
        {{-- pagination links --}}
        <div class="p-4" style="width:300px; margin: auto; margin-top: 4vh;">
            {{ $products->links() }}
        </div>

        @if (isset($carts) || $carts != null)
            <div class="mt-3" style="border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="9">
                                <h2 class="text-center">Cart List</h2>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Foto Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Harga (Rp.)</th>
                            <th scope="col">Kuantitas</th>
                            <th scope="col">Subtotal (Rp.)</th>
                            <th scope="col">Nama Kasir</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    @php
                        $i = 1;
                    @endphp
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><img src="{{ asset('images/' . $cart->pict) }}" alt="foto barang"
                                        style="width: 90px; border-radius: 8px;"></td>
                                <td>{{ $cart->product_name }}</td>
                                <td>{{ $cart->category_name }}</td>
                                <td>{{ $cart->selling_price }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ $cart->subtotal }}</td>
                                <td>{{ $cart->name }}</td>
                                <td class="text-center">
                                    <form action="/transaksi/cart/delete/{{ $cart->cart_id }}" method="post">@csrf
                                        @method('delete')<button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @php
            $total = 0;

            foreach ($carts as $cart) {
                $total += $cart->subtotal;
            }
        @endphp

        <div class="d-flex">
            <div style="border: 1px solid #ccc; border-radius: 8px; padding: 12px; width: 300px;" class="ms-auto mt-3">
                <form action="transaksi/proses" method="post">
                    @csrf
                    <label for="total" class="mb-2">Total : (Rp.)</label>
                    <input type="number" min="0" name="total" value="{{ $total }}"
                        class="form-control mb-3" readonly id="total">
                    <label for="payment" class="mb-2">Metode Pembayaran</label>
                    <select class="form-select mb-3" name="payment" id="payment">
                        <option selected disabled>Metode pembayaran...</option>
                        <option value="tunai">Tunai</option>
                        <option value="kredit">Kredit</option>
                    </select>
                    <div style="display: none;">
                        <label for="pay" class="mb-2">Nama Penghutang</label>
                        <input type="text" name="a" class="form-control mb-3"
                            id="a">
                    </div>
                    <label for="pay" class="mb-2">Bayar : (Rp.)</label>
                    <input type="number" min="0" name="pay" value="0" class="form-control mb-3"
                        id="pay" oninput="kembalian(this.value)" readonly>
                    <label for="change" class="mb-2">Kembalian : (Rp.)</label>
                    <input type="number" min="0" name="change" value="0" class="form-control mb-3"
                        readonly id="change">
                    <button type="submit" class="btn btn-primary w-100">Bayar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function kembalian(bayar) {
            const kembali = document.getElementById('change');
            const total = document.getElementById('total').value;
            kembali.value = bayar - total;
            if (kembali.value < 0) {
                kembali.value = 0;
            }
        }
    </script>

    <script>
        const payment = document.getElementById('payment')
        payment.addEventListener('change', e => {
            const pay = document.getElementById('pay');
            if (e.target.value === 'tunai') {
                pay.readOnly = false;
            } else if (e.target.value === 'kredit') {
                pay.readOnly = true;
                pay.value = 0;
                document.getElementById('change').value = 0;
            }
        });
    </script>

    @if ($pesan = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $pesan }}",
                icon: "success",
            });
        </script>
    @endif

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
@endsection
