<div class="modal fade modal-lg" id="isiStokBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/refillStock/store" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Isi Stok Barang : {{ $product->product_name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="detail_id" value="{{ $product->product_detail_id }}">
                    <div class="mb-3">
                        <label for="unit_id" class="form-label">Satuan</label>
                        <input type="text" class="form-control" value="{{ $product->unit_name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" class="form-control" name="total" value="0" id="total"
                            autocomplete="off" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Kuantitas</label>
                        <input type="number" class="form-control" name="quantity" value="0" id="qty"
                            autocomplete="off" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" name="price" value="0" id="price" readonly
                            autocomplete="off" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="expired_date" class="form-label">Tanggal Kedaluwarsa</label>
                        <input type="date" class="form-control" name="expired_date"
                            placeholder="Tanggal kedaluwarsa..." autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const qty = document.getElementById('qty');
    const price = document.getElementById('price');
    const total = document.getElementById('total');

    qty.addEventListener('input', e => {
        price.value = Math.ceil(total.value / e.target.value);
    });

    total.addEventListener('input', e => {
        price.value = Math.ceil(e.target.value / qty.value);
    });
</script>