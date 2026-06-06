<?php
$basePath    = '../../';
$currentPage = 'layanan';
$pageTitle   = 'Kelola Layanan';
include '../layout/header.php';

// Hapus layanan
if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    $db->exec("DELETE FROM services WHERE id = $did");
    header("Location: index.php?deleted=1"); exit;
}

$rows = $db->query("SELECT * FROM services ORDER BY harga ASC");
?>

<div class="u-flex-between" style="margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.15rem;font-weight:800;color:var(--admin-text);margin:0 0 .15rem;">Kelola Layanan</h1>
    <p style="font-size:.78rem;color:var(--admin-muted);margin:0;">Daftar layanan kebersihan yang tersedia</p>
  </div>
  <a href="form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Layanan</a>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="admin-alert alert-success" style="margin-bottom:1.25rem;"><i class="bi bi-check-circle-fill"></i><span>Layanan berhasil dihapus.</span></div>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<div class="admin-alert alert-success" style="margin-bottom:1.25rem;"><i class="bi bi-check-circle-fill"></i><span>Layanan berhasil disimpan.</span></div>
<?php endif; ?>

<div class="admin-card">
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr><th>#</th><th>Nama Layanan</th><th>Deskripsi</th><th>Harga</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($r = $rows->fetchArray(SQLITE3_ASSOC)):
          $empty = false; ?>
        <tr>
          <td style="color:var(--admin-muted);font-size:.75rem;"><?= $no++ ?></td>
          <td style="font-weight:700;"><?= htmlspecialchars($r['nama_layanan']) ?></td>
          <td style="font-size:.82rem;color:var(--admin-muted);max-width:240px;"><?= htmlspecialchars($r['deskripsi']) ?></td>
          <td style="font-weight:700;color:var(--admin-primary);">Rp <?= number_format((int)$r['harga'],0,',','.') ?></td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a href="form.php?id=<?= (int)$r['id'] ?>" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <a href="index.php?delete=<?= (int)$r['id'] ?>" class="btn btn-sm"
                 style="background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;"
                 onclick="return confirm('Hapus layanan ini?')">
                <i class="bi bi-trash"></i> Hapus
              </a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="5">
          <div style="text-align:center;padding:3rem;color:var(--admin-muted);">
            <i class="bi bi-stars" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
            <div style="font-weight:600;">Belum ada layanan</div>
            <div style="margin-top:.5rem;"><a href="form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Sekarang</a></div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
