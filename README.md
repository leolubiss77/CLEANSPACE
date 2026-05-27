# CleanSpace — Aplikasi Layanan Kebersihan Berbasis Web

CleanSpace adalah aplikasi web untuk pemesanan jasa kebersihan rumah dan kantor secara online. Dibangun menggunakan PHP native, SQLite, dan Bootstrap 5.

---

## Daftar Isi

1. [Fitur Aplikasi](#fitur-aplikasi)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Struktur Folder](#struktur-folder)
4. [Alur Penggunaan](#alur-penggunaan)
5. [Skema Database](#skema-database)
6. [Cara Menjalankan Secara Lokal](#cara-menjalankan-secara-lokal)
7. [Akun Default](#akun-default)
8. [Link Demo](#link-demo)

---

## Fitur Aplikasi

### Pengguna (User)
- Registrasi dan login akun
- Memesan layanan kebersihan (pilih jenis layanan, tanggal, jam, dan alamat)
- Melihat riwayat pesanan beserta status terkini

### Admin
- Melihat semua pesanan yang masuk beserta statistik ringkasan
- Menugaskan petugas kebersihan ke pesanan tertentu
- Menandai pesanan sebagai selesai

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP 8 | Bahasa pemrograman backend |
| SQLite 3 | Database ringan berbasis file |
| Bootstrap 5.3 | Framework CSS untuk tampilan responsif |
| Bootstrap Icons | Ikon antarmuka |
| HTML5 & CSS3 | Struktur dan gaya halaman |
| Railway | Platform hosting cloud gratis |

---

## Struktur Folder

```
CLEANSPACE/
│
├── index.php               # Landing page (halaman utama publik)
├── style.css               # Custom CSS tambahan
├── composer.json           # Konfigurasi PHP project (untuk Railway)
├── Procfile                # Perintah start server untuk Railway
│
├── auth/
│   ├── login.php           # Halaman login
│   ├── register.php        # Halaman registrasi
│   └── logout.php          # Proses logout (hapus session)
│
├── user/
│   ├── booking.php         # Form pemesanan layanan
│   └── history.php         # Riwayat pesanan pengguna
│
├── admin/
│   ├── dashboard.php       # Dashboard admin dengan statistik & tabel pesanan
│   ├── assign_worker.php   # Form penugasan petugas ke pesanan
│   └── update_status.php   # Halaman konfirmasi tandai pesanan selesai
│
├── config/
│   ├── koneksi.php         # Koneksi database + inisialisasi tabel otomatis
│   ├── header.php          # Template navbar (dipakai semua halaman)
│   └── footer.php          # Template footer (dipakai semua halaman)
│
└── database/
    └── cleanspace.db       # File database SQLite
```

---

## Alur Penggunaan

### Alur User
```
Landing Page → Register → Login → Booking Layanan → Lihat Riwayat Pesanan
```

### Alur Admin
```
Login (admin) → Dashboard → Assign Petugas → Tandai Selesai
```

### Status Pesanan
```
Menunggu Konfirmasi  →  Petugas Ditugaskan  →  Selesai
```

---

## Skema Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INTEGER | Primary key, auto increment |
| nama | TEXT | Nama lengkap pengguna |
| email | TEXT | Email unik pengguna |
| password | TEXT | Password ter-hash (bcrypt) |
| role | TEXT | `user` atau `admin` |

### Tabel `services`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INTEGER | Primary key, auto increment |
| nama_layanan | TEXT | Nama jenis layanan |
| harga | TEXT | Harga layanan (dalam Rupiah) |
| deskripsi | TEXT | Deskripsi singkat layanan |

### Tabel `workers`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INTEGER | Primary key, auto increment |
| nama_petugas | TEXT | Nama petugas kebersihan |
| nomor_hp | TEXT | Nomor HP petugas |
| status | TEXT | `Available` atau lainnya |

### Tabel `orders`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INTEGER | Primary key, auto increment |
| user_id | INTEGER | Relasi ke tabel `users` |
| service_id | INTEGER | Relasi ke tabel `services` |
| worker_id | INTEGER | Relasi ke tabel `workers` (setelah assign) |
| tanggal | TEXT | Tanggal layanan |
| jam | TEXT | Jam layanan |
| alamat | TEXT | Alamat tempat layanan |
| status | TEXT | Status pesanan saat ini |

---

## Cara Menjalankan Secara Lokal

### Prasyarat
- PHP 8.0 atau lebih baru
- Ekstensi SQLite3 aktif

### Langkah-langkah

**1. Clone atau download project ini**
```bash
cd /lokasi/project
```

**2. Jalankan PHP built-in server**
```bash
php -S localhost:8888
```

**3. Buka di browser**
```
http://localhost:8888/index.php
```

---

## Akun Default

Akun berikut tersedia secara otomatis saat pertama kali aplikasi dijalankan:

| Role | Email | Password |
|---|---|---|
| Admin | admin@cleanspace.com | admin123 |

Untuk akun user, silakan daftar melalui halaman **Register**.

---

## Fitur Keamanan

- Password disimpan menggunakan **bcrypt** (`password_hash` PHP)
- Semua input divalidasi dan di-escape menggunakan `htmlspecialchars()`
- Query database menggunakan **prepared statement** untuk mencegah SQL Injection
- Halaman user dan admin dilindungi dengan pengecekan session
- Halaman admin memeriksa role `admin` sebelum memberikan akses

---

## Link Demo

🌐 **https://cleanspace-production-b05d.up.railway.app**

---

## Data Layanan yang Tersedia

| Layanan | Harga |
|---|---|
| Basic Cleaning | Rp 100.000 |
| Deep Cleaning | Rp 250.000 |
| Office Cleaning | Rp 300.000 |
| Bathroom Cleaning | Rp 150.000 |

---

*Dibuat sebagai proyek pembelajaran pengembangan web dengan PHP dan SQLite.*
