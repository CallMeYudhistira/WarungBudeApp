<nav class="sidebar">
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
            </ul>
        </div>
    </div>

    <hr class="divider">

    <div class="bottom-content">
        <li class="nav-link">
            <a id="logoutbtn">
                <i class='bx bx-log-out icon'></i>
                <span class="text nav-text">Logout</span>
            </a>
        </li>
    </div>
</nav>

@include('layouts.logout')

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
