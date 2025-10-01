<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WarungBudeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Nata Sans';
            src: url("{{ asset('fonts/NataSans_Regular.ttf') }}");
        }

        body {
            font-family: 'Nata Sans', sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #fff;
            padding: 100px 0;
            text-align: center;
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
            color: #ff9800;
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

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Kelola Warung Lebih Mudah</h1>
            <p>Catat transaksi, kelola stok, dan pantau laporan keuangan hanya dengan sekali klik.</p>
            <a href="/login" class="btn btn-dark btn-lg mt-3">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="features text-center">
        <div class="container">
            <h2 class="mb-5">Fitur-Fitur Aplikasi</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-icon">💰</div>
                    <h4>Transaksi Cepat</h4>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">📦</div>
                    <h4>Manajemen Stok</h4>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">📊</div>
                    <h4>Laporan Otomatis</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Preview Dashboard -->
    <section class="preview">
        <div class="container text-center">
            <img src="{{ asset('images/dashboard.png') }}" alt="Preview Dashboard" class="img-fluid">
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 WarungBudeApp. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
