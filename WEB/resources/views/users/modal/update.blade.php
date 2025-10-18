<div class="modal fade modal-lg" id="editUser{{ $user->user_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/users/update" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $user->user_id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama User</label>
                        <input type="text" class="form-control" name="name" placeholder="Nama user..."
                            autocomplete="off" value="{{ $user->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="number" class="form-control" name="phone_number" placeholder="Nomor telepon..."
                            autocomplete="off" value="{{ $user->phone_number }}">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Username..."
                            autocomplete="off" value="{{ $user->username }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" name="password" placeholder="Password..."
                            autocomplete="off" value="- - -">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Hak</label>
                        <select class="form-select" name="role">
                            <option disabled>Hak...</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="gudang" {{ $user->role == 'gudang' ? 'selected' : '' }}>Gudang</option>
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
