<div class="modal fade modal-lg" id="hapusUser{{ $user->user_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/users/delete/{{ $user->user_id }}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="name" class="form-label">Apakah Anda Yakin Ingin Menghapus User Ini?</label>
                        <input type="text" class="form-control" name="name"
                            autocomplete="off" value="{{ $user->name }}" disabled>
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
