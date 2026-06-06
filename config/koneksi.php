<?php
// Di Railway: salin DB seed ke /tmp agar data bertahan & writable
// Di localhost: pakai path database/ biasa
$srcPath = __DIR__ . '/../database/cleanspace.db';
$tmpPath = '/tmp/cleanspace.db';

if (getenv('RAILWAY_ENVIRONMENT') !== false) {
    // Pakai /tmp — salin dari git jika belum ada di sana
    if (!file_exists($tmpPath) && file_exists($srcPath)) {
        copy($srcPath, $tmpPath);
    }
    $dbPath = $tmpPath;
} else {
    $dbPath = $srcPath;
    if (!is_dir(dirname($dbPath))) {
        mkdir(dirname($dbPath), 0777, true);
    }
}

$db = new SQLite3($dbPath);
$db->enableExceptions(true);

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    nama     TEXT NOT NULL,
    email    TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role     TEXT DEFAULT 'user'
)");

$db->exec("CREATE TABLE IF NOT EXISTS services (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_layanan TEXT NOT NULL,
    harga        TEXT,
    deskripsi    TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS workers (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_petugas TEXT NOT NULL,
    nomor_hp     TEXT,
    status       TEXT DEFAULT 'Available'
)");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id    INTEGER,
    service_id INTEGER,
    worker_id  INTEGER,
    tanggal    TEXT,
    jam        TEXT,
    alamat     TEXT,
    status     TEXT DEFAULT 'Menunggu Konfirmasi'
)");

// Seed akun admin jika belum ada
if (!$db->querySingle("SELECT id FROM users WHERE email = 'admin@cleanspace.com'")) {
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, 'Admin',                SQLITE3_TEXT);
    $stmt->bindValue(2, 'admin@cleanspace.com', SQLITE3_TEXT);
    $stmt->bindValue(3, $adminPass,             SQLITE3_TEXT);
    $stmt->bindValue(4, 'admin',                SQLITE3_TEXT);
    $stmt->execute();
}

// Seed akun demo user jika belum ada
if (!$db->querySingle("SELECT id FROM users WHERE email = 'user@cleanspace.com'")) {
    $userPass = password_hash('user123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, 'Budi Santoso',        SQLITE3_TEXT);
    $stmt->bindValue(2, 'user@cleanspace.com', SQLITE3_TEXT);
    $stmt->bindValue(3, $userPass,             SQLITE3_TEXT);
    $stmt->bindValue(4, 'user',                SQLITE3_TEXT);
    $stmt->execute();
}

// Seed services jika belum ada
if ($db->querySingle("SELECT COUNT(*) FROM services") == 0) {
    $db->exec("INSERT INTO services (nama_layanan, harga, deskripsi) VALUES
        ('Basic Cleaning',    '100000', 'Pembersihan dasar rumah'),
        ('Deep Cleaning',     '250000', 'Pembersihan menyeluruh'),
        ('Office Cleaning',   '300000', 'Pembersihan kantor'),
        ('Bathroom Cleaning', '150000', 'Pembersihan kamar mandi')
    ");
}

// Seed workers jika belum ada
if ($db->querySingle("SELECT COUNT(*) FROM workers") == 0) {
    $db->exec("INSERT INTO workers (nama_petugas, nomor_hp, status) VALUES
        ('Budi',  '08123456789', 'Available'),
        ('Andi',  '08123456780', 'Available'),
        ('Siti',  '08123456781', 'Available')
    ");
}
