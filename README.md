# CleanSpace — Platform Layanan Kebersihan Profesional

CleanSpace adalah sistem manajemen layanan cleaning service berbasis web dengan tampilan modern sekelas SaaS profesional. Dibangun menggunakan PHP native, SQLite, dan custom design system tanpa framework CSS eksternal.

---

## Identitas Tim

| NIM | Nama | Peran |
|---|---|---|
| 2409010266 | *(nama)* | Project Manager / Full Stack Developer |
| 2409010237 | *(nama)* | Backend Developer & Database Designer |
| 2409010228 | *(nama)* | Frontend Developer & UI/UX Designer |
| 2409010256 | *(nama)* | System Analyst & Documentation |
| 2409010233 | *(nama)* | Tester, Deployment & Quality Assurance |

---

## Link Penting

| | Link |
|---|---|
| **Live Demo** | https://cleanspace-production-b05d.up.railway.app |
| **Repository** | https://github.com/leolubiss77/CLEANSPACE |

---

## Daftar Isi

1. [Fitur Aplikasi](#fitur-aplikasi)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Struktur Folder](#struktur-folder)
4. [Alur Penggunaan](#alur-penggunaan)
5. [Skema Database](#skema-database)
6. [Cara Menjalankan Secara Lokal](#cara-menjalankan-secara-lokal)
7. [Akun Demo](#akun-demo)
8. [Fitur Keamanan](#fitur-keamanan)
9. [Data Layanan](#data-layanan)

---

## Fitur Aplikasi

### Halaman Publik (Landing Page)
- Navbar sticky dengan efek glassmorphism saat di-scroll
- Hero section dengan animasi gradient dan floating card
- Section social proof, fitur unggulan, cara kerja
- Pricing section 3 tier (Basic, Pro, Premium)
- Testimonial grid dengan avatar dan rating bintang
- FAQ accordion interaktif
- CTA section dan footer lengkap

### Pengguna (User)
- Registrasi akun dengan password strength indicator
- Login dengan split layout dan toggle show/hide password
- Booking layanan: pilih layanan via card visual, tanggal, jam, alamat
- Preview ringkasan pesanan real-time di sidebar
- Riwayat pesanan dengan status badge berwarna

### Admin
- Dashboard dengan stat cards (Total, Menunggu, Ditugaskan, Selesai)
- Tabel semua pesanan dengan avatar inisial pelanggan
- Assign petugas ke pesanan via card visual
- Tandai pesanan sebagai selesai dengan halaman konfirmasi

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP 8 | Bahasa pemrograman backend (server-side) |
| SQLite 3 | Database ringan berbasis file |
| CSS Variables & Custom Design System | Design system konsisten tanpa framework eksternal |
| Bootstrap Icons 1.11 | Ikon antarmuka pengguna |
| Google Fonts (Inter) | Tipografi modern |
| Vanilla JavaScript | Interaktivitas (navbar scroll, FAQ, booking summary) |
| Railway | Platform cloud hosting & deployment |

---

## Struktur Folder

```
CLEANSPACE/
│
├── index.php                   # Landing page publik (redesign SaaS)
├── composer.json               # Konfigurasi PHP project
├── Procfile                    # Perintah start server untuk Railway
│
├── assets/
│   └── css/
│       └── style.css           # Design system lengkap (CSS Variables, animasi)
│
├── auth/
│   ├── login.php               # Login dengan split layout
│   ├── register.php            # Registrasi dengan password strength indicator
│   └── logout.php              # Proses logout
│
├── user/
│   ├── booking.php             # Form booking dengan service card & summary sidebar
│   └── history.php             # Riwayat pesanan pengguna
│
├── admin/
│   ├── dashboard.php           # Dashboard admin dengan stat cards & tabel
│   ├── assign_worker.php       # Assign petugas dengan card visual
│   └── update_status.php       # Konfirmasi tandai pesanan selesai
│
├── config/
│   ├── koneksi.php             # Koneksi database + seed data otomatis
│   ├── header.php              # Layout sidebar + topbar (semua halaman dalam)
│   └── footer.php              # Penutup layout
│
└── database/
    └── cleanspace.db           # File database SQLite
```

---

## Alur Penggunaan

### Alur User
```
Landing Page → Register → Login → Booking Layanan → Lihat Riwayat
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
- Ekstensi SQLite3 aktif (biasanya sudah aktif by default)

### Langkah-langkah

**1. Clone repository**
```bash
git clone https://github.com/leolubiss77/CLEANSPACE.git
cd CLEANSPACE
```

**2. Jalankan PHP built-in server**
```bash
php -S localhost:8888
```

**3. Buka di browser**
```
http://localhost:8888
```

Database dan akun demo akan dibuat otomatis saat aplikasi pertama kali dijalankan.

---

## Akun Demo

Akun berikut tersedia secara otomatis saat pertama kali aplikasi dijalankan:

| Role | Email | Password |
|---|---|---|
| Admin | admin@cleanspace.com | admin123 |
| User | user@cleanspace.com | user123 |

Untuk akun user tambahan, daftar melalui halaman **Register**.

---

## Fitur Keamanan

| Fitur | Implementasi |
|---|---|
| Enkripsi password | `password_hash()` dengan algoritma **bcrypt** |
| Pencegahan SQL Injection | Seluruh query menggunakan **prepared statement** |
| Pencegahan XSS | Semua output melalui `htmlspecialchars()` |
| Proteksi halaman | Pengecekan **session** di setiap halaman |
| Pembatasan akses admin | Verifikasi `$_SESSION['role'] === 'admin'` |

---

## Data Layanan

| Layanan | Harga | Deskripsi |
|---|---|---|
| Basic Cleaning | Rp 100.000 | Pembersihan dasar rumah |
| Bathroom Cleaning | Rp 150.000 | Pembersihan kamar mandi |
| Deep Cleaning | Rp 250.000 | Pembersihan menyeluruh |
| Office Cleaning | Rp 300.000 | Pembersihan kantor |

---

*Proyek ini dibuat sebagai tugas akhir mata kuliah Pemrograman Web — Tim CleanSpace.*
