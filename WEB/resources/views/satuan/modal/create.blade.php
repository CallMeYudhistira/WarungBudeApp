<div class="modal fade modal-lg" id="tambahSatuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/satuan/store" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Satuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Nama Satuan</label>
                        <input type="text" class="form-control" name="unit_name" placeholder="Nama satuan..."
                            autocomplete="off">
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
