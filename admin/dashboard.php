<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$totalOrders = $db->querySingle("SELECT COUNT(*) FROM orders");
$pending     = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Menunggu Konfirmasi'");
$assigned    = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Petugas Ditugaskan'");
$done        = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Selesai'");

$orders = $db->query("
    SELECT orders.*, users.nama AS nama_user, services.nama_layanan
    FROM orders
    JOIN users    ON orders.user_id    = users.id
    JOIN services ON orders.service_id = services.id
    ORDER BY orders.id DESC
");

$pageTitle = 'Dashboard Admin';
$basePath  = '../';
include '../config/header.php';
?>

<div class="container py-4">

    <?php if (isset($_GET['assigned'])): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-4">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>Petugas berhasil ditugaskan ke pesanan.</span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['done'])): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-4">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>Pesanan berhasil ditandai selesai.</span>
    </div>
    <?php endif; ?>

    <!-- Page Title -->
    <div class="mb-4">
        <h1 class="fw-bold mb-1" style="font-size:1.5rem;">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin
        </h1>
        <p class="text-muted small">Kelola semua pesanan layanan kebersihan</p>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                <div class="stat-icon"><i class="bi bi-list-check"></i></div>
                <div class="stat-value"><?= $totalOrders ?></div>
                <div class="stat-label">Total Pesanan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value"><?= $pending ?></div>
                <div class="stat-label">Menunggu</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                <div class="stat-icon"><i class="bi bi-person-check"></i></div>
                <div class="stat-value"><?= $assigned ?></div>
                <div class="stat-label">Ditugaskan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value"><?= $done ?></div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-table me-2 text-primary"></i>Daftar Semua Pesanan
            </h6>
            <span class="badge bg-primary rounded-pill"><?= $totalOrders ?> pesanan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no    = 1;
                    $empty = true;
                    while ($row = $orders->fetchArray(SQLITE3_ASSOC)):
                        $empty  = false;
                        $status = $row['status'];

                        if ($status === 'Menunggu Konfirmasi') {
                            $badge = 'bg-warning text-dark';
                        } elseif ($status === 'Petugas Ditugaskan') {
                            $badge = 'bg-primary';
                        } else {
                            $badge = 'bg-success';
                        }
                    ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="fw-medium"><?= htmlspecialchars($row['nama_user']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td>
                            <div class="fw-medium"><?= htmlspecialchars($row['tanggal']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($row['jam']) ?></small>
                        </td>
                        <td style="max-width:170px;">
                            <small class="text-muted"><?= htmlspecialchars($row['alamat'] ?? '-') ?></small>
                        </td>
                        <td>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="assign_worker.php?id=<?= (int)$row['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-person-plus me-1"></i>Assign
                                </a>
                                <?php if ($status !== 'Selesai'): ?>
                                <a href="update_status.php?id=<?= (int)$row['id'] ?>"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-check2 me-1"></i>Selesai
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if ($empty): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="fw-medium">Belum ada pesanan masuk</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include '../config/footer.php'; ?>
