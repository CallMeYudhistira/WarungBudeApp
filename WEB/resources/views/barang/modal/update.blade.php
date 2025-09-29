@php
    $check = false;

    foreach ($categories as $category) {
        if ($category->category_id == $product->category_id) {
            $check = true;
        }
    }
@endphp

<div class="modal fade modal-lg" id="editBarang{{ $product->product_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/barang/update" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($check == false)
                        <div class="alert alert-danger m-4" role="alert" id="alert">
                            <ul>
                                <li>Kategori Sudah Dihapus!</li>
                            </ul>
                        </div>
                    @endif
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $product->product_id }}">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="product_name"
                            value="{{ $product->product_name }}" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="pict" class="form-label">Foto Barang</label>
                        <input type="file" class="form-control" name="pict" id="imageInput" autocomplete="off"
                            accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" name="category_id" id="category_id">
                            <option selected disabled>Kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}"
                                    {{ $product->category_id == $category->category_id && $check == true ? 'selected' : '' }}>
                                    {{ $category->category_name }}</option>
                            @endforeach
                        </select>
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

<script>
    const category = document.getElementById('category_id');
    const alert = document.getElementById('alert');

    category.addEventListener('click', e => {
        alert.style.display = 'none';
    });
</script>
