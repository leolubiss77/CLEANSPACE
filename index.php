<?php $year = date('Y'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanSpace — Layanan Kebersihan Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body style="background:#fff;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="z-index:10;background:rgba(0,0,0,0.15);">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-droplet-half me-2"></i>CleanSpace
        </a>
        <div class="d-flex gap-2">
            <a href="auth/login.php" class="btn btn-outline-light btn-sm px-3">Masuk</a>
            <a href="auth/register.php" class="btn btn-light btn-sm px-3 fw-semibold text-primary">Daftar</a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero-section">
    <div class="container text-white text-center py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <span class="badge rounded-pill px-3 py-2 mb-4 d-inline-flex align-items-center gap-2"
                      style="background:rgba(255,255,255,0.15);font-size:.85rem;border:1px solid rgba(255,255,255,.2)">
                    <i class="bi bi-shield-check"></i> Terpercaya &amp; Profesional
                </span>
                <h1 class="display-5 fw-bold mb-4 lh-sm">
                    Kebersihan Rumah Anda,<br>
                    <span style="color:#93c5fd;">Tanggung Jawab Kami</span>
                </h1>
                <p class="lead mb-5" style="color:rgba(255,255,255,.78);">
                    Layanan kebersihan profesional yang dapat dipesan kapan saja.
                    Tim berpengalaman kami siap menjaga rumah Anda selalu bersih dan nyaman.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="auth/register.php" class="btn btn-light btn-lg px-5 fw-semibold text-primary">
                        Pesan Sekarang <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="auth/login.php" class="btn btn-outline-light btn-lg px-5">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5" style="background:#f8fafc;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 d-inline-block">
                Keunggulan Kami
            </span>
            <h2 class="fw-bold">Mengapa Memilih CleanSpace?</h2>
            <p class="text-muted">Kami memberikan layanan terbaik untuk kenyamanan Anda</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon-wrap bg-primary bg-opacity-10 text-primary mx-auto">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Tim Profesional</h5>
                    <p class="text-muted small mb-0">Petugas kami terlatih dan berpengalaman dalam berbagai layanan kebersihan rumah dan kantor.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon-wrap bg-success bg-opacity-10 text-success mx-auto">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Booking Mudah</h5>
                    <p class="text-muted small mb-0">Pesan layanan kapan saja dan di mana saja melalui platform kami yang mudah dan cepat.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon-wrap bg-warning bg-opacity-10 text-warning mx-auto">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Kualitas Terjamin</h5>
                    <p class="text-muted small mb-0">Kepuasan pelanggan adalah prioritas utama kami, dengan jaminan kualitas di setiap layanan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-4 text-center justify-content-center">
            <div class="col-6 col-md-3">
                <div class="fw-bold" style="font-size:2.25rem;color:#2563eb;">500+</div>
                <div class="text-muted small">Pelanggan Puas</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold" style="font-size:2.25rem;color:#2563eb;">50+</div>
                <div class="text-muted small">Petugas Terlatih</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold" style="font-size:2.25rem;color:#2563eb;">10+</div>
                <div class="text-muted small">Jenis Layanan</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold" style="font-size:2.25rem;color:#2563eb;">4.9★</div>
                <div class="text-muted small">Rating Rata-rata</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
    <div class="container text-center text-white py-3">
        <h3 class="fw-bold mb-3">Siap untuk Rumah yang Lebih Bersih?</h3>
        <p class="mb-4" style="color:rgba(255,255,255,.78);">
            Daftarkan diri Anda dan nikmati kemudahan memesan layanan kebersihan profesional.
        </p>
        <a href="auth/register.php" class="btn btn-light btn-lg px-5 fw-semibold text-primary">
            Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<!-- Footer -->
<footer style="background:#0f172a;color:rgba(255,255,255,.5);padding:2rem 0;text-align:center;font-size:.85rem;">
    <div class="container">
        <div class="mb-2">
            <i class="bi bi-droplet-half me-1 text-primary"></i>
            <strong class="text-white">CleanSpace</strong>
        </div>
        <p class="mb-0">&copy; <?= $year ?> CleanSpace. Semua hak dilindungi.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
