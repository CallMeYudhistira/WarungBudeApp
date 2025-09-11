<nav class="navbar navbar-expand-lg bg-light p-3" style="border-radius: 0 0 4px 4px; border-bottom: 1px solid #ccc;">
    <div class="container-fluid">
        <a class="navbar-brand" href="" style="font-size: 2rem;">🏪</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item m-2">
                    <a class="nav-link {{ request()->is('home') ? 'active bold' : '' }}" href="/home">Beranda 🏠</a>
                </li>
                @if (Auth::user()->role == 'kasir' || Auth::user()->role == 'admin')
                    <li class="nav-item m-2">
                        <a class="nav-link {{ request()->is('transaksi') ? 'active bold' : '' }}"
                            href="/transaksi">Transaksi 🛒</a>
                    </li>
                    <li class="nav-item m-2">
                        <a class="nav-link {{ request()->is('kredit') ? 'active bold' : '' }}" href="/kredit">Kredit
                            📒</a>
                    </li>
                @endif
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mt-2 {{ request()->is('users') ? 'active bold' : '' }} 
                        {{ request()->is('barang') ? 'active bold' : '' }} 
                        {{ request()->is('satuan') ? 'active bold' : '' }}
                        {{ request()->is('kategori') ? 'active bold' : '' }}"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Data 📁
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/users">Users 👤</a></li>
                            <li><a class="dropdown-item" href="/barang">Barang 📦</a></li>
                            <li><a class="dropdown-item" href="/kategori">Kategori 🗂️</a></li>
                            <li><a class="dropdown-item" href="/satuan">Satuan 📓</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == 'gudang')
                    <li class="nav-item m-2">
                        <a class="nav-link {{ request()->is('barang') ? 'active bold' : '' }}" href="/barang">Barang
                            📦</a>
                    </li>
                    <li class="nav-item m-2">
                        <a class="nav-link {{ request()->is('kategori') ? 'active bold' : '' }}" href="/kategori">Kategori 🗂️</a>
                    </li>
                    <li class="nav-item m-2">
                        <a class="nav-link {{ request()->is('satuan') ? 'active bold' : '' }}" href="/satuan">Satuan 📓</a>
                    </li>
                @endif
            </ul>
            <form class="d-flex" action="/logout" method="post">
                @csrf <button class="nav-link" type="submit"
                    onclick="return confirm('Yakin Ingin Logout?');">Logout</button>
            </form>
        </div>
    </div>
</nav>
