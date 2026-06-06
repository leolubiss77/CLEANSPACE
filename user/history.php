<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$user_id = (int)$_SESSION['id'];

$stmt = $db->prepare("
    SELECT orders.*, services.nama_layanan, services.harga
    FROM orders
    JOIN services ON orders.service_id = services.id
    WHERE orders.user_id = ?
    ORDER BY orders.id DESC
");
$stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
$data = $stmt->execute();

$pageTitle    = 'Riwayat Pesanan';
$pageSubtitle = 'Semua riwayat pemesanan layanan Anda';
$basePath     = '../';
include '../config/header.php';
?>

<div class="cs-card">
  <div class="cs-card-header">
    <div>
      <div class="cs-card-title">Riwayat Pesanan</div>
      <div style="font-size:.75rem;color:var(--c-gray);margin-top:.15rem;">Semua pesanan Anda tercatat di sini</div>
    </div>
    <a href="booking.php" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> Pesan Baru
    </a>
  </div>
  <div style="overflow-x:auto;">
    <table class="cs-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Layanan</th>
          <th>Jadwal</th>
          <th>Alamat</th>
          <th>Harga</th>
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
            if ($status === 'Menunggu Konfirmasi')  $cls = 'status-pending';
            elseif ($status === 'Petugas Ditugaskan') $cls = 'status-assigned';
            else                                      $cls = 'status-done';
        ?>
        <tr>
          <td style="color:var(--c-gray-light);font-size:.8rem;"><?= $no++ ?></td>
          <td style="font-weight:600;"><?= htmlspecialchars($row['nama_layanan']) ?></td>
          <td>
            <div style="font-weight:600;"><?= htmlspecialchars($row['tanggal']) ?></div>
            <div style="font-size:.75rem;color:var(--c-gray);"><?= htmlspecialchars($row['jam']) ?></div>
          </td>
          <td style="max-width:180px;">
            <div style="font-size:.8rem;color:var(--c-gray);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              <?= htmlspecialchars($row['alamat'] ?? '-') ?>
            </div>
          </td>
          <td style="font-weight:700;color:var(--c-primary);">
            Rp <?= number_format((int)$row['harga'], 0, ',', '.') ?>
          </td>
          <td><span class="status-badge <?= $cls ?>"><?= htmlspecialchars($status) ?></span></td>
        </tr>
        <?php endwhile; ?>

        <?php if ($empty): ?>
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
              <div class="empty-state-title">Belum ada pesanan</div>
              <div class="empty-state-desc" style="margin-bottom:1.25rem;">Anda belum memiliki riwayat pesanan layanan.</div>
              <a href="booking.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Buat Pesanan Pertama
              </a>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../config/footer.php'; ?>
