<?php
$dbPath = __DIR__ . '/../database/cleanspace.db';

if (!is_dir(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0777, true);
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

// Seed data awal jika database kosong
if ($db->querySingle("SELECT COUNT(*) FROM users") == 0) {
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, 'Admin',                 SQLITE3_TEXT);
    $stmt->bindValue(2, 'admin@cleanspace.com',  SQLITE3_TEXT);
    $stmt->bindValue(3, $adminPass,              SQLITE3_TEXT);
    $stmt->bindValue(4, 'admin',                 SQLITE3_TEXT);
    $stmt->execute();

    $db->exec("INSERT INTO services (nama_layanan, harga, deskripsi) VALUES
        ('Basic Cleaning',    '100000', 'Pembersihan dasar rumah'),
        ('Deep Cleaning',     '250000', 'Pembersihan menyeluruh'),
        ('Office Cleaning',   '300000', 'Pembersihan kantor'),
        ('Bathroom Cleaning', '150000', 'Pembersihan kamar mandi')
    ");

    $db->exec("INSERT INTO workers (nama_petugas, nomor_hp, status) VALUES
        ('Budi', '08123456789', 'Available'),
        ('Andi', '08123456780', 'Available'),
        ('Siti', '08123456781', 'Available')
    ");
}
