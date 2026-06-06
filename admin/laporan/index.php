<?php
$basePath    = '../../';
$currentPage = 'laporan';
$pageTitle   = 'Laporan';
include '../layout/header.php';

$dari    = $_GET['dari']   ?? date('Y-m-01');
$sampai  = $_GET['sampai'] ?? date('Y-m-d');

// Summary stats
$totalPendapatan = $db->querySingle("SELECT COALESCE(SUM(s.harga),0) FROM orders o JOIN services s ON o.service_id=s.id WHERE o.status='Selesai' AND o.tanggal BETWEEN '$dari' AND '$sampai'");
$totalSelesai    = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status='Selesai' AND tanggal BETWEEN '$dari' AND '$sampai'");
$totalSemua      = $db->querySingle("SELECT COUNT(*) FROM orders WHERE tanggal BETWEEN '$dari' AND '$sampai'");
$rataPerBulan    = $db->querySingle("SELECT COALESCE(AVG(monthly),0) FROM (SELECT strftime('%Y-%m',tanggal) AS m, SUM(s.harga) AS monthly FROM orders o JOIN services s ON o.service_id=s.id WHERE o.status='Selesai' GROUP BY m)");

// Detail
$stmt = $db->prepare("SELECT o.*, u.nama AS nama_user, s.nama_layanan, s.harga
                       FROM orders o
                       JOIN users u ON o.user_id=u.id
                       JOIN services s ON o.service_id=s.id
                       WHERE o.status='Selesai' AND o.tanggal BETWEEN ? AND ?
                       ORDER BY o.tanggal DESC");
$stmt->bindValue(1,$dari,   SQLITE3_TEXT);
$stmt->bindValue(2,$sampai, SQLITE3_TEXT);
$rows = $stmt->execute();
?>

<!-- Filter -->
<div class="admin-card" style="margin-bottom:1.5rem;">
  <div class="admin-card-body" style="padding:.875rem 1.25rem;">
    <form method="GET" style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
      <label style="font-size:.78rem;font-weight:600;color:var(--admin-muted);">Dari</label>
      <input type="date" name="dari" value="<?= $dari ?>"
             style="padding:.45rem .75rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.8rem;color:var(--admin-text);background:var(--admin-bg);">
      <label style="font-size:.78rem;font-weight:600;color:var(--admin-muted);">Sampai</label>
      <input type="date" name="sampai" value="<?= $sampai ?>"
             style="padding:.45rem .75rem;border:1.5px solid var(--admin-border);border-radius:var(--r-md);font-size:.8rem;color:var(--admin-text);background:var(--admin-bg);">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i> Filter</button>
    </form>
  </div>
</div>

<!-- Summary cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
  <div class="stat-card stat-green">
    <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
    <div class="stat-body">
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-value">Rp <?= number_format((int)$totalPendapatan,0,',','.') ?></div>
    </div>
  </div>
  <div class="stat-card stat-blue">
    <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
    <div class="stat-body">
      <div class="stat-label">Pesanan Selesai</div>
      <div class="stat-value"><?= (int)$totalSelesai ?></div>
    </div>
  </div>
  <div class="stat-card stat-orange">
    <div class="stat-icon"><i class="bi bi-receipt"></i></div>
    <div class="stat-body">
      <div class="stat-label">Total Pesanan</div>
      <div class="stat-value"><?= (int)$totalSemua ?></div>
    </div>
  </div>
  <div class="stat-card stat-purple">
    <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
    <div class="stat-body">
      <div class="stat-label">Rata-rata/Bulan</div>
      <div class="stat-value">Rp <?= number_format((int)$rataPerBulan,0,',','.') ?></div>
    </div>
  </div>
</div>

<!-- Tabel detail -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="bi bi-table"></i> Detail Pesanan Selesai</div>
    <a href="index.php?dari=<?= $dari ?>&sampai=<?= $sampai ?>&export=1" class="btn btn-outline btn-sm">
      <i class="bi bi-download"></i> Export CSV
    </a>
  </div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr><th>#</th><th>Pelanggan</th><th>Layanan</th><th>Tanggal</th><th>Harga</th></tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($r = $rows->fetchArray(SQLITE3_ASSOC)):
          $empty = false; ?>
        <tr>
          <td style="color:var(--admin-muted);font-size:.75rem;"><?= $no++ ?></td>
          <td style="font-weight:600;"><?= htmlspecialchars($r['nama_user']) ?></td>
          <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
          <td style="font-size:.82rem;"><?= htmlspecialchars($r['tanggal']) ?></td>
          <td style="font-weight:700;color:var(--admin-primary);">Rp <?= number_format((int)$r['harga'],0,',','.') ?></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="5">
          <div style="text-align:center;padding:3rem;color:var(--admin-muted);">
            <i class="bi bi-bar-chart-line" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
            <div style="font-weight:600;">Tidak ada pesanan selesai pada periode ini</div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// Handle CSV export
if (isset($_GET['export'])) {
    // Re-query for export
    $estmt = $db->prepare("SELECT o.tanggal, u.nama AS pelanggan, s.nama_layanan, s.harga FROM orders o JOIN users u ON o.user_id=u.id JOIN services s ON o.service_id=s.id WHERE o.status='Selesai' AND o.tanggal BETWEEN ? AND ? ORDER BY o.tanggal DESC");
    $estmt->bindValue(1,$dari,SQLITE3_TEXT);
    $estmt->bindValue(2,$sampai,SQLITE3_TEXT);
    $erows = $estmt->execute();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan-'.$dari.'-'.$sampai.'.csv"');
    echo "Tanggal,Pelanggan,Layanan,Harga\n";
    while ($er = $erows->fetchArray(SQLITE3_ASSOC)) {
        echo implode(',', array_map(fn($v) => '"'.$v.'"', $er)) . "\n";
    }
    exit;
}
?>

<?php include '../layout/footer.php'; ?>
