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

$error = '';
if (isset($_POST['assign'])) {
    $worker_id = (int)($_POST['worker_id'] ?? 0);
    if (!$worker_id) {
        $error = 'Pilih petugas terlebih dahulu.';
    } else {
        $upd = $db->prepare("UPDATE orders SET worker_id = ?, status = 'Petugas Ditugaskan' WHERE id = ?");
        $upd->bindValue(1, $worker_id, SQLITE3_INTEGER);
        $upd->bindValue(2, $order_id,  SQLITE3_INTEGER);
        $upd->execute();
        header("Location: dashboard.php?assigned=1");
        exit;
    }
}

$workerRows = [];
$wResult    = $db->query("SELECT * FROM workers WHERE status = 'Available' ORDER BY nama_petugas");
while ($w = $wResult->fetchArray(SQLITE3_ASSOC)) { $workerRows[] = $w; }

$pageTitle    = 'Assign Petugas';
$pageSubtitle = 'Tugaskan petugas ke pesanan #' . $order_id;
$basePath     = '../';
include '../config/header.php';
?>

<div style="max-width:600px;">
  <a href="dashboard.php" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
  </a>

  <!-- Order detail -->
  <div class="cs-card" style="margin-bottom:1.25rem;background:var(--c-surface);">
    <div class="cs-card-header">
      <div class="cs-card-title">Detail Pesanan #<?= $order_id ?></div>
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
          <div style="font-weight:600;color:var(--c-dark);"><?= htmlspecialchars($order['nama_user']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Layanan</div>
          <div style="font-weight:600;color:var(--c-dark);"><?= htmlspecialchars($order['nama_layanan']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Tanggal</div>
          <div style="font-weight:600;color:var(--c-dark);"><?= htmlspecialchars($order['tanggal']) ?></div>
        </div>
        <div>
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Jam</div>
          <div style="font-weight:600;color:var(--c-dark);"><?= htmlspecialchars($order['jam']) ?></div>
        </div>
        <?php if (!empty($order['alamat'])): ?>
        <div style="grid-column:1/-1;">
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--c-gray);margin-bottom:.25rem;">Alamat</div>
          <div style="font-size:var(--text-sm);color:var(--c-dark-3);"><?= htmlspecialchars($order['alamat']) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if ($error): ?>
  <div class="cs-alert cs-alert-danger" style="margin-bottom:1rem;">
    <i class="bi bi-exclamation-circle-fill"></i>
    <span><?= htmlspecialchars($error) ?></span>
  </div>
  <?php endif; ?>

  <!-- Assign form -->
  <div class="cs-card">
    <div class="cs-card-header">
      <div class="cs-card-title"><i class="bi bi-person-plus" style="color:var(--c-primary);margin-right:.5rem;"></i>Pilih Petugas</div>
    </div>
    <div class="cs-card-body">
      <?php if (empty($workerRows)): ?>
      <div class="cs-alert cs-alert-info" style="margin-bottom:1.25rem;">
        <i class="bi bi-info-circle-fill"></i>
        <span>Tidak ada petugas yang tersedia saat ini.</span>
      </div>
      <a href="dashboard.php" class="btn btn-outline">Kembali</a>
      <?php else: ?>
      <form method="POST">
        <div style="display:flex;flex-direction:column;gap:.625rem;margin-bottom:1.5rem;">
          <?php foreach ($workerRows as $w): ?>
          <label style="cursor:pointer;">
            <input type="radio" name="worker_id" value="<?= (int)$w['id'] ?>" style="display:none;" class="worker-radio">
            <div class="worker-opt" style="display:flex;align-items:center;gap:.875rem;padding:.875rem 1rem;border:1.5px solid var(--c-border);border-radius:var(--radius-lg);transition:all var(--transition);">
              <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($w['nama_petugas'], 0, 1)) ?>
              </div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:var(--text-sm);"><?= htmlspecialchars($w['nama_petugas']) ?></div>
                <div style="font-size:.75rem;color:var(--c-gray);"><?= htmlspecialchars($w['nomor_hp']) ?></div>
              </div>
              <span class="status-badge status-done"><?= htmlspecialchars($w['status']) ?></span>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;gap:.75rem;">
          <button type="submit" name="assign" class="btn btn-primary-gradient">
            <i class="bi bi-person-check"></i> Tugaskan Petugas
          </button>
          <a href="dashboard.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
.worker-radio:checked + .worker-opt {
  border-color: var(--c-primary);
  background: var(--c-primary-50);
  box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.worker-opt:hover { border-color: var(--c-primary-light); background: var(--c-primary-50); }
</style>

<?php include '../config/footer.php'; ?>
