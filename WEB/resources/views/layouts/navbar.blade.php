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
                @if (Auth::user()->role == 'kasir' || Auth::user()->role == 'admin')
                    <hr class="divider" style="margin-bottom: 30px;">
                    <li class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <a href="/home">
                            <i class='bx bx-home icon'></i>
                            <span class="text nav-text">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-link {{ request()->is('transaksi') ? 'active' : '' }}">
                        <a href="/transaksi">
                            <i class='bx bx-cart icon'></i>
                            <span class="text nav-text">Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-link {{ request()->is('kredit') ? 'active' : '' }}">
                        <a href="/kredit">
                            <i class='bx bx-notepad icon'></i>
                            <span class="text nav-text">Kredit</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'admin')
                    <hr class="divider">
                    <li class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                        <a href="/users">
                            <i class='bx bxs-group icon'></i>
                            <span class="text nav-text">Users</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'gudang' || Auth::user()->role == 'admin')
                    <hr class="divider" style="margin-bottom: 30px;">
                    <li class="nav-link {{ request()->is('satuan') ? 'active' : '' }}">
                        <a href="/satuan">
                            <i class='bx bx-bookmark icon'></i>
                            <span class="text nav-text">Satuan</span>
                        </a>
                    </li>
                    <li class="nav-link {{ request()->is('kategori') ? 'active' : '' }}">
                        <a href="/kategori">
                            <i class='bx bx-category icon'></i>
                            <span class="text nav-text">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-link {{ request()->is('barang') ? 'active' : '' }}">
                        <a href="/barang">
                            <i class='bx bx-package icon'></i>
                            <span class="text nav-text">Barang</span>
                        </a>
                    </li>
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
</script>
