<?php
$basePath    = '../../';
$currentPage = 'pelanggan';
$pageTitle   = 'Daftar Pelanggan';
include '../layout/header.php';

$search = trim($_GET['q'] ?? '');
$sql = "SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id = u.id) AS total_pesanan,
               (SELECT COALESCE(SUM(s.harga),0) FROM orders o JOIN services s ON o.service_id=s.id WHERE o.user_id=u.id AND o.status='Selesai') AS total_spend
        FROM users u WHERE u.role = 'user'";
if ($search) $sql .= " AND (u.nama LIKE '%$search%' OR u.email LIKE '%$search%')";
$sql .= " ORDER BY u.id DESC";
$rows = $db->query($sql);
?>

<div class="u-flex-between" style="margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.15rem;font-weight:800;color:var(--admin-text);margin:0 0 .15rem;">Daftar Pelanggan</h1>
    <p style="font-size:.78rem;color:var(--admin-muted);margin:0;">Semua pengguna terdaftar</p>
  </div>
</div>

<!-- Search -->
<div class="admin-card" style="margin-bottom:1.25rem;">
  <div class="admin-card-body" style="padding:.875rem 1.25rem;">
    <form method="GET" style="display:flex;gap:.75rem;">
      <div style="position:relative;flex:1;max-width:360px;">
        <i class="bi bi-search" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--admin-muted);font-size:.85rem;"></i>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
               placeholder="Cari nama atau email..."
               style="width:100%;padding:.5rem .75rem .5rem 2.2rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.8rem;color:var(--admin-text);background:var(--admin-bg);outline:none;">
      </div>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Cari</button>
      <?php if ($search): ?><a href="index.php" class="btn btn-outline btn-sm">Reset</a><?php endif; ?>
    </form>
  </div>
</div>

<div class="admin-card">
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr><th>#</th><th>Pelanggan</th><th>Email</th><th>Total Pesanan</th><th>Total Belanja</th></tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($r = $rows->fetchArray(SQLITE3_ASSOC)):
          $empty = false; ?>
        <tr>
          <td style="color:var(--admin-muted);font-size:.75rem;"><?= $no++ ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.75rem;">
              <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(mb_substr($r['nama'],0,1)) ?>
              </div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($r['nama']) ?></div>
            </div>
          </td>
          <td style="font-size:.845rem;color:var(--admin-muted);"><?= htmlspecialchars($r['email']) ?></td>
          <td style="text-align:center;font-weight:700;"><?= (int)$r['total_pesanan'] ?></td>
          <td style="font-weight:700;color:var(--admin-primary);">Rp <?= number_format((int)$r['total_spend'],0,',','.') ?></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="5">
          <div style="text-align:center;padding:3rem;color:var(--admin-muted);">
            <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
            <div style="font-weight:600;">Belum ada pelanggan terdaftar</div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
