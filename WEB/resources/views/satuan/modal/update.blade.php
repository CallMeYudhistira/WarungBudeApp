<div class="modal fade modal-lg" id="editSatuan{{ $unit->unit_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/satuan/update" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Satuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $unit->unit_id }}">
                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="unit_name"
                            autocomplete="off" value="{{ $unit->unit_name }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
