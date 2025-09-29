<div class="modal fade modal-lg" id="hapusSatuan{{ $unit->unit_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/satuan/delete/{{ $unit->unit_id }}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Satuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Apakah Anda Yakin Ingin Menghapus Satuan Ini?</label>
                        <input type="text" class="form-control" name="unit_name" disabled
                            autocomplete="off" value="{{ $unit->unit_name }}">
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
