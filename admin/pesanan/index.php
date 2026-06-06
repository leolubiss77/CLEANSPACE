<?php
$basePath    = '../../';
$currentPage = 'pesanan';
$pageTitle   = 'Kelola Pesanan';
include '../layout/header.php';

$filter = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');

$sql = "SELECT o.*, u.nama AS nama_user, s.nama_layanan, s.harga
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN services s ON o.service_id = s.id
        WHERE 1=1";
if ($filter) $sql .= " AND o.status = " . $db->escapeString("'$filter'");
if ($search) $sql .= " AND (u.nama LIKE '%$search%' OR s.nama_layanan LIKE '%$search%')";
$sql .= " ORDER BY o.id DESC";
$rows = $db->query($sql);

$statuses = ['Menunggu Konfirmasi','Petugas Ditugaskan','Selesai'];
?>

<div class="u-flex-between" style="margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.15rem;font-weight:800;color:var(--admin-text);margin:0 0 .15rem;">Kelola Pesanan</h1>
    <p style="font-size:.78rem;color:var(--admin-muted);margin:0;">Semua pesanan layanan pelanggan</p>
  </div>
</div>

<!-- Filter bar -->
<div class="admin-card" style="margin-bottom:1.25rem;">
  <div class="admin-card-body" style="padding:.875rem 1.25rem;">
    <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
      <div style="position:relative;flex:1;min-width:180px;">
        <i class="bi bi-search" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--admin-muted);font-size:.85rem;"></i>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
               placeholder="Cari pelanggan / layanan..."
               style="width:100%;padding:.5rem .75rem .5rem 2.2rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.8rem;color:var(--admin-text);background:var(--admin-bg);outline:none;">
      </div>
      <select name="status" style="padding:.5rem .875rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.8rem;color:var(--admin-text);background:var(--admin-bg);">
        <option value="">Semua Status</option>
        <?php foreach ($statuses as $s): ?>
        <option value="<?= $s ?>" <?= $filter === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i> Filter</button>
      <?php if ($filter || $search): ?>
      <a href="index.php" class="btn btn-outline btn-sm">Reset</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<div class="admin-card">
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th><th>Pelanggan</th><th>Layanan</th><th>Jadwal</th>
          <th>Alamat</th><th>Harga</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($r = $rows->fetchArray(SQLITE3_ASSOC)):
          $empty = false;
          $s   = $r['status'];
          $cls = $s==='Menunggu Konfirmasi' ? 'badge-pending' : ($s==='Petugas Ditugaskan' ? 'badge-assigned' : 'badge-done');
        ?>
        <tr>
          <td style="color:var(--admin-muted);font-size:.75rem;"><?= $no++ ?></td>
          <td style="font-weight:600;"><?= htmlspecialchars($r['nama_user']) ?></td>
          <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
          <td>
            <div style="font-weight:600;font-size:.82rem;"><?= htmlspecialchars($r['tanggal']) ?></div>
            <div style="font-size:.73rem;color:var(--admin-muted);"><?= htmlspecialchars($r['jam']) ?></div>
          </td>
          <td style="max-width:140px;font-size:.78rem;color:var(--admin-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= htmlspecialchars($r['alamat'] ?? '-') ?>
          </td>
          <td style="font-weight:700;color:var(--admin-primary);">Rp <?= number_format((int)$r['harga'],0,',','.') ?></td>
          <td><span class="badge-status <?= $cls ?>"><?= htmlspecialchars($s) ?></span></td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a href="detail.php?id=<?= (int)$r['id'] ?>" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> Detail</a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="8">
          <div style="text-align:center;padding:3rem 1rem;color:var(--admin-muted);">
            <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
            <div style="font-weight:600;">Belum ada pesanan</div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
