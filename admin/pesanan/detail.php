<?php
$basePath    = '../../';
$currentPage = 'pesanan';
$pageTitle   = 'Detail Pesanan';
include '../layout/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$stmt = $db->prepare("SELECT o.*, u.nama AS nama_user, u.email AS email_user,
                       s.nama_layanan, s.harga, s.deskripsi AS deskripsi_layanan,
                       w.nama_petugas
                       FROM orders o
                       JOIN users u ON o.user_id = u.id
                       JOIN services s ON o.service_id = s.id
                       LEFT JOIN workers w ON o.worker_id = w.id
                       WHERE o.id = ?");
$stmt->bindValue(1, $id, SQLITE3_INTEGER);
$order = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (!$order) { header("Location: index.php"); exit; }

$flash = '';

// Update status
if (isset($_POST['update_status'])) {
    $newStatus = $_POST['status'] ?? '';
    $allowed   = ['Menunggu Konfirmasi','Petugas Ditugaskan','Selesai'];
    if (in_array($newStatus, $allowed)) {
        $upd = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $upd->bindValue(1, $newStatus, SQLITE3_TEXT);
        $upd->bindValue(2, $id, SQLITE3_INTEGER);
        $upd->execute();
        $flash = 'Status pesanan diperbarui.';
        $order['status'] = $newStatus;
    }
}

// Assign worker
if (isset($_POST['assign_worker'])) {
    $wid = (int)($_POST['worker_id'] ?? 0);
    if ($wid) {
        $upd = $db->prepare("UPDATE orders SET worker_id = ?, status = 'Petugas Ditugaskan' WHERE id = ?");
        $upd->bindValue(1, $wid, SQLITE3_INTEGER);
        $upd->bindValue(2, $id, SQLITE3_INTEGER);
        $upd->execute();
        $flash = 'Petugas berhasil ditugaskan.';
        $order['status'] = 'Petugas Ditugaskan';
        $order['worker_id'] = $wid;
        $w = $db->querySingle("SELECT nama_petugas FROM workers WHERE id = $wid", true);
        $order['nama_petugas'] = $w['nama_petugas'] ?? '';
    }
}

$workers = [];
$wr = $db->query("SELECT * FROM workers WHERE status = 'Available' ORDER BY nama_petugas");
while ($w = $wr->fetchArray(SQLITE3_ASSOC)) $workers[] = $w;

$s   = $order['status'];
$cls = $s==='Menunggu Konfirmasi' ? 'badge-pending' : ($s==='Petugas Ditugaskan' ? 'badge-assigned' : 'badge-done');
$statuses = ['Menunggu Konfirmasi','Petugas Ditugaskan','Selesai'];
?>

<div style="margin-bottom:1.25rem;">
  <a href="index.php" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if ($flash): ?>
<div class="admin-alert alert-success" style="margin-bottom:1.25rem;"><i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($flash) ?></span></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start;">

  <!-- Left: Info pesanan -->
  <div style="display:flex;flex-direction:column;gap:1.25rem;">

    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="bi bi-receipt"></i> Pesanan #<?= $id ?></div>
        <span class="badge-status <?= $cls ?>"><?= htmlspecialchars($s) ?></span>
      </div>
      <div class="admin-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <?php
        $fields = [
          'Pelanggan'  => $order['nama_user'],
          'Email'      => $order['email_user'],
          'Layanan'    => $order['nama_layanan'],
          'Harga'      => 'Rp ' . number_format((int)$order['harga'],0,',','.'),
          'Tanggal'    => $order['tanggal'],
          'Jam'        => $order['jam'],
        ];
        foreach ($fields as $lbl => $val): ?>
        <div>
          <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.2rem;"><?= $lbl ?></div>
          <div style="font-weight:600;font-size:.875rem;"><?= htmlspecialchars((string)$val) ?></div>
        </div>
        <?php endforeach; ?>
        <?php if (!empty($order['alamat'])): ?>
        <div style="grid-column:1/-1;">
          <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.2rem;">Alamat</div>
          <div style="font-size:.845rem;color:var(--admin-muted);line-height:1.6;"><?= htmlspecialchars($order['alamat']) ?></div>
        </div>
        <?php endif; ?>
        <div style="grid-column:1/-1;">
          <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.2rem;">Petugas</div>
          <div style="font-weight:600;font-size:.875rem;"><?= $order['nama_petugas'] ? htmlspecialchars($order['nama_petugas']) : '<span style="color:var(--admin-muted);">Belum ditugaskan</span>' ?></div>
        </div>
      </div>
    </div>

    <!-- Ubah Status -->
    <div class="admin-card">
      <div class="admin-card-header"><div class="admin-card-title"><i class="bi bi-arrow-repeat"></i> Ubah Status</div></div>
      <div class="admin-card-body">
        <form method="POST" style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
          <select name="status" style="flex:1;min-width:200px;padding:.5rem .875rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.845rem;color:var(--admin-text);background:#fff;">
            <?php foreach ($statuses as $st): ?>
            <option value="<?= $st ?>" <?= $order['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" name="update_status" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
        </form>
      </div>
    </div>

  </div>

  <!-- Right: Assign petugas -->
  <div class="admin-card">
    <div class="admin-card-header"><div class="admin-card-title"><i class="bi bi-person-plus"></i> Tugaskan Petugas</div></div>
    <div class="admin-card-body">
      <?php if (empty($workers)): ?>
      <div class="admin-alert alert-info"><i class="bi bi-info-circle-fill"></i><span>Tidak ada petugas tersedia.</span></div>
      <?php else: ?>
      <form method="POST">
        <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.25rem;">
          <?php foreach ($workers as $w): $sel = $order['worker_id'] == $w['id']; ?>
          <label style="cursor:pointer;">
            <input type="radio" name="worker_id" value="<?= (int)$w['id'] ?>" style="display:none;" class="wr" <?= $sel ? 'checked' : '' ?>>
            <div class="w-opt <?= $sel ? 'selected' : '' ?>" style="display:flex;align-items:center;gap:.75rem;padding:.75rem;border:1.5px solid var(--admin-border);border-radius:var(--r-lg);transition:all var(--t);">
              <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($w['nama_petugas'],0,1)) ?>
              </div>
              <div>
                <div style="font-weight:700;font-size:.82rem;"><?= htmlspecialchars($w['nama_petugas']) ?></div>
                <div style="font-size:.72rem;color:var(--admin-muted);"><?= htmlspecialchars($w['nomor_hp']) ?></div>
              </div>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <button type="submit" name="assign_worker" class="btn btn-primary w-100" style="width:100%;justify-content:center;">
          <i class="bi bi-person-check"></i> Tugaskan
        </button>
      </form>
      <style>
        .wr:checked + .w-opt, .w-opt.selected { border-color:var(--admin-primary);background:var(--admin-primary-50); }
        .w-opt:hover { border-color:#93c5fd; }
      </style>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php include '../layout/footer.php'; ?>
