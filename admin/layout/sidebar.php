<?php
$currentPage = $currentPage ?? '';
$pendingCount = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Menunggu Konfirmasi'") ?? 0;
?>
<aside class="admin-sidebar">

  <!-- Logo -->
  <div class="sb-logo">
    <div class="sb-logo-inner">
      <div class="sb-logo-icon"><i class="bi bi-droplet-half"></i></div>
      <div>
        <div class="sb-logo-name">CleanSpace</div>
        <div class="sb-logo-tag">Admin Panel</div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="sb-nav">

    <div class="sb-group-label">Utama</div>
    <a href="<?= $basePath ?>admin/dashboard.php"
       class="sb-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sb-group-label">Manajemen</div>
    <a href="<?= $basePath ?>admin/pesanan/index.php"
       class="sb-link <?= $currentPage === 'pesanan' ? 'active' : '' ?>">
      <i class="bi bi-receipt"></i> Pesanan
      <?php if ($pendingCount > 0): ?>
      <span class="sb-link-badge"><?= $pendingCount ?></span>
      <?php endif; ?>
    </a>
    <a href="<?= $basePath ?>admin/layanan/index.php"
       class="sb-link <?= $currentPage === 'layanan' ? 'active' : '' ?>">
      <i class="bi bi-stars"></i> Layanan
    </a>
    <a href="<?= $basePath ?>admin/petugas/index.php"
       class="sb-link <?= $currentPage === 'petugas' ? 'active' : '' ?>">
      <i class="bi bi-person-badge"></i> Petugas
    </a>
    <a href="<?= $basePath ?>admin/pelanggan/index.php"
       class="sb-link <?= $currentPage === 'pelanggan' ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Pelanggan
    </a>

    <div class="sb-group-label">Analitik</div>
    <a href="<?= $basePath ?>admin/laporan/index.php"
       class="sb-link <?= $currentPage === 'laporan' ? 'active' : '' ?>">
      <i class="bi bi-bar-chart-line"></i> Laporan
    </a>

    <div class="sb-group-label">Sistem</div>
    <a href="#" class="sb-link <?= $currentPage === 'pengaturan' ? 'active' : '' ?>">
      <i class="bi bi-gear"></i> Pengaturan
    </a>
    <a href="<?= $basePath ?>auth/logout.php" class="sb-link danger">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>

  </nav>

  <!-- User info -->
  <div class="sb-user">
    <div class="sb-user-inner">
      <div class="sb-avatar"><?= strtoupper(mb_substr($_SESSION['nama'] ?? 'A', 0, 1)) ?></div>
      <div style="min-width:0;">
        <div class="sb-user-name"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></div>
        <div class="sb-user-role">Administrator</div>
      </div>
    </div>
  </div>

</aside>
