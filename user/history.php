<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$user_id = (int)$_SESSION['id'];

$stmt = $db->prepare("
    SELECT orders.*, services.nama_layanan
    FROM orders
    JOIN services ON orders.service_id = services.id
    WHERE orders.user_id = ?
    ORDER BY orders.id DESC
");
$stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
$data = $stmt->execute();

$pageTitle = 'Riwayat Pesanan';
$basePath  = '../';
include '../config/header.php';
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-start align-items-sm-center flex-column flex-sm-row gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:1.5rem;">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pesanan
            </h1>
            <p class="text-muted small mb-0">Daftar semua pesanan layanan Anda</p>
        </div>
        <a href="booking.php" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-plus-lg me-1"></i>Booking Baru
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Layanan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no    = 1;
                    $empty = true;
                    while ($row = $data->fetchArray(SQLITE3_ASSOC)):
                        $empty  = false;
                        $status = $row['status'];

                        if ($status === 'Menunggu Konfirmasi') {
                            $badge = 'bg-warning text-dark';
                            $icon  = 'bi-hourglass-split';
                        } elseif ($status === 'Petugas Ditugaskan') {
                            $badge = 'bg-primary';
                            $icon  = 'bi-person-check';
                        } elseif ($status === 'Selesai') {
                            $badge = 'bg-success';
                            $icon  = 'bi-check-circle';
                        } else {
                            $badge = 'bg-secondary';
                            $icon  = 'bi-circle';
                        }
                    ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td class="fw-medium"><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['jam']) ?></td>
                        <td class="text-muted" style="max-width:200px;">
                            <small><?= htmlspecialchars($row['alamat'] ?? '-') ?></small>
                        </td>
                        <td>
                            <span class="badge <?= $badge ?>">
                                <i class="bi <?= $icon ?> me-1"></i><?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if ($empty): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="fw-medium mb-1">Belum ada pesanan</p>
                                <p class="small mb-3 text-muted">Anda belum memiliki riwayat pesanan layanan.</p>
                                <a href="booking.php" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-plus-lg me-1"></i>Buat Pesanan Pertama
                                </a>
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
