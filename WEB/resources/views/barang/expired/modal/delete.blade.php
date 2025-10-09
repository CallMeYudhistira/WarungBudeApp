<div class="modal fade modal-lg" id="hapusStok{{ $product->product_detail_id }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/barang/expired/delete" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Stok Barang Yang Kedaluwarsa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_detail_id" value="{{ $product->product_detail_id }}">
                    <input type="hidden" name="refill_stock_id" value="{{ $product->refill_stock_id }}">
                    <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $product->product_name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Tanggal Kedaluwarsa</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($product->expired_date)->translatedFormat('l, d/F/Y') }}" disabled>
                    </div>
                    <img id="previewImage" src="{{ asset('images/' . $product->pict) }}" alt="Preview Gambar"
                        style="display: block; max-width: 300px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Buang</button>
                </div>
            </form>
        </div>
    </div>
</div>
