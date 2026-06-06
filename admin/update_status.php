<?php
$basePath    = '../';
$currentPage = 'pesanan';
$pageTitle   = 'Tandai Selesai';
include 'layout/header.php';

$order_id = (int)($_GET['id'] ?? 0);
if (!$order_id) { header("Location: dashboard.php"); exit; }

$stmt = $db->prepare("SELECT o.*,u.nama AS nama_user,s.nama_layanan FROM orders o JOIN users u ON o.user_id=u.id JOIN services s ON o.service_id=s.id WHERE o.id=?");
$stmt->bindValue(1, $order_id, SQLITE3_INTEGER);
$order = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$order) { header("Location: dashboard.php"); exit; }

if (isset($_POST['update'])) {
    $upd = $db->prepare("UPDATE orders SET status='Selesai' WHERE id=?");
    $upd->bindValue(1, $order_id, SQLITE3_INTEGER);
    $upd->execute();
    header("Location: dashboard.php?done=1"); exit;
}

$s = $order['status'];
$cls = $s==='Menunggu Konfirmasi' ? 'badge-pending' : ($s==='Petugas Ditugaskan' ? 'badge-assigned' : 'badge-done');
?>

<div style="max-width:500px;">
  <a href="dashboard.php" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>

  <div class="admin-card" style="margin-bottom:1.25rem;">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-receipt"></i> Pesanan #<?= $order_id ?></div>
      <span class="badge-status <?= $cls ?>"><?= htmlspecialchars($s) ?></span>
    </div>
    <div class="admin-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <?php foreach (['Pelanggan'=>$order['nama_user'],'Layanan'=>$order['nama_layanan'],'Tanggal'=>$order['tanggal'],'Jam'=>$order['jam']] as $lbl=>$val): ?>
      <div>
        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.25rem;"><?= $lbl ?></div>
        <div style="font-weight:600;"><?= htmlspecialchars($val) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="admin-card" style="border-color:#a7f3d0;">
    <div class="admin-card-body" style="text-align:center;padding:2.5rem 2rem;">
      <div style="width:64px;height:64px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.75rem;color:#059669;">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div style="font-size:1rem;font-weight:700;color:var(--admin-text);margin-bottom:.625rem;">Konfirmasi Penyelesaian</div>
      <p style="font-size:.845rem;color:var(--admin-muted);margin-bottom:2rem;line-height:1.7;">
        Tandai pesanan ini sebagai <strong>Selesai</strong>?<br>Status tidak dapat dikembalikan.
      </p>
      <form method="POST" style="display:flex;gap:.75rem;justify-content:center;">
        <button type="submit" name="update" class="btn btn-success btn-lg"><i class="bi bi-check-lg"></i> Ya, Selesai</button>
        <a href="dashboard.php" class="btn btn-outline btn-lg">Batal</a>
      </form>
    </div>
  </div>
</div>

<?php include 'layout/footer.php'; ?>
