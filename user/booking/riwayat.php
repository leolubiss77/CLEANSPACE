<?php
$basePath    = '../../';
$currentPage = 'riwayat';
$pageTitle   = 'Riwayat Pesanan';
include '../layout/header.php';

$userId = (int)$_SESSION['id'];
$stmt = $db->prepare("SELECT o.*,s.nama_layanan,s.harga FROM orders o JOIN services s ON o.service_id=s.id WHERE o.user_id=? ORDER BY o.id DESC");
$stmt->bindValue(1, $userId, SQLITE3_INTEGER);
$data = $stmt->execute();
?>

<div class="u-flex-between" style="margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.2rem;font-weight:800;color:var(--u-text);letter-spacing:-.02em;margin:0 0 .2rem;">Riwayat Pesanan</h1>
    <p style="font-size:.8rem;color:var(--u-muted);margin:0;">Semua pesanan layanan Anda</p>
  </div>
  <a href="create.php" class="u-btn u-btn-primary"><i class="bi bi-plus-lg"></i> Pesan Baru</a>
</div>

<div class="u-card">
  <div style="overflow-x:auto;">
    <table class="u-table">
      <thead>
        <tr>
          <th>#</th><th>Layanan</th><th>Jadwal</th><th>Alamat</th><th>Harga</th><th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; $empty=true;
        while ($row = $data->fetchArray(SQLITE3_ASSOC)):
          $empty = false;
          $s = $row['status'];
          $cls = $s==='Menunggu Konfirmasi' ? 'u-badge-pending' : ($s==='Petugas Ditugaskan' ? 'u-badge-assigned' : 'u-badge-done');
        ?>
        <tr>
          <td style="color:var(--u-light);font-size:.78rem;"><?= $no++ ?></td>
          <td style="font-weight:600;"><?= htmlspecialchars($row['nama_layanan']) ?></td>
          <td>
            <div style="font-weight:600;"><?= htmlspecialchars($row['tanggal']) ?></div>
            <div style="font-size:.75rem;color:var(--u-muted);"><?= htmlspecialchars($row['jam']) ?></div>
          </td>
          <td style="max-width:160px;font-size:.78rem;color:var(--u-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= htmlspecialchars($row['alamat'] ?? '-') ?>
          </td>
          <td style="font-weight:700;color:var(--u-primary);">Rp <?= number_format((int)$row['harga'],0,',','.') ?></td>
          <td><span class="u-badge <?= $cls ?>"><?= htmlspecialchars($s) ?></span></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="6">
          <div class="u-empty">
            <i class="bi bi-inbox"></i>
            <div class="u-empty-title">Belum ada pesanan</div>
            <div class="u-empty-desc" style="margin-bottom:1rem;">Pesan layanan pertama Anda sekarang.</div>
            <a href="create.php" class="u-btn u-btn-primary u-btn-sm"><i class="bi bi-plus-lg"></i> Pesan Sekarang</a>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
