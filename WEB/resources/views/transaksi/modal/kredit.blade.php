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
                        <input type="text" class="form-control" name="customer_name" id="customer_name"
                            placeholder="Nama pelanggan..." autocomplete="off" list="customer_names">

                        <datalist id="customer_names">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_name }}"
                                    data-phone="{{ $customer->phone_number }}" data-address="{{ $customer->address }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Nomor telepon..." autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea rows="3" class="form-control" name="address" id="address" placeholder="Alamat..." autocomplete="off"></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerInput = document.getElementById('customer_name');
        const phoneInput = document.getElementById('phone_number');
        const addressInput = document.getElementById('address');
        const datalist = document.getElementById('customer_names');

        customerInput.addEventListener('input', function() {
            const inputValue = this.value.trim();
            const option = Array.from(datalist.options)
                .find(opt => opt.value.toLowerCase() === inputValue.toLowerCase());

            if (option) {
                phoneInput.value = option.dataset.phone || '';
                addressInput.value = option.dataset.address || '';
            } else {
                phoneInput.value = '';
                addressInput.value = '';
            }
        });
    });
</script>
