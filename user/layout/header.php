<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ' . ($basePath ?? '../') . 'auth/login.php');
    exit;
}
if (!isset($db)) include ($basePath ?? '../') . 'config/koneksi.php';

$pageTitle   = $pageTitle   ?? 'Dashboard';
$currentPage = $currentPage ?? '';
$basePath    = $basePath    ?? '../';
$userName    = htmlspecialchars($_SESSION['nama'] ?? '');
$userInitial = strtoupper(mb_substr($_SESSION['nama'] ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> — CleanSpace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= $basePath ?>assets/css/user.css" rel="stylesheet">
</head>
<body>

<nav class="user-nav">
  <a href="<?= $basePath ?>user/dashboard.php" class="user-nav-logo">
    <div class="user-nav-logo-icon"><i class="bi bi-droplet-half"></i></div>
    CleanSpace
  </a>

  <div class="user-nav-links">
    <a href="<?= $basePath ?>user/dashboard.php"
       class="user-nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a href="<?= $basePath ?>user/booking/create.php"
       class="user-nav-link <?= $currentPage === 'booking' ? 'active' : '' ?>">
      <i class="bi bi-calendar-plus"></i> Pesan Layanan
    </a>
    <a href="<?= $basePath ?>user/booking/riwayat.php"
       class="user-nav-link <?= $currentPage === 'riwayat' ? 'active' : '' ?>">
      <i class="bi bi-clock-history"></i> Riwayat
    </a>
  </div>

  <div class="user-nav-right">
    <div style="display:flex;align-items:center;gap:.5rem;">
      <div class="user-nav-avatar"><?= $userInitial ?></div>
      <span class="user-nav-name" style="display:none;" id="navName"><?= $userName ?></span>
    </div>
    <a href="<?= $basePath ?>auth/logout.php" class="user-nav-logout">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
  </div>
</nav>

<div class="user-layout">
