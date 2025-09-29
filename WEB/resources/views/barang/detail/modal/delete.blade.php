@php
    $unit_name = '';
    $check = false;
    foreach ($units as $unit) {
        if ($unit->unit_id == $product_detail->unit_id) {
            $unit_name = $unit->unit_name;
            $check = true;
        }
    }
@endphp

<div class="modal fade modal-lg" id="hapusDetailBarang{{ $product_detail->product_detail_id }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/barang/detail/delete/{{ $product_detail->product_detail_id }}" method="post"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Detail Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('delete')
                    @if ($check == false)
                        <div class="alert alert-danger m-4" role="alert" id="alert">
                            <ul>
                                <li>Satuan Sudah Dihapus!</li>
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="id" value="{{ $product_detail->product_id }}">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Apakah Anda Yakin Ingin Menghapus Detail Barang
                            Ini?</label>
                        <input type="text" class="form-control" value="{{ $product_detail->product_name }}" disabled>
                    </div>
                    <img id="previewImage" src="{{ asset('images/' . $product_detail->pict) }}" alt="Preview Gambar"
                        style="display: block; max-width: 350px; margin: auto; margin-top: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.4);">
                    <div class="mb-3" style="{{  $check == false ? 'display: none;' : '' }}">
                        <label for="unit_id" class="form-label">Satuan</label>
                        <input type="text" class="form-control" value="{{ $unit_name }}" disabled>
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
