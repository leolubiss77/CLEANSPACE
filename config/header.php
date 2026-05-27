<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'CleanSpace') ?> | CleanSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= $basePath ?>style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= $basePath ?>index.php">
            <i class="bi bi-droplet-half me-2"></i>CleanSpace
        </a>
        <div class="d-flex align-items-center gap-3">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="<?= $basePath ?>admin/dashboard.php" class="nav-link-custom">
                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                </a>
            <?php else: ?>
                <a href="<?= $basePath ?>user/booking.php" class="nav-link-custom">
                    <i class="bi bi-calendar-plus me-1"></i>Booking
                </a>
                <a href="<?= $basePath ?>user/history.php" class="nav-link-custom">
                    <i class="bi bi-clock-history me-1"></i>Riwayat
                </a>
            <?php endif; ?>

            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['nama'] ?? '') ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li>
                        <span class="dropdown-item-text small text-muted px-3">
                            <?= htmlspecialchars($_SESSION['role'] === 'admin' ? 'Administrator' : 'Pelanggan') ?>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= $basePath ?>auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
