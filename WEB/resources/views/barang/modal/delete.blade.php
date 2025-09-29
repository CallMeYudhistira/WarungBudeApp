<div class="modal fade modal-lg" id="hapusBarang{{ $product->product_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/barang/delete/{{ $product->product_id }}" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Apakah Anda Yakin Ingin Menghapus Barang Ini?</label>
                        <input type="text" class="form-control" name="product_name"
                            value="{{ $product->product_name }}" autocomplete="off" disabled>
                    </div>
                    <img id="previewImage" src="{{ asset('images/' . $product->pict) }}" alt="Preview Gambar"
                        style="display: block; max-width: 350px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
