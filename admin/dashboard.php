<?php
$basePath    = '../';
$currentPage = 'dashboard';
$pageTitle   = 'Dashboard';
include 'layout/header.php';

// Stats
$totalPesanan   = (int)$db->querySingle("SELECT COUNT(*) FROM orders");
$pendapatan     = (int)$db->querySingle("SELECT COALESCE(SUM(s.harga),0) FROM orders o JOIN services s ON o.service_id=s.id WHERE o.status='Selesai'");
$petugasAktif   = (int)$db->querySingle("SELECT COUNT(*) FROM workers WHERE status='Available'");
$totalPelanggan = (int)$db->querySingle("SELECT COUNT(*) FROM users WHERE role='user'");

// Chart data
$pending  = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE status='Menunggu Konfirmasi'");
$assigned = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE status='Petugas Ditugaskan'");
$done     = (int)$db->querySingle("SELECT COUNT(*) FROM orders WHERE status='Selesai'");
$maxBar   = max($pending, $assigned, $done, 1);

// Recent orders
$recentOrders = $db->query("
    SELECT o.*, u.nama AS nama_user, s.nama_layanan
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    ORDER BY o.id DESC LIMIT 8
");
?>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card stat-blue">
    <div class="stat-card-icon"><i class="bi bi-receipt-cutoff"></i></div>
    <div class="stat-card-value"><?= $totalPesanan ?></div>
    <div class="stat-card-label">Total Pesanan</div>
    <div class="stat-card-trend"><i class="bi bi-arrow-up-right"></i> Semua waktu</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-card-icon"><i class="bi bi-cash-stack"></i></div>
    <div class="stat-card-value">Rp <?= number_format($pendapatan/1000, 0, ',', '.') ?>k</div>
    <div class="stat-card-label">Pendapatan</div>
    <div class="stat-card-trend"><i class="bi bi-check-circle"></i> Dari pesanan selesai</div>
  </div>
  <div class="stat-card stat-orange">
    <div class="stat-card-icon"><i class="bi bi-person-badge"></i></div>
    <div class="stat-card-value"><?= $petugasAktif ?></div>
    <div class="stat-card-label">Petugas Aktif</div>
    <div class="stat-card-trend"><i class="bi bi-circle-fill" style="font-size:.5rem;"></i> Siap bertugas</div>
  </div>
  <div class="stat-card stat-purple">
    <div class="stat-card-icon"><i class="bi bi-people"></i></div>
    <div class="stat-card-value"><?= $totalPelanggan ?></div>
    <div class="stat-card-label">Total Pelanggan</div>
    <div class="stat-card-trend"><i class="bi bi-person-plus"></i> Terdaftar</div>
  </div>
</div>

<?php if (isset($_GET['assigned'])): ?>
<div class="admin-alert alert-success"><i class="bi bi-check-circle-fill"></i><span>Petugas berhasil ditugaskan.</span></div>
<?php endif; ?>
<?php if (isset($_GET['done'])): ?>
<div class="admin-alert alert-success"><i class="bi bi-check-circle-fill"></i><span>Pesanan berhasil ditandai selesai.</span></div>
<?php endif; ?>

<div class="grid-2" style="margin-bottom:1.5rem;">

  <!-- Chart -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-bar-chart-line"></i> Status Pesanan</div>
      <span style="font-size:.75rem;color:var(--admin-muted);"><?= $totalPesanan ?> total</span>
    </div>
    <div class="admin-card-body">
      <canvas id="orderChart" height="180"></canvas>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-lightning-charge"></i> Aksi Cepat</div>
    </div>
    <div class="admin-card-body">
      <div class="quick-actions">
        <a href="pesanan/index.php" class="quick-action-card">
          <div class="qa-icon" style="background:#eff6ff;color:#2563eb;">
            <i class="bi bi-receipt"></i>
          </div>
          Kelola Pesanan
        </a>
        <a href="layanan/index.php" class="quick-action-card">
          <div class="qa-icon" style="background:#f0fdf4;color:#10b981;">
            <i class="bi bi-stars"></i>
          </div>
          Tambah Layanan
        </a>
        <a href="petugas/index.php" class="quick-action-card">
          <div class="qa-icon" style="background:#fefce8;color:#d97706;">
            <i class="bi bi-person-badge"></i>
          </div>
          Atur Petugas
        </a>
        <a href="pelanggan/index.php" class="quick-action-card">
          <div class="qa-icon" style="background:#fdf4ff;color:#9333ea;">
            <i class="bi bi-people"></i>
          </div>
          Pelanggan
        </a>
        <a href="laporan/index.php" class="quick-action-card">
          <div class="qa-icon" style="background:#fff7ed;color:#ea580c;">
            <i class="bi bi-graph-up"></i>
          </div>
          Laporan
        </a>
      </div>

      <!-- Status summary mini -->
      <hr class="divider">
      <div style="display:flex;gap:1rem;flex-wrap:wrap;">
        <div style="flex:1;min-width:80px;background:#fef9c3;border-radius:var(--r-lg);padding:.875rem;text-align:center;">
          <div style="font-size:1.5rem;font-weight:800;color:#854d0e;"><?= $pending ?></div>
          <div style="font-size:.7rem;font-weight:600;color:#a16207;margin-top:.2rem;">Menunggu</div>
        </div>
        <div style="flex:1;min-width:80px;background:#dbeafe;border-radius:var(--r-lg);padding:.875rem;text-align:center;">
          <div style="font-size:1.5rem;font-weight:800;color:#1d4ed8;"><?= $assigned ?></div>
          <div style="font-size:.7rem;font-weight:600;color:#1e40af;margin-top:.2rem;">Ditugaskan</div>
        </div>
        <div style="flex:1;min-width:80px;background:#d1fae5;border-radius:var(--r-lg);padding:.875rem;text-align:center;">
          <div style="font-size:1.5rem;font-weight:800;color:#065f46;"><?= $done ?></div>
          <div style="font-size:.7rem;font-weight:600;color:#047857;margin-top:.2rem;">Selesai</div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Recent Orders Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="bi bi-table"></i> Pesanan Terbaru</div>
    <a href="pesanan/index.php" class="btn btn-outline btn-sm">
      Lihat Semua <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Layanan</th>
          <th>Jadwal</th>
          <th>Alamat</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1; $empty = true;
        while ($row = $recentOrders->fetchArray(SQLITE3_ASSOC)):
          $empty = false;
          $s = $row['status'];
          $cls = $s === 'Menunggu Konfirmasi' ? 'badge-pending' : ($s === 'Petugas Ditugaskan' ? 'badge-assigned' : 'badge-done');
        ?>
        <tr>
          <td style="color:var(--admin-light);font-size:.78rem;"><?= $no++ ?></td>
          <td>
            <div class="user-cell">
              <div class="user-cell-avatar"><?= strtoupper(mb_substr($row['nama_user'],0,1)) ?></div>
              <div class="user-cell-name"><?= htmlspecialchars($row['nama_user']) ?></div>
            </div>
          </td>
          <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
          <td>
            <div style="font-weight:600;"><?= htmlspecialchars($row['tanggal']) ?></div>
            <div style="font-size:.75rem;color:var(--admin-muted);"><?= htmlspecialchars($row['jam']) ?></div>
          </td>
          <td style="max-width:150px;">
            <div style="font-size:.78rem;color:var(--admin-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              <?= htmlspecialchars($row['alamat'] ?? '-') ?>
            </div>
          </td>
          <td><span class="badge-status <?= $cls ?>"><?= htmlspecialchars($s) ?></span></td>
          <td>
            <div style="display:flex;gap:.375rem;">
              <a href="assign_worker.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline btn-sm">
                <i class="bi bi-person-plus"></i>
              </a>
              <?php if ($s !== 'Selesai'): ?>
              <a href="update_status.php?id=<?= (int)$row['id'] ?>" class="btn btn-success btn-sm">
                <i class="bi bi-check2"></i>
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if ($empty): ?>
        <tr><td colspan="7">
          <div class="empty-state">
            <i class="bi bi-inbox empty-state-icon"></i>
            <div class="empty-state-title">Belum ada pesanan</div>
            <div class="empty-state-desc">Pesanan dari pelanggan akan muncul di sini.</div>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$adminPageScript = "
const ctx = document.getElementById('orderChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Menunggu', 'Ditugaskan', 'Selesai'],
    datasets: [{
      data: [{$pending}, {$assigned}, {$done}],
      backgroundColor: ['#fef9c3', '#dbeafe', '#d1fae5'],
      borderColor:     ['#d97706',  '#2563eb',  '#10b981'],
      borderWidth: 2,
      borderRadius: 8,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1, font: { family: 'Inter', size: 11 } },
        grid: { color: '#f1f5f9' }
      },
      x: {
        ticks: { font: { family: 'Inter', size: 11 } },
        grid: { display: false }
      }
    }
  }
});
";
include 'layout/footer.php';
?>
