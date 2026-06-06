<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$success = '';
$error   = '';

if (isset($_POST['booking'])) {
    $user_id    = (int)$_SESSION['id'];
    $service_id = (int)($_POST['service_id'] ?? 0);
    $tanggal    = $_POST['tanggal'] ?? '';
    $jam        = $_POST['jam'] ?? '';
    $alamat     = trim($_POST['alamat'] ?? '');

    if (!$service_id || empty($tanggal) || empty($jam) || empty($alamat)) {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, service_id, tanggal, jam, alamat, status)
            VALUES (?, ?, ?, ?, ?, 'Menunggu Konfirmasi')
        ");
        $stmt->bindValue(1, $user_id,    SQLITE3_INTEGER);
        $stmt->bindValue(2, $service_id, SQLITE3_INTEGER);
        $stmt->bindValue(3, $tanggal,    SQLITE3_TEXT);
        $stmt->bindValue(4, $jam,        SQLITE3_TEXT);
        $stmt->bindValue(5, $alamat,     SQLITE3_TEXT);

        if ($stmt->execute()) {
            $success = 'Booking berhasil! Pesanan Anda sedang menunggu konfirmasi.';
            $_POST   = [];
        } else {
            $error = 'Booking gagal. Silakan coba lagi.';
        }
    }
}

// Fetch services with harga
$services = $db->query("SELECT * FROM services ORDER BY nama_layanan");
$svcList  = [];
while ($s = $services->fetchArray(SQLITE3_ASSOC)) {
    $svcList[$s['id']] = $s;
}

$pageTitle    = 'Booking Layanan';
$pageSubtitle = 'Pesan layanan kebersihan profesional';
$basePath     = '../';
include '../config/header.php';
?>

<div style="max-width:860px;">

<?php if ($success): ?>
<div class="cs-alert cs-alert-success mb-4">
  <i class="bi bi-check-circle-fill"></i>
  <div>
    <strong>Berhasil!</strong> <?= htmlspecialchars($success) ?>
    <div style="margin-top:.375rem;">
      <a href="history.php" style="color:inherit;font-weight:700;text-decoration:underline;">Lihat riwayat pesanan →</a>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="cs-alert cs-alert-danger mb-4">
  <i class="bi bi-exclamation-circle-fill"></i>
  <span><?= htmlspecialchars($error) ?></span>
</div>
<?php endif; ?>

<!-- Step indicator -->
<div class="step-indicator">
  <div class="step-dot done">
    <div class="step-circle"><i class="bi bi-check2" style="font-size:.7rem;"></i></div>
    <div class="step-label">Layanan</div>
  </div>
  <div class="step-dot active">
    <div class="step-circle">2</div>
    <div class="step-label">Jadwal</div>
  </div>
  <div class="step-dot">
    <div class="step-circle">3</div>
    <div class="step-label">Alamat</div>
  </div>
  <div class="step-dot">
    <div class="step-circle">4</div>
    <div class="step-label">Konfirmasi</div>
  </div>
</div>

