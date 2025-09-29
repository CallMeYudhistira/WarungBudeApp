<div class="modal fade modal-lg" id="tambahDetailBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/detail/store" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Detail Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->product_id }}">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $product->product_name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="pict" class="form-label">Foto Barang</label>
                    </div>
                    <img id="previewImage" src="{{ asset('images/' . $product->pict) }}" alt="Preview Gambar"
                        style="display: block; max-width: 350px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <input type="text" class="form-control" value="{{ $product->category_name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="unit_id" class="form-label">Satuan</label>
                        <select class="form-select" name="unit_id">
                            <option selected disabled>Satuan...</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
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
