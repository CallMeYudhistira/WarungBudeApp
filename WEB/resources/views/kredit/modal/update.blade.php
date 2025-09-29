<div class="modal fade modal-lg" id="editPelanggan{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/kredit/update" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Pelanggan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="customer_name" placeholder="Nama pelanggan..."
                            autocomplete="off" value="{{ $customer->customer_name }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="number" class="form-control" name="phone_number" placeholder="Nomor telepon..."
                            autocomplete="off" value="{{ $customer->phone_number }}">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="address" placeholder="Alamat..."
                            autocomplete="off" value="{{ $customer->address }}">
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
