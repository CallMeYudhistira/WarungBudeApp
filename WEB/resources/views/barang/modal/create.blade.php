<div class="modal fade modal-lg" id="tambahBarang" tabindex="-1" aria-hidden="true">
    <form action="/barang/store" method="post" enctype="multipart/form-data">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="product_name" placeholder="Nama barang..."
                            autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="pict" class="form-label">Foto Barang</label>
                        <input type="file" class="form-control" name="pict" id="imageInput" autocomplete="off"
                            accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" name="category_id">
                            <option selected disabled>Kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </div>
    </form>
</div>