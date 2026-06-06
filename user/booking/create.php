<?php
$basePath    = '../../';
$currentPage = 'booking';
$pageTitle   = 'Pesan Layanan';
include '../layout/header.php';

$success = ''; $error = '';

if (isset($_POST['booking'])) {
    $user_id    = (int)$_SESSION['id'];
    $service_id = (int)($_POST['service_id'] ?? 0);
    $tanggal    = $_POST['tanggal'] ?? '';
    $jam        = $_POST['jam'] ?? '';
    $alamat     = trim($_POST['alamat'] ?? '');

    if (!$service_id || empty($tanggal) || empty($jam) || empty($alamat)) {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $stmt = $db->prepare("INSERT INTO orders (user_id,service_id,tanggal,jam,alamat,status) VALUES (?,?,?,?,?,'Menunggu Konfirmasi')");
        $stmt->bindValue(1,$user_id,    SQLITE3_INTEGER);
        $stmt->bindValue(2,$service_id, SQLITE3_INTEGER);
        $stmt->bindValue(3,$tanggal,    SQLITE3_TEXT);
        $stmt->bindValue(4,$jam,        SQLITE3_TEXT);
        $stmt->bindValue(5,$alamat,     SQLITE3_TEXT);
        if ($stmt->execute()) {
            header('Location: /user/booking/riwayat.php?booked=1');
            exit;
        } else {
            $error = 'Booking gagal, coba lagi.';
        }
    }
}

$services = $db->query("SELECT * FROM services ORDER BY harga ASC");
$svcList  = [];
while ($s = $services->fetchArray(SQLITE3_ASSOC)) $svcList[$s['id']] = $s;

$preselect = (int)($_GET['service'] ?? 0);
?>

<?php if ($success): ?>
<div class="u-alert u-alert-success">
  <i class="bi bi-check-circle-fill"></i>
  <div><strong>Berhasil!</strong> <?= $success ?>
    <div style="margin-top:.3rem;"><a href="riwayat.php" style="color:inherit;font-weight:700;text-decoration:underline;">Lihat riwayat →</a></div>
  </div>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="u-alert u-alert-danger"><i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

  <div class="u-card">
    <div class="u-card-header">
      <div class="u-card-title"><i class="bi bi-calendar-plus"></i> Form Pemesanan</div>
    </div>
    <div class="u-card-body">
      <form method="POST" id="bookForm">

        <div style="margin-bottom:1.5rem;">
          <label class="u-form-label">Pilih Jenis Layanan</label>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;">
            <?php foreach ($svcList as $svc):
              $checked = ($preselect == $svc['id'] || (isset($_POST['service_id']) && $_POST['service_id'] == $svc['id']));
            ?>
            <label style="cursor:pointer;">
              <input type="radio" name="service_id" value="<?= (int)$svc['id'] ?>" class="svc-radio" style="display:none;" <?= $checked ? 'checked' : '' ?>>
              <div class="svc-opt" data-harga="<?= (int)$svc['harga'] ?>" data-nama="<?= htmlspecialchars($svc['nama_layanan']) ?>"
                   style="border:1.5px solid var(--u-border);border-radius:var(--u-r-lg);padding:.875rem;transition:all var(--u-t);">
                <div style="font-weight:700;font-size:.845rem;color:var(--u-text);"><?= htmlspecialchars($svc['nama_layanan']) ?></div>
                <div style="font-size:.75rem;color:var(--u-muted);margin:.2rem 0 .5rem;"><?= htmlspecialchars($svc['deskripsi']) ?></div>
                <div style="font-weight:800;color:var(--u-primary);">Rp <?= number_format((int)$svc['harga'],0,',','.') ?></div>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
          <div>
            <label class="u-form-label">Tanggal</label>
            <input type="date" name="tanggal" class="u-input" id="iDate" min="<?= date('Y-m-d') ?>"
                   value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>" required>
          </div>
          <div>
            <label class="u-form-label">Jam Mulai</label>
            <input type="time" name="jam" class="u-input" id="iTime"
                   value="<?= htmlspecialchars($_POST['jam'] ?? '') ?>" required>
          </div>
        </div>

        <div style="margin-bottom:1.75rem;">
          <label class="u-form-label">Alamat Lengkap</label>
          <textarea name="alamat" class="u-input" rows="3" id="iAddr" style="padding:.675rem .9rem;resize:vertical;"
                    placeholder="Jalan, nomor, RT/RW, kelurahan..."
                    required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:.75rem;">
          <button type="submit" name="booking" class="u-btn u-btn-primary u-btn-lg">
            <i class="bi bi-calendar-check"></i> Konfirmasi Pesanan
          </button>
          <a href="riwayat.php" class="u-btn u-btn-outline u-btn-lg">Riwayat</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary -->
  <div class="u-card" style="position:sticky;top:80px;">
    <div class="u-card-header"><div class="u-card-title"><i class="bi bi-receipt"></i> Ringkasan</div></div>
    <div class="u-card-body">
      <div style="display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.25rem;font-size:.845rem;">
        <div style="display:flex;justify-content:space-between;"><span style="color:var(--u-muted);">Layanan</span><span id="sLayanan" style="font-weight:600;color:var(--u-light);">Belum dipilih</span></div>
        <div style="display:flex;justify-content:space-between;"><span style="color:var(--u-muted);">Tanggal</span><span id="sTanggal" style="font-weight:600;color:var(--u-light);">—</span></div>
        <div style="display:flex;justify-content:space-between;"><span style="color:var(--u-muted);">Jam</span><span id="sJam" style="font-weight:600;color:var(--u-light);">—</span></div>
      </div>
      <div style="border-top:2px solid var(--u-border);padding-top:1rem;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;">Total</span>
        <span id="sHarga" style="font-size:1.15rem;font-weight:800;color:var(--u-primary);">Rp 0</span>
      </div>
      <div style="margin-top:1rem;padding:.875rem;background:var(--u-primary-50);border-radius:var(--u-r-lg);font-size:.78rem;color:var(--u-primary);display:flex;gap:.5rem;">
        <i class="bi bi-shield-check" style="flex-shrink:0;margin-top:.1rem;"></i>
        <span>Garansi kepuasan 100% — tidak puas, kami ulangi gratis.</span>
      </div>
    </div>
  </div>

</div>

<style>
.svc-radio:checked + .svc-opt { border-color:var(--u-primary);background:var(--u-primary-50);box-shadow:0 0 0 3px rgba(37,99,235,.12); }
.svc-opt:hover { border-color:#93c5fd; }
</style>
<script>
function upd() {
  const r = document.querySelector('.svc-radio:checked');
  const o = r ? r.nextElementSibling : null;
  const set = (id, val, empty) => { const el = document.getElementById(id); el.textContent = val || empty; el.style.color = (val && val !== '—') ? 'var(--u-text)' : 'var(--u-light)'; };
  set('sLayanan', o ? o.dataset.nama : '', 'Belum dipilih');
  set('sHarga', o ? 'Rp ' + parseInt(o.dataset.harga).toLocaleString('id-ID') : 'Rp 0', 'Rp 0');
  set('sTanggal', document.getElementById('iDate').value, '—');
  set('sJam', document.getElementById('iTime').value, '—');
}
document.querySelectorAll('.svc-radio').forEach(r => r.addEventListener('change', upd));
document.getElementById('iDate').addEventListener('input', upd);
document.getElementById('iTime').addEventListener('input', upd);
upd();
</script>

<?php include '../layout/footer.php'; ?>