<div class="booking-layout">
  <!-- Form -->
  <div class="cs-card">
    <div class="cs-card-header">
      <div class="cs-card-title"><i class="bi bi-calendar-plus" style="color:var(--c-primary);margin-right:.5rem;"></i>Detail Pesanan</div>
    </div>
    <div class="cs-card-body">
      <form method="POST" id="bookingForm">

        <!-- Pilih Layanan -->
        <div style="margin-bottom:1.5rem;">
          <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-dark-3);margin-bottom:.75rem;">
            Pilih Jenis Layanan
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem;" id="serviceCards">
            <?php foreach ($svcList as $svc): ?>
            <label style="cursor:pointer;">
              <input type="radio" name="service_id" value="<?= (int)$svc['id'] ?>"
                     class="service-radio" style="display:none;"
                     <?= (isset($_POST['service_id']) && $_POST['service_id'] == $svc['id']) ? 'checked' : '' ?>>
              <div class="service-card-opt" data-harga="<?= (int)$svc['harga'] ?>" data-nama="<?= htmlspecialchars($svc['nama_layanan']) ?>">
                <div style="font-weight:700;font-size:.875rem;color:var(--c-dark);"><?= htmlspecialchars($svc['nama_layanan']) ?></div>
                <div style="font-size:.75rem;color:var(--c-gray);margin-top:.2rem;"><?= htmlspecialchars($svc['deskripsi']) ?></div>
                <div style="margin-top:.625rem;font-size:.95rem;font-weight:800;color:var(--c-primary);">
                  Rp <?= number_format((int)$svc['harga'], 0, ',', '.') ?>
                </div>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <hr class="divider" style="margin:1.5rem 0;">

        <!-- Jadwal -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
          <div>
            <label style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-dark-3);display:block;margin-bottom:.4rem;">
              Tanggal Layanan
            </label>
            <div class="input-wrap">
              <i class="bi bi-calendar3 input-icon"></i>
              <input type="date" name="tanggal" class="form-input"
                     min="<?= date('Y-m-d') ?>"
                     value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>"
                     id="inputTanggal" required>
            </div>
          </div>
          <div>
            <label style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-dark-3);display:block;margin-bottom:.4rem;">
              Jam Mulai
            </label>
            <div class="input-wrap">
              <i class="bi bi-clock input-icon"></i>
              <input type="time" name="jam" class="form-input"
                     value="<?= htmlspecialchars($_POST['jam'] ?? '') ?>"
                     id="inputJam" required>
            </div>
          </div>
        </div>

        <!-- Alamat -->
        <div style="margin-bottom:1.75rem;">
          <label style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-dark-3);display:block;margin-bottom:.4rem;">
            Alamat Lengkap
          </label>
          <textarea name="alamat" class="form-input" rows="3"
                    style="resize:vertical;padding-left:.875rem;"
                    placeholder="Masukkan alamat lengkap tempat layanan (jalan, nomor, RT/RW, kelurahan)..."
                    id="inputAlamat" required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:.75rem;">
          <button type="submit" name="booking" class="btn btn-primary-gradient btn-lg">
            <i class="bi bi-calendar-check"></i> Konfirmasi Pesanan
          </button>
          <a href="history.php" class="btn btn-outline btn-lg">
            <i class="bi bi-clock-history"></i> Riwayat
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary Sidebar -->
  <div class="booking-summary">
    <div class="summary-title">Ringkasan Pesanan</div>

    <div class="summary-row">
      <span class="label">Layanan</span>
      <span class="value" id="sumLayanan" style="color:var(--c-gray-light);">Belum dipilih</span>
    </div>
    <div class="summary-row">
      <span class="label">Tanggal</span>
      <span class="value" id="sumTanggal" style="color:var(--c-gray-light);">—</span>
    </div>
    <div class="summary-row">
      <span class="label">Jam</span>
      <span class="value" id="sumJam" style="color:var(--c-gray-light);">—</span>
    </div>
    <div class="summary-row">
      <span class="label">Alamat</span>
      <span class="value" id="sumAlamat" style="color:var(--c-gray-light);">—</span>
    </div>
    <div class="summary-total">
      <span>Total</span>
      <span class="total-price" id="sumHarga">Rp 0</span>
    </div>

    <div style="margin-top:1.25rem;padding:.875rem;background:var(--c-primary-50);border-radius:var(--radius-lg);border:1px solid var(--c-primary-100);">
      <div style="display:flex;gap:.5rem;font-size:.8rem;color:var(--c-primary);">
        <i class="bi bi-shield-check" style="flex-shrink:0;margin-top:.1rem;"></i>
        <span>Dilindungi garansi kepuasan 100%. Tidak puas, kami ulangi gratis.</span>
      </div>
    </div>
  </div>
</div>

</div>

<style>
.service-card-opt {
  border: 1.5px solid var(--c-border);
  border-radius: var(--radius-lg);
  padding: .875rem;
  transition: all var(--transition);
  background: var(--c-white);
}
.service-card-opt:hover { border-color: var(--c-primary-light); background: var(--c-primary-50); }
.service-radio:checked + .service-card-opt {
  border-color: var(--c-primary);
  background: var(--c-primary-50);
  box-shadow: 0 0 0 3px rgba(37,99,235,.15);
}
.form-input {
  width:100%;padding:.7rem .875rem .7rem 2.6rem;
  border:1.5px solid var(--c-border);border-radius:var(--radius-md);
  font-family:var(--font);font-size:var(--text-sm);color:var(--c-dark);
  background:var(--c-white);transition:all var(--transition);outline:none;
}
.form-input:focus { border-color:var(--c-primary);box-shadow:0 0 0 3px rgba(37,99,235,.12); }
textarea.form-input { padding-left:.875rem; }
.input-wrap { position:relative; }
.input-wrap .input-icon {
  position:absolute;left:.875rem;top:50%;transform:translateY(-50%);
  color:var(--c-gray-light);font-size:1rem;pointer-events:none;
}
</style>

<script>
function fmt(n) {
  return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}

function updateSummary() {
  const checked = document.querySelector('.service-radio:checked');
  const opt     = checked ? checked.nextElementSibling : null;

  document.getElementById('sumLayanan').textContent = opt ? opt.dataset.nama  : 'Belum dipilih';
  document.getElementById('sumHarga').textContent   = opt ? fmt(opt.dataset.harga) : 'Rp 0';

  const tgl = document.getElementById('inputTanggal').value;
  const jam = document.getElementById('inputJam').value;
  const alm = document.getElementById('inputAlamat').value.trim();

  document.getElementById('sumTanggal').textContent = tgl || '—';
  document.getElementById('sumJam').textContent     = jam || '—';
  document.getElementById('sumAlamat').textContent  = alm ? (alm.length > 40 ? alm.slice(0,40)+'…' : alm) : '—';

  const fields = ['sumLayanan','sumTanggal','sumJam','sumAlamat'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    el.style.color = el.textContent === 'Belum dipilih' || el.textContent === '—' ? 'var(--c-gray-light)' : 'var(--c-dark)';
  });
}

document.querySelectorAll('.service-radio').forEach(r => r.addEventListener('change', updateSummary));
document.getElementById('inputTanggal').addEventListener('input', updateSummary);
document.getElementById('inputJam').addEventListener('input', updateSummary);
document.getElementById('inputAlamat').addEventListener('input', updateSummary);
updateSummary();
</script>

<?php include '../config/footer.php'; ?>
