<div class="modal fade modal-lg" id="bayarKredit{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/kredit/pay/store/{{ $customer->customer_id }}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Pembayaran Hutang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" autocomplete="off"
                            value="{{ $customer->customer_name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount_of_debt" class="form-label">Total Hutang</label>
                        <input type="number" class="form-control" name="amount_of_debt" autocomplete="off"
                            value="{{ $customer->amount_of_debt }}" readonly id="total">
                    </div>
                    <div class="mb-3">
                        <label for="amount_of_paid" class="form-label">Hutang Yang Dibayar</label>
                        <input type="number" class="form-control" name="amount_of_paid"
                            placeholder="Hutang yang dibayar..." autocomplete="off" id="bayar"
                            oninput="sisaHutang(this.value)" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="remaining_debt" class="form-label">Sisa Hutang</label>
                        <input type="number" class="form-control" name="remaining_debt" autocomplete="off"
                            id="sisa" readonly value="{{ $customer->amount_of_debt }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change">Kembalian</label>
                        <input type="number" class="form-control" autocomplete="off" id="kembali" name="change"
                            readonly value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        function sisaHutang(bayar) {
            const total = document.getElementById('total').value;
            const sisa = document.getElementById('sisa');
            const kembali = document.getElementById('kembali');

            kembali.value = (bayar - total);
            if (kembali.value < 0) {
                kembali.value = 0;
            }

            sisa.value = (total - bayar);
            if (sisa.value < 0) {
                sisa.value = 0;
            }
        }
    </script>