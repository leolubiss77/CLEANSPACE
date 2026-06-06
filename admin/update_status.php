<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$order_id = (int)($_GET['id'] ?? 0);
if (!$order_id) { header("Location: dashboard.php"); exit; }

$stmt = $db->prepare("
    SELECT orders.*, users.nama AS nama_user, services.nama_layanan
    FROM orders
    JOIN users    ON orders.user_id    = users.id
    JOIN services ON orders.service_id = services.id
    WHERE orders.id = ?
");
$stmt->bindValue(1, $order_id, SQLITE3_INTEGER);
$order = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$order) { header("Location: dashboard.php"); exit; }

if (isset($_POST['update'])) {
    $upd = $db->prepare("UPDATE orders SET status = 'Selesai' WHERE id = ?");
    $upd->bindValue(1, $order_id, SQLITE3_INTEGER);
    $upd->execute();
    header("Location: dashboard.php?done=1");
    exit;
}

$pageTitle    = 'Tandai Selesai';
$pageSubtitle = 'Konfirmasi penyelesaian pesanan #' . $order_id;
$basePath     = '../';
include '../config/header.php';
?>

<div style="max-width:520px;">
  <a href="dashboard.php" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
  </a>

  <!-- Order detail -->
  <div class="cs-card" style="margin-bottom:1.25rem;">
    <div class="cs-card-header">
      <div class="cs-card-title">Pesanan #<?= $order_id ?></div>
      <?php
      $s = $order['status'];
      $cls = $s === 'Menunggu Konfirmasi' ? 'status-pending' : ($s === 'Petugas Ditugaskan' ? 'status-assigned' : 'status-done');
      ?>
      <span class="status-badge <?= $cls ?>"><?= htmlspecialchars($s) ?></span>
    </div>
    <div class="cs-card-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Pelanggan</div>
          <div style="font-weight:600;"><?= htmlspecialchars($order['nama_user']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Layanan</div>
          <div style="font-weight:600;"><?= htmlspecialchars($order['nama_layanan']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Tanggal</div>
          <div style="font-weight:600;"><?= htmlspecialchars($order['tanggal']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Jam</div>
          <div style="font-weight:600;"><?= htmlspecialchars($order['jam']) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirm card -->
  <div class="cs-card" style="border-color:#a7f3d0;">
    <div class="cs-card-body" style="text-align:center;padding:2.5rem 2rem;">
      <div style="width:64px;height:64px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.75rem;color:#059669;">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div style="font-size:var(--text-lg);font-weight:700;color:var(--c-dark);margin-bottom:.625rem;">
        Konfirmasi Penyelesaian
      </div>
      <p style="font-size:var(--text-sm);color:var(--c-gray);margin-bottom:2rem;line-height:1.7;">
        Apakah Anda yakin ingin menandai pesanan ini sebagai <strong>Selesai</strong>?<br>
        Status tidak dapat dikembalikan setelah dikonfirmasi.
      </p>
      <form method="POST" style="display:flex;gap:.75rem;justify-content:center;">
        <button type="submit" name="update" class="btn btn-lg"
                style="background:#059669;color:#fff;border-color:#059669;">
          <i class="bi bi-check-lg"></i> Ya, Tandai Selesai
        </button>
        <a href="dashboard.php" class="btn btn-outline btn-lg">Batal</a>
      </form>
    </div>
  </div>
</div>

<?php include '../config/footer.php'; ?>
