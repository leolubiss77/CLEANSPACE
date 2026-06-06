<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userName = htmlspecialchars($_SESSION['nama'] ?? '');
$userInitial = strtoupper(mb_substr($_SESSION['nama'] ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'CleanSpace') ?> — CleanSpace</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= $basePath ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="dashboard-layout">

  <!-- ══ SIDEBAR ══ -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon"><i class="bi bi-droplet-half"></i></div>
      CleanSpace
    </div>

    <?php if ($isAdmin): ?>
      <div class="sidebar-section-label">Admin</div>
      <a href="<?= $basePath ?>admin/dashboard.php"
         class="sidebar-link <?= (strpos($_SERVER['PHP_SELF'], 'dashboard') !== false) ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

    <?php else: ?>
      <div class="sidebar-section-label">Menu</div>
      <a href="<?= $basePath ?>user/booking.php"
         class="sidebar-link <?= (strpos($_SERVER['PHP_SELF'], 'booking') !== false) ? 'active' : '' ?>">
        <i class="bi bi-calendar-plus"></i> Booking Layanan
      </a>
      <a href="<?= $basePath ?>user/history.php"
         class="sidebar-link <?= (strpos($_SERVER['PHP_SELF'], 'history') !== false) ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i> Riwayat Pesanan
      </a>
    <?php endif; ?>

    <div class="sidebar-section-label">Akun</div>
    <a href="<?= $basePath ?>auth/logout.php" class="sidebar-link" style="color:rgba(239,68,68,.7);">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>

    <div class="sidebar-user">
      <div class="sidebar-user-inner">
        <div class="sidebar-user-avatar"><?= $userInitial ?></div>
        <div>
          <div class="sidebar-user-name"><?= $userName ?></div>
          <div class="sidebar-user-role"><?= $isAdmin ? 'Administrator' : 'Pelanggan' ?></div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ══ MAIN ══ -->
  <main class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'CleanSpace') ?></div>
        <div class="topbar-sub"><?= htmlspecialchars($pageSubtitle ?? 'CleanSpace Platform') ?></div>
      </div>
      <div class="topbar-actions">
        <?php if (!$isAdmin): ?>
        <a href="<?= $basePath ?>user/booking.php" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-lg"></i> Pesan Baru
        </a>
        <?php endif; ?>
        <div class="sidebar-user-avatar" style="margin:0;cursor:default;" title="<?= $userName ?>">
          <?= $userInitial ?>
        </div>
      </div>
    </div>
    <div class="page-body">
