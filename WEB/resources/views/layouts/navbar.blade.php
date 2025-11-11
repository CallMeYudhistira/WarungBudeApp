<nav class="sidebar" style="overflow-y: scroll;">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="{{ asset('images/logo/bude.png') }}" alt="Logo Warung Bude">
            </span>

            <div class="text header-text">
                <span class="name">Warung Bude</span>
                <span class="address">KP. Gunung Leutik</span>
            </div>
        </div>
    </header>

    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">
                <hr class="divider" style="margin-bottom: 20px; margin-top: 20px;">
                <li class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                    <a href="/home">
                        <i class='bx bx-home icon'></i>
                        <span class="text nav-text">Beranda</span>
                    </a>
                </li>

                {{-- Data --}}
                @php
                    $isDataActive =
                        request()->is('users*') ||
                        request()->is('kategori*') ||
                        request()->is('satuan*') ||
                        request()->is('barang*');
                @endphp

                @if (Auth::user()->role == 'gudang' || Auth::user()->role == 'admin')
                    <hr class="divider">
                    <li class="nav-link dropdown-btn {{ $isDataActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class='bx bx-data icon'></i>
                            <span class="text nav-text">Data</span>
                            <i class='bx bx-chevron-down arrow'></i>
                        </a>
                    </li>
                    <ul class="submenu {{ $isDataActive ? 'show' : '' }}">
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <a href="/users">
                                    <i class='bx bxs-group icon'></i>
                                    <span class="text nav-text">Users</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                            <a href="/kategori">
                                <i class='bx bx-category icon'></i>
                                <span class="text nav-text">Kategori</span>
                            </a>
                        </li>
                        <li class="nav-link {{ request()->is('satuan*') ? 'active' : '' }}">
                            <a href="/satuan">
                                <i class='bx bx-bookmark icon'></i>
                                <span class="text nav-text">Satuan</span>
                            </a>
                        </li>
                        <li class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
                            <a href="/barang">
                                <i class='bx bx-package icon'></i>
                                <span class="text nav-text">Barang</span>
                            </a>
                        </li>
                    </ul>
                @endif

                {{-- Transaksi --}}
                @php
                    $isTransaksiActive = request()->is('transaksi*') || request()->is('kredit*');
                @endphp

                @if (Auth::user()->role == 'kasir' || Auth::user()->role == 'admin')
                    <hr class="divider">
                    <li class="nav-link dropdown-btn {{ $isTransaksiActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class='bx bx-money icon'></i>
                            <span class="text nav-text">Transaksi</span>
                            <i class='bx bx-chevron-down arrow'></i>
                        </a>
                    </li>
                    <ul class="submenu {{ $isTransaksiActive ? 'show' : '' }}">
                        <li class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}">
                            <a href="/transaksi">
                                <i class='bx bx-cart icon'></i>
                                <span class="text nav-text">Penjualan</span>
                            </a>
                        </li>
                        <li class="nav-link {{ request()->is('kredit*') ? 'active' : '' }}">
                            <a href="/kredit">
                                <i class='bx bx-notepad icon'></i>
                                <span class="text nav-text">Kredit</span>
                            </a>
                        </li>
                    </ul>
                @endif

                {{-- Laporan --}}
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'gudang' || Auth::user()->role == 'kasir')
                    <hr class="divider">
                    <li class="nav-link dropdown-btn">
                        <a href="javascript:void(0);">
                            <i class='bx bx-bar-chart icon'></i>
                            <span class="text nav-text">Laporan</span>
                            <i class='bx bx-chevron-down arrow'></i>
                        </a>
                    </li>
                    <ul class="submenu">
                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'kasir')
                            <li class="nav-link">
                                <a href="/laporan/pendapatan">
                                    <i class='bx bx-wallet icon'></i>
                                    <span class="text nav-text">Pendapatan</span>
                                </a>
                            </li>
                            <li class="nav-link">
                                <a href="/laporan/transaksi">
                                    <i class='bx bx-receipt icon'></i>
                                    <span class="text nav-text">Penjualan</span>
                                </a>
                            </li>
                            <li class="nav-link">
                                <a href="/laporan/kredit">
                                    <i class='bx bx-credit-card icon'></i>
                                    <span class="text nav-text">Kredit</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'gudang')
                            <li class="nav-link">
                                <a href="/laporan/expired">
                                    <i class='bx bx-time-five icon'></i>
                                    <span class="text nav-text">Kedaluwarsa</span>
                                </a>
                            </li>
                            <li class="nav-link">
                                <a href="/laporan/refillStock">
                                    <i class='bx bx-archive icon'></i>
                                    <span class="text nav-text">Isi Stok</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif

                {{-- Akun --}}
                @php
                    $isAccountActive = request()->is('profile*') || request()->is('notifikasi*');
                    $unreadCount = Auth::user()->unreadNotifications()->count();
                @endphp
                <hr class="divider">
                <li class="nav-link dropdown-btn {{ $isAccountActive ? 'active' : '' }}">
                    <a href="javascript:void(0);">
                        <i class='bx bx-user icon'></i>
                        <span class="text nav-text">Akun</span>
                        @if ($unreadCount > 0)
                            <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                        <i class='bx bx-chevron-down arrow'></i>
                    </a>
                </li>
                <ul class="submenu {{ $isAccountActive ? 'show' : '' }}">
                    <li class="nav-link">
                        <a href="javascript:void(0)" id="notifBtn">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Notifikasi</span>
                            @if ($unreadCount > 0)
                                <span class="notif-badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <a href="/profile/{{ Auth::user()->user_id }}">
                            <i class='bx bx-id-card icon'></i>
                            <span class="text nav-text">Profile</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a id="logoutbtn">
                            <i class='bx bx-log-out icon'></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>
                </ul>

            </ul>
        </div>
    </div>
</nav>

@include('auth.logout')

<div class="modal fade" id="notifications" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Notifikasi 📢</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notif-body-placeholder">
                <div class="text-center p-4 text-muted">Memuat... 🔃</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const logout = document.getElementById('logoutbtn');
    logout.addEventListener("click", function() {
        var myModal = new bootstrap.Modal(document.getElementById('logout'));
        myModal.show();
    });

    const dropdownBtns = document.querySelectorAll('.dropdown-btn');
    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const submenu = btn.nextElementSibling;
            submenu.classList.toggle('show');
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notifBtn = document.getElementById('notifBtn');
    const notifModalEl = document.getElementById('notifications');
    const notifModal = new bootstrap.Modal(notifModalEl);
    const notifBody = document.getElementById('notif-body-placeholder');

    notifBtn.addEventListener('click', function () {
        notifBody.innerHTML = '<div class="text-center p-4 text-muted">Memuat...</div>';

        fetch('/notifikasi', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat data notifikasi');
                return response.text();
            })
            .then(html => {
                notifBody.innerHTML = html;
                notifModal.show();
            })
            .catch(err => {
                console.error(err);
                notifBody.innerHTML = '<p class="text-danger text-center">Gagal memuat notifikasi.</p>';
                notifModal.show();
            });
    });
});
</script>
