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

$pageTitle    = 'Dashboard';
$pageSubtitle = 'Kelola semua pesanan layanan kebersihan';
$basePath     = '../';
include '../config/header.php';
?>

<?php if (isset($_GET['assigned'])): ?>
<div class="cs-alert cs-alert-success mb-4">
  <i class="bi bi-check-circle-fill"></i>
  <span>Petugas berhasil ditugaskan ke pesanan.</span>
</div>
<?php endif; ?>

<?php if (isset($_GET['done'])): ?>
<div class="cs-alert cs-alert-success mb-4">
  <i class="bi bi-check-circle-fill"></i>
  <span>Pesanan berhasil ditandai selesai.</span>
</div>
<?php endif; ?>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
  <div class="dash-stat">
    <div class="dash-stat-icon" style="background:#eff6ff;color:#2563eb;">
      <i class="bi bi-list-check"></i>
    </div>
    <div class="dash-stat-value"><?= $totalOrders ?></div>
    <div class="dash-stat-label">Total Pesanan</div>
    <div class="dash-stat-trend trend-up"><i class="bi bi-arrow-up"></i> Semua waktu</div>
  </div>
  <div class="dash-stat">
    <div class="dash-stat-icon" style="background:#fefce8;color:#d97706;">
      <i class="bi bi-hourglass-split"></i>
    </div>
    <div class="dash-stat-value"><?= $pending ?></div>
    <div class="dash-stat-label">Menunggu</div>
    <div class="dash-stat-trend trend-<?= $pending > 0 ? 'down' : 'up' ?>">
      <?= $pending > 0 ? 'Perlu tindakan' : 'Semua tertangani' ?>
    </div>
  </div>
  <div class="dash-stat">
    <div class="dash-stat-icon" style="background:#ede9fe;color:#7c3aed;">
      <i class="bi bi-person-check"></i>
    </div>
    <div class="dash-stat-value"><?= $assigned ?></div>
    <div class="dash-stat-label">Ditugaskan</div>
    <div class="dash-stat-trend trend-up"><i class="bi bi-arrow-up"></i> Sedang berjalan</div>
  </div>
  <div class="dash-stat">
    <div class="dash-stat-icon" style="background:#d1fae5;color:#059669;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="dash-stat-value"><?= $done ?></div>
    <div class="dash-stat-label">Selesai</div>
    <div class="dash-stat-trend trend-up"><i class="bi bi-arrow-up"></i> Berhasil</div>
  </div>
</div>

<!-- Orders Table -->
<div class="cs-card">
  <div class="cs-card-header">
    <div>
      <div class="cs-card-title">Semua Pesanan</div>
      <div style="font-size:.75rem;color:var(--c-gray);margin-top:.15rem;"><?= $totalOrders ?> total pesanan</div>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="cs-table">
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
            if ($status === 'Menunggu Konfirmasi')  $cls = 'status-pending';
            elseif ($status === 'Petugas Ditugaskan') $cls = 'status-assigned';
            else                                      $cls = 'status-done';
        ?>
        <tr>
          <td style="color:var(--c-gray-light);font-size:.8rem;"><?= $no++ ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.625rem;">
              <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($row['nama_user'], 0, 1)) ?>
              </div>
              <span style="font-weight:600;"><?= htmlspecialchars($row['nama_user']) ?></span>
            </div>
          </td>
          <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
          <td>
            <div style="font-weight:600;"><?= htmlspecialchars($row['tanggal']) ?></div>
            <div style="font-size:.75rem;color:var(--c-gray);"><?= htmlspecialchars($row['jam']) ?></div>
          </td>
          <td style="max-width:160px;">
            <div style="font-size:.8rem;color:var(--c-gray);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              <?= htmlspecialchars($row['alamat'] ?? '-') ?>
            </div>
          </td>
          <td><span class="status-badge <?= $cls ?>"><?= htmlspecialchars($status) ?></span></td>
          <td>
            <div style="display:flex;gap:.375rem;flex-wrap:wrap;">
              <a href="assign_worker.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline btn-sm">
                <i class="bi bi-person-plus"></i> Assign
              </a>
              <?php if ($status !== 'Selesai'): ?>
              <a href="update_status.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm"
                 style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;">
                <i class="bi bi-check2"></i> Selesai
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
              <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
              <div class="empty-state-title">Belum ada pesanan masuk</div>
              <div class="empty-state-desc">Pesanan dari pelanggan akan muncul di sini.</div>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../config/footer.php'; ?>
