<div class="modal fade modal-lg" id="bayarKredit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/transaksi/proses" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Pembayaran Kredit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="total" id="totalModal">
                    <input type="hidden" name="pay" id="payModal">
                    <input type="hidden" name="change" id="changeModal">
                    <input type="hidden" name="payment" value="kredit">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="customer_name" placeholder="Nama pelanggan..." autocomplete="off" list="customer_names">
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
