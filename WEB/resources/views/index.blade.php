<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Warung Bude</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nata+Sans:wght@100..900&display=swap");

        body {
            font-family: 'Nata Sans', sans-serif;
        }

        .hero {
            background: linear-gradient(to right, #5c6bc0, #512da8);
            color: #fff;
            padding: 0 15px;
            min-height: 100vh;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .feature-icon {
            font-size: 40px;
            color: #512da8;
            margin-bottom: 15px;
        }

        .features {
            padding: 60px 0;
        }

        .preview {
            padding: 60px 0;
            background-color: #f9f9f9;
        }

        .preview img {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        footer {
            background: #212529;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">
    <section id="home" class="hero d-flex align-items-center text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Kelola Warung Lebih Mudah</h1>
            <p class="lead mt-3 mb-4">Catat transaksi, kelola stok, dan pantau laporan keuangan hanya dengan sekali klik.</p>
            <a href="/login" class="btn btn-light btn-lg px-4 me-2">Mulai Sekarang</a>
        </div>
    </section>

    <section class="features text-center bg-light">
        <div class="container">
            <h2 class="mt-5">Fitur-Fitur Aplikasi</h2>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/kelola.png') }}"
                        class="img-fluid rounded">
                </div>
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Pengelolaan Data</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Manajemen Barang</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/barang.png') }}"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/transaksi.png') }}"
                        class="img-fluid rounded">
                </div>
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Transaksi Cepat</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Manajemen Catatan Kredit</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/kredit.png') }}"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/laporan.png') }}"
                        class="img-fluid rounded">
                </div>
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Laporan Akurat</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 mx-5">
                    <h2 class="fw-bold mb-3">Notifikasi</h2>
                    <p class="text-muted mb-4">Aplikasi ini dirancang untuk mempermudah pengguna dalam melakukan transaksi secara akurat, cepat, dan aman.
                    </p>
                </div>
                <div class="col-md-7 mb-4 mb-md-0">
                    <img src="{{ asset('images/fitur/notif.png') }}"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 WarungBudeApp. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
