<?php
$basePath    = '../';
$currentPage = 'pesanan';
$pageTitle   = 'Assign Petugas';
include 'layout/header.php';

$order_id = (int)($_GET['id'] ?? 0);
if (!$order_id) { header("Location: dashboard.php"); exit; }

$stmt = $db->prepare("SELECT o.*,u.nama AS nama_user,s.nama_layanan FROM orders o JOIN users u ON o.user_id=u.id JOIN services s ON o.service_id=s.id WHERE o.id=?");
$stmt->bindValue(1, $order_id, SQLITE3_INTEGER);
$order = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$order) { header("Location: dashboard.php"); exit; }

$error = '';
if (isset($_POST['assign'])) {
    $worker_id = (int)($_POST['worker_id'] ?? 0);
    if (!$worker_id) { $error = 'Pilih petugas terlebih dahulu.'; }
    else {
        $upd = $db->prepare("UPDATE orders SET worker_id=?,status='Petugas Ditugaskan' WHERE id=?");
        $upd->bindValue(1,$worker_id,SQLITE3_INTEGER);
        $upd->bindValue(2,$order_id, SQLITE3_INTEGER);
        $upd->execute();
        header("Location: dashboard.php?assigned=1"); exit;
    }
}

$workerRows = [];
$wr = $db->query("SELECT * FROM workers WHERE status='Available' ORDER BY nama_petugas");
while ($w = $wr->fetchArray(SQLITE3_ASSOC)) $workerRows[] = $w;

$s = $order['status'];
$cls = $s==='Menunggu Konfirmasi' ? 'badge-pending' : ($s==='Petugas Ditugaskan' ? 'badge-assigned' : 'badge-done');
?>

<div style="max-width:580px;">
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
      <?php if (!empty($order['alamat'])): ?>
      <div style="grid-column:1/-1;">
        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.25rem;">Alamat</div>
        <div style="font-size:.845rem;color:var(--admin-muted);"><?= htmlspecialchars($order['alamat']) ?></div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($error): ?>
  <div class="admin-alert alert-danger"><i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span></div>
  <?php endif; ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-person-plus"></i> Pilih Petugas</div>
    </div>
    <div class="admin-card-body">
      <?php if (empty($workerRows)): ?>
      <div class="admin-alert alert-info"><i class="bi bi-info-circle-fill"></i><span>Tidak ada petugas tersedia.</span></div>
      <a href="dashboard.php" class="btn btn-outline">Kembali</a>
      <?php else: ?>
      <form method="POST">
        <div style="display:flex;flex-direction:column;gap:.625rem;margin-bottom:1.5rem;">
          <?php foreach ($workerRows as $w): ?>
          <label style="cursor:pointer;">
            <input type="radio" name="worker_id" value="<?= (int)$w['id'] ?>" style="display:none;" class="w-radio">
            <div class="w-opt" style="display:flex;align-items:center;gap:.875rem;padding:.875rem 1rem;border:1.5px solid var(--admin-border);border-radius:var(--r-lg);transition:all var(--t);">
              <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($w['nama_petugas'],0,1)) ?>
              </div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:.845rem;"><?= htmlspecialchars($w['nama_petugas']) ?></div>
                <div style="font-size:.75rem;color:var(--admin-muted);"><?= htmlspecialchars($w['nomor_hp']) ?></div>
              </div>
              <span class="badge-status badge-done"><?= htmlspecialchars($w['status']) ?></span>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;gap:.75rem;">
          <button type="submit" name="assign" class="btn btn-primary"><i class="bi bi-person-check"></i> Tugaskan</button>
          <a href="dashboard.php" class="btn btn-outline">Batal</a>
        </div>
      </form>
      <style>.w-radio:checked+.w-opt{border-color:var(--admin-primary);background:var(--admin-primary-50);box-shadow:0 0 0 3px rgba(37,99,235,.1);}.w-opt:hover{border-color:#93c5fd;}</style>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'layout/footer.php'; ?>
