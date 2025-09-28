<div class="modal fade modal-lg" id="hapusKategori{{ $category->category_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/kategori/delete/{{ $category->category_id }}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Apakah Anda Yakin Ingin Menghapus Kategori Ini?</label>
                        <input type="text" class="form-control" name="category_name" value="{{ $category->category_name }}" disabled
                            autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
