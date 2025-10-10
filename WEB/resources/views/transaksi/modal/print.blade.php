<div class="modal fade" id="print{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Print Transaksi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary p-3 m-2" role="alert">
                    Apakah anda ingin mencetak hasil transaksi?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
                <a href="/transaksi/detail/{{ $id }}/print" class="btn btn-primary">Ya</a>
            </div>
        </div>
    </div>
</div>
