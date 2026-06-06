<?php
$basePath    = '../';
$currentPage = 'dashboard';
$pageTitle   = 'Dashboard';
include 'layout/header.php';

$userId = (int)$_SESSION['id'];

// Stats user
$totalPesanan  = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $userId");
$pesananAktif  = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $userId AND status != 'Selesai'");
$pesananSelesai = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $userId AND status = 'Selesai'");

// Pesanan aktif (belum selesai)
$stmtAktif = $db->prepare("
    SELECT o.*, s.nama_layanan, s.harga
    FROM orders o JOIN services s ON o.service_id = s.id
    WHERE o.user_id = ? AND o.status != 'Selesai'
    ORDER BY o.id DESC LIMIT 1
");
$stmtAktif->bindValue(1, $userId, SQLITE3_INTEGER);
$orderAktif = $stmtAktif->execute()->fetchArray(SQLITE3_ASSOC);

// Riwayat 3 pesanan terakhir
$stmtRiwayat = $db->prepare("
    SELECT o.*, s.nama_layanan, s.harga
    FROM orders o JOIN services s ON o.service_id = s.id
    WHERE o.user_id = ?
    ORDER BY o.id DESC LIMIT 3
");
$stmtRiwayat->bindValue(1, $userId, SQLITE3_INTEGER);
$riwayat = $stmtRiwayat->execute();

// Semua layanan
$services = $db->query("SELECT * FROM services ORDER BY harga ASC");
$svcIcons = ['Basic Cleaning' => 'bi-house-heart', 'Deep Cleaning' => 'bi-stars', 'Office Cleaning' => 'bi-building', 'Bathroom Cleaning' => 'bi-droplet'];
$svcColors = [
  'Basic Cleaning'    => ['bg' => '#eff6ff', 'color' => '#2563eb'],
  'Deep Cleaning'     => ['bg' => '#fdf4ff', 'color' => '#9333ea'],
  'Office Cleaning'   => ['bg' => '#f0fdf4', 'color' => '#16a34a'],
  'Bathroom Cleaning' => ['bg' => '#fff7ed', 'color' => '#ea580c'],
];
?>

<!-- Greeting -->
<div class="greeting-card">
  <div class="greeting-content">
    <div class="greeting-hi">Selamat datang kembali</div>
    <div class="greeting-name">Halo, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</div>
    <div class="greeting-sub">Kelola dan pantau semua pesanan layanan kebersihan Anda di sini.</div>
    <div class="greeting-stats">
      <div>
        <div class="greeting-stat-val"><?= $totalPesanan ?></div>
        <div class="greeting-stat-lbl">Total Pesanan</div>
      </div>
      <div>
        <div class="greeting-stat-val"><?= $pesananAktif ?></div>
        <div class="greeting-stat-lbl">Sedang Berjalan</div>
      </div>
      <div>
        <div class="greeting-stat-val"><?= $pesananSelesai ?></div>
        <div class="greeting-stat-lbl">Selesai</div>
      </div>
    </div>
  </div>
</div>

<?php if ($orderAktif): ?>
<!-- Pesanan Aktif -->
<?php
$s = $orderAktif['status'];
$step1Class = 'done';
$step2Class = $s === 'Petugas Ditugaskan' ? 'active' : ($s === 'Selesai' ? 'done' : '');
$step3Class = $s === 'Selesai' ? 'done' : '';
?>
<div class="active-order-card" style="margin-bottom:1.75rem;">
  <div class="active-order-badge"><span class="pulse"></span> Pesanan Aktif</div>
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
    <div>
      <div style="font-size:1.05rem;font-weight:800;color:var(--u-text);margin-bottom:.25rem;">
        <?= htmlspecialchars($orderAktif['nama_layanan']) ?>
      </div>
      <div style="font-size:.8rem;color:var(--u-muted);">
        <i class="bi bi-calendar3"></i> <?= htmlspecialchars($orderAktif['tanggal']) ?>
        &nbsp;·&nbsp;
        <i class="bi bi-clock"></i> <?= htmlspecialchars($orderAktif['jam']) ?>
        &nbsp;·&nbsp;
        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($orderAktif['alamat'] ?? '-') ?>
      </div>
    </div>
    <div style="font-size:1.1rem;font-weight:800;color:var(--u-primary);">
      Rp <?= number_format((int)$orderAktif['harga'], 0, ',', '.') ?>
    </div>
  </div>

  <div class="order-progress">
    <div class="progress-step done <?= $step1Class ?>">
      <div class="progress-dot"><i class="bi bi-check2" style="font-size:.65rem;"></i></div>
      <div class="progress-label">Dipesan</div>
    </div>
    <div class="progress-step <?= $step2Class ?>">
      <div class="progress-dot">2</div>
      <div class="progress-label">Ditugaskan</div>
    </div>
    <div class="progress-step <?= $step3Class ?>">
      <div class="progress-dot">3</div>
      <div class="progress-label">Selesai</div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="u-grid-2" style="margin-bottom:1.75rem;">

  <!-- Layanan tersedia -->
  <div>
    <div class="section-label"><i class="bi bi-stars"></i> Layanan Tersedia</div>
    <div style="display:flex;flex-direction:column;gap:.75rem;">
      <?php while ($svc = $services->fetchArray(SQLITE3_ASSOC)):
        $icon  = $svcIcons[$svc['nama_layanan']]  ?? 'bi-sparkles';
        $style = $svcColors[$svc['nama_layanan']] ?? ['bg' => '#f1f5f9', 'color' => '#64748b'];
      ?>
      <div style="background:var(--u-surface);border:1.5px solid var(--u-border);border-radius:var(--u-r-lg);padding:.875rem 1rem;display:flex;align-items:center;gap:.875rem;transition:all var(--u-t);"
           onmouseover="this.style.borderColor='#bfdbfe';this.style.transform='translateX(4px)'"
           onmouseout="this.style.borderColor='var(--u-border)';this.style.transform=''">
        <div style="width:38px;height:38px;border-radius:var(--u-r-md);background:<?= $style['bg'] ?>;color:<?= $style['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
          <i class="bi <?= $icon ?>"></i>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:.845rem;font-weight:700;color:var(--u-text);"><?= htmlspecialchars($svc['nama_layanan']) ?></div>
          <div style="font-size:.75rem;color:var(--u-muted);"><?= htmlspecialchars($svc['deskripsi']) ?></div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div style="font-size:.875rem;font-weight:800;color:var(--u-primary);">Rp <?= number_format((int)$svc['harga'],0,',','.') ?></div>
          <a href="booking/create.php?service=<?= (int)$svc['id'] ?>" class="u-btn u-btn-primary u-btn-sm" style="margin-top:.375rem;">
            Pesan
          </a>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Riwayat 3 terakhir -->
  <div>
    <div class="section-label"><i class="bi bi-clock-history"></i> Pesanan Terbaru</div>
    <div class="u-card">
      <div class="u-card-body" style="padding:0 1.25rem;">
        <?php
        $no = 0; $emptyRiwayat = true;
        while ($row = $riwayat->fetchArray(SQLITE3_ASSOC)):
          $emptyRiwayat = false; $no++;
          $st = $row['status'];
          $badgeCls = $st === 'Menunggu Konfirmasi' ? 'u-badge-pending' : ($st === 'Petugas Ditugaskan' ? 'u-badge-assigned' : 'u-badge-done');
          $icon = $svcIcons[$row['nama_layanan']] ?? 'bi-sparkles';
          $style = $svcColors[$row['nama_layanan']] ?? ['bg' => '#f1f5f9', 'color' => '#64748b'];
        ?>
        <div class="order-history-item">
          <div class="order-history-icon" style="background:<?= $style['bg'] ?>;color:<?= $style['color'] ?>;">
            <i class="bi <?= $icon ?>"></i>
          </div>
          <div class="order-history-info">
            <div class="order-history-name"><?= htmlspecialchars($row['nama_layanan']) ?></div>
            <div class="order-history-date">
              <?= htmlspecialchars($row['tanggal']) ?> · <?= htmlspecialchars($row['jam']) ?>
            </div>
            <span class="u-badge <?= $badgeCls ?>" style="margin-top:.3rem;"><?= htmlspecialchars($st) ?></span>
          </div>
          <div class="order-history-price">Rp <?= number_format((int)$row['harga'],0,',','.') ?></div>
        </div>
        <?php endwhile; ?>

        <?php if ($emptyRiwayat): ?>
        <div class="u-empty" style="padding:2rem;">
          <i class="bi bi-inbox"></i>
          <div class="u-empty-title">Belum ada pesanan</div>
          <div class="u-empty-desc">Mulai pesan layanan pertama Anda.</div>
        </div>
        <?php endif; ?>
      </div>
      <?php if (!$emptyRiwayat): ?>
      <div style="padding:.875rem 1.25rem;border-top:1px solid var(--u-border-2);text-align:center;">
        <a href="booking/riwayat.php" class="u-btn u-btn-outline u-btn-sm">
          Lihat Semua Riwayat <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- CTA Banner -->
<div class="cta-banner">
  <div class="cta-banner-text">
    <h3>Siap untuk Rumah yang Lebih Bersih?</h3>
    <p>Pesan layanan sekarang dan petugas profesional kami akan segera hadir.</p>
  </div>
  <a href="booking/create.php" class="cta-banner-btn">
    <i class="bi bi-calendar-plus"></i> Pesan Sekarang
  </a>
</div>

<?php include 'layout/footer.php'; ?>
