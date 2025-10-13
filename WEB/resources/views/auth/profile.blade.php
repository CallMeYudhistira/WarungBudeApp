@extends('layouts.app')
@section('title', 'Profile')
@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <div class="profile-img mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=512da8&color=fff&size=128"
                                alt="Avatar" class="rounded-circle border border-3 border-light" width="120"
                                height="120">
                        </div>
                        <h4 class="mb-0 fw-bold">{{ $user->name }}</h4>
                        <p class="mb-0 opacity-75">{{ '@' . $user->username }}</p>
                    </div>

                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Informasi Akun</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">Nomor Telepon</span>
                                <span>{{ $user->phone_number ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">Role</span>
                                <span class="badge bg-secondary text-capitalize px-3 py-2">{{ $user->role }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">Tanggal Dibuat</span>
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <div class="text-center py-3 text-muted small">
                            <span>© 2025 <strong>Warung Bude</strong></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
