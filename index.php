<?php $year = date('Y'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CleanSpace — Layanan Kebersihan Profesional</title>
  <meta name="description" content="Platform pemesanan layanan kebersihan rumah dan kantor yang profesional, terpercaya, dan mudah digunakan.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- ═══════════════ NAVBAR ═══════════════ -->
<nav class="cs-navbar transparent" id="navbar">
  <div class="container d-flex align-items-center justify-content-between">
    <a href="#" class="navbar-brand">
      <span class="brand-icon"><i class="bi bi-droplet-half"></i></span>
      CleanSpace
    </a>
    <div class="nav-links">
      <a href="#features"     class="nav-item-link">Fitur</a>
      <a href="#how-it-works" class="nav-item-link">Cara Kerja</a>
      <a href="#pricing"      class="nav-item-link">Harga</a>
      <a href="#testimonials" class="nav-item-link">Testimoni</a>
      <a href="#faq"          class="nav-item-link">FAQ</a>
    </div>
    <div class="nav-actions">
      <a href="auth/login.php"    class="btn btn-ghost btn-sm" id="nav-login">Masuk</a>
      <a href="auth/register.php" class="btn btn-white  btn-sm" id="nav-register">Mulai Gratis</a>
    </div>
  </div>
</nav>

<!-- ═══════════════ HERO ═══════════════ -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-mesh"></div>
  <div class="hero-grid"></div>

  <div class="container hero-content py-5">
    <div class="row align-items-center g-5">
      <!-- Left copy -->
      <div class="col-lg-6">
        <div class="hero-badge animate-fade-up">
          <span class="dot"></span>
          Lebih dari 500 pelanggan puas
        </div>
        <h1 class="hero-title animate-fade-up delay-100">
          Rumah Bersih,<br>
          <span class="gradient-text">Hidup Lebih Nyaman</span>
        </h1>
        <p class="hero-desc animate-fade-up delay-200">
          Platform pemesanan layanan kebersihan profesional. Pesan kapan saja,
          petugas berpengalaman kami hadir tepat waktu dengan standar kualitas tertinggi.
        </p>
        <div class="hero-actions animate-fade-up delay-300">
          <a href="auth/register.php" class="btn btn-white btn-lg">
            Pesan Sekarang <i class="bi bi-arrow-right"></i>
          </a>
          <a href="#how-it-works" class="btn btn-ghost btn-lg">
            <i class="bi bi-play-circle"></i> Lihat Cara Kerja
          </a>
        </div>
        <div class="hero-stats animate-fade-up delay-400">
          <div class="hero-stat-item">
            <div class="hero-stat-value">500+</div>
            <div class="hero-stat-label">Pelanggan Puas</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">50+</div>
            <div class="hero-stat-label">Petugas Aktif</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">4.9★</div>
            <div class="hero-stat-label">Rating Rata-rata</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">10+</div>
            <div class="hero-stat-label">Jenis Layanan</div>
          </div>
        </div>
      </div>

      <!-- Right visual card -->
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-visual animate-float">
          <div class="hero-card" style="max-width:380px;margin-left:auto;">
            <div class="hero-card-header">
              <div class="hero-card-avatar">CS</div>
              <div>
                <div style="font-weight:700;font-size:.9rem;">Pesanan Aktif</div>
                <div style="font-size:.75rem;opacity:.6;">Hari ini, 3 pesanan baru</div>
              </div>
              <span style="margin-left:auto;font-size:.7rem;background:rgba(52,211,153,.2);color:#34d399;padding:.2rem .6rem;border-radius:99px;font-weight:600;">Live</span>
            </div>

            <div class="hero-mini-card">
              <div class="mini-icon" style="background:rgba(59,130,246,.2);color:#93c5fd;">
                <i class="bi bi-house-heart"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:.8rem;font-weight:600;">Basic Cleaning</div>
                <div style="font-size:.7rem;opacity:.55;">Jl. Merdeka No. 12 · Hari ini 09:00</div>
              </div>
              <span style="font-size:.7rem;background:rgba(251,191,36,.15);color:#fbbf24;padding:.2rem .6rem;border-radius:99px;font-weight:600;">Proses</span>
            </div>

            <div class="hero-mini-card">
              <div class="mini-icon" style="background:rgba(16,185,129,.2);color:#6ee7b7;">
                <i class="bi bi-building"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:.8rem;font-weight:600;">Office Cleaning</div>
                <div style="font-size:.7rem;opacity:.55;">Gedung Graha · Besok 08:00</div>
              </div>
              <span style="font-size:.7rem;background:rgba(52,211,153,.15);color:#34d399;padding:.2rem .6rem;border-radius:99px;font-weight:600;">Terjadwal</span>
            </div>

            <div class="hero-mini-card">
              <div class="mini-icon" style="background:rgba(139,92,246,.2);color:#c4b5fd;">
                <i class="bi bi-droplet"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:.8rem;font-weight:600;">Bathroom Cleaning</div>
                <div style="font-size:.7rem;opacity:.55;">Jl. Sudirman · Besok 13:00</div>
              </div>
              <span style="font-size:.7rem;background:rgba(52,211,153,.15);color:#34d399;padding:.2rem .6rem;border-radius:99px;font-weight:600;">Selesai</span>
            </div>

            <!-- Mini stat row -->
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid rgba(255,255,255,.1);">
              <div style="text-align:center;">
                <div style="font-size:1.25rem;font-weight:800;">24</div>
                <div style="font-size:.65rem;opacity:.5;margin-top:.15rem;">Bulan ini</div>
              </div>
              <div style="text-align:center;border-left:1px solid rgba(255,255,255,.1);border-right:1px solid rgba(255,255,255,.1);">
                <div style="font-size:1.25rem;font-weight:800;">98%</div>
                <div style="font-size:.65rem;opacity:.5;margin-top:.15rem;">Kepuasan</div>
              </div>
              <div style="text-align:center;">
                <div style="font-size:1.25rem;font-weight:800;">4.9</div>
                <div style="font-size:.65rem;opacity:.5;margin-top:.15rem;">Rating</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ LOGOS / SOCIAL PROOF ═══════════════ -->
<div class="logos-section">
  <div class="container text-center">
    <p class="logos-label">Dipercaya oleh pelanggan dari berbagai perusahaan</p>
    <div class="logos-row">
      <div class="logo-item"><i class="bi bi-building"></i> Graha Corp</div>
      <div class="logo-item"><i class="bi bi-bank2"></i> BankSatu</div>
      <div class="logo-item"><i class="bi bi-shop"></i> RetailMart</div>
      <div class="logo-item"><i class="bi bi-hospital"></i> Klinik Prima</div>
      <div class="logo-item"><i class="bi bi-mortarboard"></i> Kampus Maju</div>
      <div class="logo-item"><i class="bi bi-briefcase"></i> StartupHub</div>
    </div>
  </div>
</div>

<!-- ═══════════════ FEATURES ═══════════════ -->
<section class="section" id="features">
  <div class="container">
    <div class="text-center">
      <div class="section-label"><i class="bi bi-stars"></i> Keunggulan Kami</div>
      <h2 class="section-title">Mengapa Memilih CleanSpace?</h2>
      <p class="section-desc mx-auto text-center">
        Kami menggabungkan teknologi modern dengan tim profesional berpengalaman
        untuk memberikan pengalaman kebersihan terbaik.
      </p>
    </div>

    <div class="feature-grid">
      <div class="feature-card animate-fade-up">
        <div class="feature-icon" style="background:#eff6ff;color:#2563eb;">
          <i class="bi bi-people-fill"></i>
        </div>
        <h4>Tim Profesional</h4>
        <p>Seluruh petugas telah melalui pelatihan intensif, lulus seleksi ketat, dan dilengkapi peralatan kebersihan berstandar tinggi.</p>
      </div>
      <div class="feature-card animate-fade-up delay-100">
        <div class="feature-icon" style="background:#f0fdf4;color:#10b981;">
          <i class="bi bi-calendar2-check"></i>
        </div>
        <h4>Booking Fleksibel</h4>
        <p>Pesan layanan kapan saja — pagi, siang, atau malam. Pilih tanggal dan jam yang sesuai jadwal Anda.</p>
      </div>
      <div class="feature-card animate-fade-up delay-200">
        <div class="feature-icon" style="background:#fefce8;color:#d97706;">
          <i class="bi bi-shield-check"></i>
        </div>
        <h4>Terjamin & Aman</h4>
        <p>Setiap petugas terverifikasi identitasnya. Layanan dilindungi garansi kepuasan 100% — tidak puas, kami ulangi gratis.</p>
      </div>
      <div class="feature-card animate-fade-up delay-300">
        <div class="feature-icon" style="background:#fdf4ff;color:#a855f7;">
          <i class="bi bi-phone"></i>
        </div>
        <h4>Pantau Real-Time</h4>
        <p>Lacak status pesanan secara langsung dari dashboard. Notifikasi otomatis saat petugas dalam perjalanan.</p>
      </div>
      <div class="feature-card animate-fade-up delay-400">
        <div class="feature-icon" style="background:#fff7ed;color:#ea580c;">
          <i class="bi bi-star-fill"></i>
        </div>
        <h4>Rating & Ulasan</h4>
        <p>Beri penilaian setelah layanan selesai. Ulasan Anda membantu kami terus meningkatkan kualitas.</p>
      </div>
      <div class="feature-card animate-fade-up delay-500">
        <div class="feature-icon" style="background:#f0fdfa;color:#0d9488;">
          <i class="bi bi-headset"></i>
        </div>
        <h4>Support 24/7</h4>
        <p>Tim customer service kami siap membantu kapan pun Anda membutuhkan, 7 hari seminggu tanpa hari libur.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ HOW IT WORKS ═══════════════ -->
<section class="section how-section" id="how-it-works">
  <div class="container">
    <div class="text-center">
      <div class="section-label"><i class="bi bi-lightning-charge"></i> Cara Kerja</div>
      <h2 class="section-title">Bersih dalam 4 Langkah Mudah</h2>
      <p class="section-desc mx-auto text-center">
        Proses pemesanan yang simpel — dari daftar hingga rumah bersih hanya butuh beberapa menit.
      </p>
    </div>
    <div class="step-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h5>Daftar Akun</h5>
        <p>Buat akun gratis dengan email Anda. Proses registrasi cepat, hanya butuh 1 menit.</p>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h5>Pilih Layanan</h5>
        <p>Pilih jenis layanan, tanggal, waktu, dan masukkan alamat tempat Anda.</p>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h5>Petugas Datang</h5>
        <p>Petugas profesional kami akan hadir tepat waktu sesuai jadwal yang dipilih.</p>
      </div>
      <div class="step-card">
        <div class="step-number">4</div>
        <h5>Rumah Bersih!</h5>
        <p>Nikmati rumah yang bersih dan rapi. Beri ulasan untuk membantu pelanggan lain.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ PRICING ═══════════════ -->
<section class="section" id="pricing">
  <div class="container">
    <div class="text-center">
      <div class="section-label"><i class="bi bi-tag"></i> Harga Layanan</div>
      <h2 class="section-title">Transparan, Tanpa Biaya Tersembunyi</h2>
      <p class="section-desc mx-auto text-center">
        Pilih paket yang sesuai kebutuhan Anda. Semua harga sudah termasuk peralatan dan bahan pembersih.
      </p>
    </div>

    <div class="pricing-grid">
      <!-- Basic -->
      <div class="pricing-card">
        <div class="pricing-name">Basic</div>
        <div class="pricing-price">Rp 100k <span>/ sesi</span></div>
        <div class="pricing-desc">Cocok untuk apartemen dan rumah kecil</div>
        <ul class="pricing-features">
          <li><i class="bi bi-check2"></i> Sapu & pel lantai</li>
          <li><i class="bi bi-check2"></i> Lap debu permukaan</li>
          <li><i class="bi bi-check2"></i> Bersihkan dapur ringan</li>
          <li><i class="bi bi-check2"></i> Buang sampah</li>
          <li class="muted"><i class="bi bi-x"></i> Deep cleaning</li>
          <li class="muted"><i class="bi bi-x"></i> Cuci jendela</li>
        </ul>
        <a href="auth/register.php" class="btn btn-outline w-100">Pilih Basic</a>
      </div>

      <!-- Pro (featured) -->
      <div class="pricing-card featured">
        <div class="pricing-badge">Paling Populer</div>
        <div class="pricing-name">Pro</div>
        <div class="pricing-price">Rp 250k <span>/ sesi</span></div>
        <div class="pricing-desc">Untuk rumah tapak dan keluarga</div>
        <ul class="pricing-features">
          <li><i class="bi bi-check2"></i> Semua layanan Basic</li>
          <li><i class="bi bi-check2"></i> Deep cleaning menyeluruh</li>
          <li><i class="bi bi-check2"></i> Bersihkan kamar mandi</li>
          <li><i class="bi bi-check2"></i> Cuci & lap jendela</li>
          <li><i class="bi bi-check2"></i> Atur ulang perabotan</li>
          <li class="muted"><i class="bi bi-x"></i> Layanan weekend</li>
        </ul>
        <a href="auth/register.php" class="btn btn-primary-gradient w-100">Pilih Pro</a>
      </div>

      <!-- Premium -->
      <div class="pricing-card">
        <div class="pricing-name">Premium</div>
        <div class="pricing-price">Rp 300k <span>/ sesi</span></div>
        <div class="pricing-desc">Untuk kantor dan ruang komersial</div>
        <ul class="pricing-features">
          <li><i class="bi bi-check2"></i> Semua layanan Pro</li>
          <li><i class="bi bi-check2"></i> Cuci karpet & sofa</li>
          <li><i class="bi bi-check2"></i> Disinfeksi ruangan</li>
          <li><i class="bi bi-check2"></i> Layanan weekend</li>
          <li><i class="bi bi-check2"></i> 2 petugas sekaligus</li>
          <li><i class="bi bi-check2"></i> Laporan kebersihan</li>
        </ul>
        <a href="auth/register.php" class="btn btn-outline w-100">Pilih Premium</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ TESTIMONIALS ═══════════════ -->
<section class="section testimonials-section" id="testimonials">
  <div class="container">
    <div class="text-center">
      <div class="section-label"><i class="bi bi-chat-quote"></i> Testimoni</div>
      <h2 class="section-title">Kata Mereka tentang CleanSpace</h2>
      <p class="section-desc mx-auto text-center">
        Lebih dari 500 pelanggan telah merasakan perbedaannya. Ini cerita mereka.
      </p>
    </div>

    <div class="testi-grid">
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Luar biasa! Petugas datang tepat waktu dan hasilnya memuaskan. Rumah saya jadi bersih banget. Pasti akan pesan lagi!"</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#2563eb,#7c3aed);">SR</div>
          <div>
            <div class="testi-name">Sari Rahayu</div>
            <div class="testi-role">Ibu Rumah Tangga, Jakarta Selatan</div>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Booking lewat web gampang banget. Dalam 30 menit sudah dapat konfirmasi. Petugas ramah dan profesional. Recommended!"</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#0ea5e9,#10b981);">BP</div>
          <div>
            <div class="testi-name">Budi Prakoso</div>
            <div class="testi-role">Manajer, Bandung</div>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Saya pakai untuk Office Cleaning setiap minggu. Hasilnya konsisten bagus. Tim CS-nya juga responsif kalau ada pertanyaan."</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444);">DA</div>
          <div>
            <div class="testi-name">Diana Anggraini</div>
            <div class="testi-role">Direktur HRD, Surabaya</div>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">★★★★☆</div>
        <p class="testi-text">"Deep cleaning apartment saya hasilnya memuaskan, sudah termasuk kamar mandi dan dapur. Harganya sangat worth it!"</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#8b5cf6,#ec4899);">RW</div>
          <div>
            <div class="testi-name">Rizky Wirawan</div>
            <div class="testi-role">Mahasiswa, Depok</div>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Sudah 3 bulan langganan. Petugas selalu sama, jadi sudah hafal rumah saya. Hasilnya selalu konsisten dan memuaskan."</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#10b981,#0ea5e9);">FS</div>
          <div>
            <div class="testi-name">Fitri Susanti</div>
            <div class="testi-role">Dokter, Yogyakarta</div>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Terima kasih CleanSpace! Kalau dulu saya harus cari-cari jasa kebersihan, sekarang tinggal klik dan beres. Praktis sekali!"</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#f97316,#f59e0b);">AH</div>
          <div>
            <div class="testi-name">Ahmad Hidayat</div>
            <div class="testi-role">Wiraswasta, Medan</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ FAQ ═══════════════ -->
<section class="section" id="faq">
  <div class="container">
    <div class="text-center">
      <div class="section-label"><i class="bi bi-question-circle"></i> FAQ</div>
      <h2 class="section-title">Pertanyaan yang Sering Ditanyakan</h2>
      <p class="section-desc mx-auto text-center">
        Tidak menemukan jawaban yang dicari? <a href="auth/register.php" style="color:var(--c-primary);">Hubungi kami</a>.
      </p>
    </div>

    <div class="faq-list">
      <div class="faq-item">
        <button class="faq-question">
          Apakah petugas CleanSpace sudah terlatih dan terpercaya?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Ya. Semua petugas CleanSpace melalui proses seleksi ketat, verifikasi identitas (KTP), dan pelatihan intensif sebelum bertugas. Kami juga melakukan evaluasi berkala berdasarkan rating dari pelanggan.
          </div>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Bagaimana cara memesan layanan?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Cukup daftar akun, pilih jenis layanan, tentukan tanggal dan waktu, masukkan alamat Anda, lalu konfirmasi pesanan. Proses hanya butuh kurang dari 2 menit!
          </div>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Apakah peralatan dan bahan pembersih disediakan?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Ya, semua peralatan dan bahan pembersih sudah termasuk dalam harga. Petugas kami datang dengan perlengkapan lengkap. Anda tidak perlu menyiapkan apapun.
          </div>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Apakah ada garansi jika hasil cleaning tidak memuaskan?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Tentu! Kami memberikan garansi kepuasan 100%. Jika Anda tidak puas dengan hasil layanan, kami akan mengirim petugas kembali untuk mengulang pekerjaan tanpa biaya tambahan.
          </div>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Berapa lama proses pengerjaan setiap layanan?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Tergantung jenis layanan dan ukuran ruangan. Basic Cleaning biasanya 1–2 jam, Deep Cleaning 3–5 jam, dan Office Cleaning 2–4 jam. Estimasi akan diberikan saat konfirmasi pesanan.
          </div>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Bisakah saya membatalkan atau menjadwal ulang pesanan?
          <span class="faq-icon"><i class="bi bi-plus"></i></span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Bisa. Anda dapat membatalkan atau menjadwal ulang pesanan hingga 2 jam sebelum jadwal layanan tanpa dikenakan biaya pembatalan. Hubungi CS kami untuk bantuan.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════ CTA ═══════════════ -->
<section class="cta-section">
  <div class="container cta-content text-center">
    <h2 class="cta-title">Siap Merasakan Perbedaannya?</h2>
    <p class="cta-desc">
      Bergabung dengan 500+ pelanggan yang sudah mempercayakan kebersihan rumah mereka kepada CleanSpace.
    </p>
    <div class="cta-actions">
      <a href="auth/register.php" class="btn btn-white btn-lg">
        Mulai Gratis Sekarang <i class="bi bi-arrow-right"></i>
      </a>
      <a href="#how-it-works" class="btn btn-ghost btn-lg">
        <i class="bi bi-info-circle"></i> Pelajari Lebih Lanjut
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════ FOOTER ═══════════════ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5 mb-4">
      <!-- Brand -->
      <div class="col-lg-4">
        <div class="footer-brand">
          <div class="footer-brand-icon"><i class="bi bi-droplet-half"></i></div>
          CleanSpace
        </div>
        <p class="footer-tagline">Platform pemesanan layanan kebersihan profesional yang terpercaya dan mudah digunakan.</p>
      </div>
      <!-- Layanan -->
      <div class="col-6 col-lg-2">
        <div class="footer-col-title">Layanan</div>
        <ul class="footer-links">
          <li><a href="#">Basic Cleaning</a></li>
          <li><a href="#">Deep Cleaning</a></li>
          <li><a href="#">Office Cleaning</a></li>
          <li><a href="#">Bathroom Cleaning</a></li>
        </ul>
      </div>
      <!-- Perusahaan -->
      <div class="col-6 col-lg-2">
        <div class="footer-col-title">Perusahaan</div>
        <ul class="footer-links">
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Kontak</a></li>
        </ul>
      </div>
      <!-- Bantuan -->
      <div class="col-6 col-lg-2">
        <div class="footer-col-title">Bantuan</div>
        <ul class="footer-links">
          <li><a href="#faq">FAQ</a></li>
          <li><a href="#">Syarat Penggunaan</a></li>
          <li><a href="#">Kebijakan Privasi</a></li>
          <li><a href="#">Garansi Layanan</a></li>
        </ul>
      </div>
      <!-- Kontak -->
      <div class="col-6 col-lg-2">
        <div class="footer-col-title">Kontak</div>
        <ul class="footer-links">
          <li><a href="#">hello@cleanspace.id</a></li>
          <li><a href="#">0800-1234-5678</a></li>
          <li><a href="#">Jakarta, Indonesia</a></li>
        </ul>
      </div>
    </div>

    <hr class="footer-divider">

    <div class="footer-bottom">
      <div class="footer-copy">&copy; <?= $year ?> CleanSpace. Semua hak dilindungi.</div>
      <div class="footer-socials">
        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
        <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- ═══════════════ JS ═══════════════ -->
<script>
// Navbar scroll effect
const navbar = document.getElementById('navbar');
const navLogin = document.getElementById('nav-login');
const navRegister = document.getElementById('nav-register');

window.addEventListener('scroll', () => {
  if (window.scrollY > 60) {
    navbar.classList.remove('transparent');
    navbar.classList.add('scrolled');
    navLogin.classList.remove('btn-ghost');
    navLogin.classList.add('btn-outline');
    navRegister.classList.remove('btn-white');
    navRegister.classList.add('btn-primary');
  } else {
    navbar.classList.add('transparent');
    navbar.classList.remove('scrolled');
    navLogin.classList.add('btn-ghost');
    navLogin.classList.remove('btn-outline');
    navRegister.classList.add('btn-white');
    navRegister.classList.remove('btn-primary');
  }
});

// FAQ accordion
document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  });
});

// Scroll reveal (IntersectionObserver)
const revealEls = document.querySelectorAll('.animate-fade-up');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.animationPlayState = 'running';
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.1 });

revealEls.forEach(el => {
  el.style.animationPlayState = 'paused';
  observer.observe(el);
});
</script>
</body>
</html>
