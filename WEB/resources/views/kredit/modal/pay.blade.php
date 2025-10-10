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
                        <input type="text" class="form-control" value="{{ $customer->customer_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="amount_of_debt_{{ $customer->customer_id }}" class="form-label">Total Hutang</label>
                        <input type="number" class="form-control" name="amount_of_debt"
                            id="total_{{ $customer->customer_id }}" value="{{ $customer->amount_of_debt }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="amount_of_paid_{{ $customer->customer_id }}" class="form-label">Hutang Yang
                            Dibayar</label>
                        <input type="number" class="form-control" name="amount_of_paid"
                            placeholder="Hutang yang dibayar..." id="bayar_{{ $customer->customer_id }}"
                            oninput="sisaHutang('{{ $customer->customer_id }}')" min="0">
                    </div>

                    <div class="mb-3">
                        <label for="remaining_debt_{{ $customer->customer_id }}" class="form-label">Sisa Hutang</label>
                        <input type="number" class="form-control" name="remaining_debt"
                            id="sisa_{{ $customer->customer_id }}" value="{{ $customer->amount_of_debt }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="change_{{ $customer->customer_id }}" class="form-label">Kembalian</label>
                        <input type="number" class="form-control" name="change"
                            id="kembali_{{ $customer->customer_id }}" value="0" readonly>
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
    function sisaHutang(id) {
        const total = document.getElementById('total_' + id);
        const bayar = document.getElementById('bayar_' + id);
        const sisa = document.getElementById('sisa_' + id);
        const kembali = document.getElementById('kembali_' + id);

        const totalVal = parseInt(total.value) || 0;
        const bayarVal = parseInt(bayar.value) || 0;

        const kembaliVal = bayarVal - totalVal;
        const sisaVal = totalVal - bayarVal;

        kembali.value = kembaliVal > 0 ? kembaliVal : 0;
        sisa.value = sisaVal > 0 ? sisaVal : 0;
    }
</script>
