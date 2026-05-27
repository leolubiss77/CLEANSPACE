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

$error = '';

if (isset($_POST['assign'])) {
    $worker_id = (int)($_POST['worker_id'] ?? 0);
    if (!$worker_id) {
        $error = 'Pilih petugas terlebih dahulu.';
    } else {
        $upd = $db->prepare("
            UPDATE orders SET worker_id = ?, status = 'Petugas Ditugaskan' WHERE id = ?
        ");
        $upd->bindValue(1, $worker_id,  SQLITE3_INTEGER);
        $upd->bindValue(2, $order_id,   SQLITE3_INTEGER);
        $upd->execute();
        header("Location: dashboard.php?assigned=1");
        exit;
    }
}

$workerRows = [];
$wResult    = $db->query("SELECT * FROM workers WHERE status = 'Available' ORDER BY nama_petugas");
while ($w = $wResult->fetchArray(SQLITE3_ASSOC)) {
    $workerRows[] = $w;
}

$pageTitle = 'Assign Petugas';
$basePath  = '../';
include '../config/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="mb-4">
                <a href="dashboard.php" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
                <h1 class="fw-bold mt-2 mb-1" style="font-size:1.5rem;">
                    <i class="bi bi-person-plus me-2 text-primary"></i>Assign Petugas
                </h1>
            </div>

            <!-- Order Detail -->
            <div class="card mb-4 border-0" style="background:#f1f5f9;">
                <div class="card-body p-4">
                    <p class="detail-label mb-3">Detail Pesanan #<?= $order_id ?></p>
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
                        <?php if (!empty($order['alamat'])): ?>
                        <div class="col-12">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value"><?= htmlspecialchars($order['alamat']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Pilih Petugas yang Tersedia</h6>

                    <?php if (empty($workerRows)): ?>
                    <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                        <span>Tidak ada petugas yang tersedia saat ini.</span>
                    </div>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Kembali</a>

                    <?php else: ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label">Petugas</label>
                            <select name="worker_id" class="form-select" required>
                                <option value="">-- Pilih Petugas --</option>
                                <?php foreach ($workerRows as $w): ?>
                                <option value="<?= (int)$w['id'] ?>">
                                    <?= htmlspecialchars($w['nama_petugas']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="assign" class="btn btn-primary px-4">
                                <i class="bi bi-person-check me-2"></i>Tugaskan Petugas
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../config/footer.php'; ?>
