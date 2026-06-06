<?php
$_sessionBasePath = $basePath ?? '../';
include $_sessionBasePath . 'config/session.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /auth/login.php');
    exit;
}
if (!isset($db)) include $_sessionBasePath . 'config/koneksi.php';

$pageTitle   = $pageTitle   ?? 'Admin';
$pageSubtitle = $pageSubtitle ?? 'CleanSpace Admin Panel';
$currentPage = $currentPage ?? '';
$basePath    = $basePath    ?? '../';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> — CleanSpace Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= $basePath ?>assets/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="admin-wrap">

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="admin-main">

  <!-- Mobile overlay -->
  <div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

  <!-- Topbar -->
  <header class="admin-topbar">
    <div class="tb-left">
      <button class="sb-toggle-btn" id="sbToggle" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
      </button>
      <div class="tb-breadcrumb">
        <a href="<?= $basePath ?>admin/dashboard.php">CleanSpace</a>
        <span class="sep"><i class="bi bi-chevron-right"></i></span>
        <span class="current"><?= htmlspecialchars($pageTitle) ?></span>
      </div>
      <div class="tb-title"><?= htmlspecialchars($pageTitle) ?></div>
    </div>
    <div class="tb-right">
      <div class="tb-icon-btn" title="Notifikasi">
        <i class="bi bi-bell"></i>
        <?php
        $notif = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Menunggu Konfirmasi'");
        if ($notif > 0): ?>
        <span class="tb-notif-dot"></span>
        <?php endif; ?>
      </div>
      <div class="tb-admin-chip">
        <div class="sb-avatar" style="width:26px;height:26px;font-size:.68rem;">
          <?= strtoupper(mb_substr($_SESSION['nama'] ?? 'A', 0, 1)) ?>
        </div>
        <?= htmlspecialchars($_SESSION['nama'] ?? '') ?>
      </div>
    </div>
  </header>

  <!-- Page body -->
  <div class="admin-body">
