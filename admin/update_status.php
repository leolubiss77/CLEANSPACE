<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$order_id = (int)($_GET['id'] ?? 0);
if (!$order_id) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $db->prepare("
    SELECT orders.*, users.nama AS nama_user, services.nama_layanan
    FROM orders
    JOIN users    ON orders.user_id    = users.id
    JOIN services ON orders.service_id = services.id
    WHERE orders.id = ?
");
$stmt->bindValue(1, $order_id, SQLITE3_INTEGER);
$order = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$order) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['update'])) {
    $upd = $db->prepare("UPDATE orders SET status = 'Selesai' WHERE id = ?");
    $upd->bindValue(1, $order_id, SQLITE3_INTEGER);
    $upd->execute();
    header("Location: dashboard.php?done=1");
    exit;
}

$pageTitle = 'Tandai Selesai';
$basePath  = '../';
include '../config/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-5">

            <div class="mb-4">
                <a href="dashboard.php" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
                <h1 class="fw-bold mt-2 mb-1" style="font-size:1.5rem;">
                    <i class="bi bi-check-circle me-2 text-success"></i>Tandai Pesanan Selesai
                </h1>
            </div>

            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-body p-4">
                    <p class="detail-label mb-3">Pesanan #<?= $order_id ?></p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-label">Pelanggan</div>
                            <div class="detail-value"><?= htmlspecialchars($order['nama_user']) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-label">Layanan</div>
                            <div class="detail-value"><?= htmlspecialchars($order['nama_layanan']) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value"><?= htmlspecialchars($order['tanggal']) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-label">Jam</div>
                            <div class="detail-value"><?= htmlspecialchars($order['jam']) ?></div>
                        </div>
                        <div class="col-12">
                            <div class="detail-label">Status Saat Ini</div>
                            <span class="badge bg-warning text-dark mt-1">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Card -->
            <div class="card border-success">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-check-circle text-success mb-3 d-block" style="font-size:2.75rem;"></i>
                    <h6 class="fw-semibold mb-2">Konfirmasi Penyelesaian</h6>
                    <p class="text-muted small mb-4">
                        Apakah Anda yakin ingin menandai pesanan ini sebagai <strong>Selesai</strong>?
                        Status tidak dapat dikembalikan setelah dikonfirmasi.
                    </p>
                    <form method="POST" class="d-flex gap-2 justify-content-center">
                        <button type="submit" name="update" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-2"></i>Ya, Tandai Selesai
                        </button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../config/footer.php'; ?>
