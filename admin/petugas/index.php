<?php
$basePath    = '../../';
$currentPage = 'petugas';
$pageTitle   = 'Kelola Petugas';
include '../layout/header.php';

if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    $db->exec("DELETE FROM workers WHERE id = $did");
    header("Location: index.php?deleted=1"); exit;
}

$rows = $db->query("SELECT w.*, (SELECT COUNT(*) FROM orders WHERE worker_id = w.id) AS total_pesanan FROM workers w ORDER BY w.nama_petugas ASC");
?>

<div class="u-flex-between" style="margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.15rem;font-weight:800;color:var(--admin-text);margin:0 0 .15rem;">Kelola Petugas</h1>
    <p style="font-size:.78rem;color:var(--admin-muted);margin:0;">Daftar petugas lapangan CleanSpace</p>
  </div>
  <a href="form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Petugas</a>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="admin-alert alert-success" style="margin-bottom:1.25rem;"><i class="bi bi-check-circle-fill"></i><span>Petugas berhasil dihapus.</span></div>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<div class="admin-alert alert-success" style="margin-bottom:1.25rem;"><i class="bi bi-check-circle-fill"></i><span>Petugas berhasil disimpan.</span></div>
<?php endif; ?>

<div class="admin-card">
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr><th>#</th><th>Petugas</th><th>No. HP</th><th>Status</th><th>Pesanan</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($r = $rows->fetchArray(SQLITE3_ASSOC)):
          $empty = false;
          $avail = $r['status'] === 'Available';
        ?>
        <tr>
          <td style="color:var(--admin-muted);font-size:.75rem;"><?= $no++ ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.75rem;">
              <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($r['nama_petugas'],0,1)) ?>
              </div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($r['nama_petugas']) ?></div>
            </div>
          </td>
          <td style="font-size:.845rem;"><?= htmlspecialchars($r['nomor_hp'] ?? '-') ?></td>
          <td>
            <span class="badge-status <?= $avail ? 'badge-done' : 'badge-pending' ?>">
              <?= htmlspecialchars($r['status']) ?>
            </span>
          </td>
          <td style="font-weight:700;text-align:center;"><?= (int)$r['total_pesanan'] ?></td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a href="form.php?id=<?= (int)$r['id'] ?>" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <a href="index.php?delete=<?= (int)$r['id'] ?>"
                 class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;"
                 onclick="return confirm('Hapus petugas ini?')">
                <i class="bi bi-trash"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="6">
          <div style="text-align:center;padding:3rem;color:var(--admin-muted);">
            <i class="bi bi-person-badge" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
            <div style="font-weight:600;">Belum ada petugas</div>
            <div style="margin-top:.5rem;"><a href="form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Petugas</a></div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
