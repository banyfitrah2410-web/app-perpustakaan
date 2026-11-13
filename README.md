# 📚 Sistem Perpustakaan Digital Kampus

Aplikasi web manajemen perpustakaan modern berbasis **Laravel 12**, dilengkapi dengan autentikasi berbasis role (Admin & Petugas), relasi database transaksi peminjaman multi-buku, dedicated REST API Resource, dan konsumsi API internal via Laravel HTTP Client.

---

## 🌟 Fitur Utama

- **Autentikasi & Otorisasi:**
  - Login & Logout dengan proteksi session.
  - Multi-role: `admin` (akses penuh termasuk CRUD kategori) dan `petugas` (manajemen buku, anggota, dan sirkulasi peminjaman).
  - Profil pengguna & fitur ganti password mandiri.
- **Manajemen Data (CRUD):**
  - Kategori Buku (khusus Admin).
  - Data Buku lengkap dengan relasi kategori, stok, ISBN, dan pagination.
  - Data Anggota Perpustakaan dengan status aktif/nonaktif.
- **Sirkulasi Peminjaman (Eloquent Relationships):**
  - Transaksi peminjaman multi-buku dalam satu transaksi (`Loan` & `LoanItem`).
  - Alur pengembalian buku dengan pencatatan tanggal dikembalikan otomatis.
- **REST API & API Resource:**
  - Dedicated API Controller di `app/Http/Controllers/Api/` dengan format response seragam via `JsonResource`.
  - Endpoint lengkap untuk `books`, `members`, `loans`, dan `stats`.
- **Konsumsi API Internal (BFF Pattern):**
  - **Dashboard:** Menampilkan kartu statistik dari `GET /api/stats`.
  - **Laporan:** Menampilkan data transaksi peminjaman dari `GET /api/loans`.
  - Dilengkapi penanganan `ConnectionException` untuk ketahanan saat server API offline.
- **Seeder & Factory Realistis:**
  - Menggunakan Faker locale Indonesia (`id_ID`) dengan kurasi judul buku dan penerbit nasional.

---

## 💻 Prasyarat Sistem

- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Database:** MySQL / MariaDB (via Laragon / XAMPP)
- **Ekstensi PHP:** PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, cURL

---

## 🚀 Panduan Instalasi & Menjalankan Project

### 1. Clone Repository
```bash
git clone https://github.com/banyfarhan/app-perpustakaan.git
cd app-perpustakaan
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Generate application key:
```bash
php artisan key:generate
```

Pastikan pengaturan database di `.env` sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
DB_PASSWORD=

# Base URL instance kedua khusus panggilan API internal
INTERNAL_API_URL=http://127.0.0.1:8011
APP_FAKER_LOCALE=id_ID
```

### 4. Migrasi & Seeding Database
Jalankan migrasi tabel dan seeding data dummy realistis:
```bash
php artisan migrate:fresh --seed
```

### 5. Menjalankan Server Aplikasi (Wajib 2 Terminal)
Karena `php artisan serve` (PHP built-in server) bersifat *single-threaded*, konsumsi API internal memerlukan dua terminal berjalan bersamaan:

- **Terminal 1 – Web Server Utama (Aplikasi Browser):**
  ```bash
  php artisan serve --port=8000
  ```
  Akses aplikasi web di: `http://127.0.0.1:8000`

- **Terminal 2 – Internal API Server (Endpoint Service):**
  ```bash
  php artisan serve --port=8011
  ```
  Melayani request internal dari `DashboardController` dan `LoanController@report`.

---

## 🔑 Akun Bawaan (Default Seeders)

| Role | Nama | Email | Password |
|---|---|---|---|
| **Admin** | Bambang Sutrisno | `admin@pens.ac.id` | `password` |
| **Petugas 1** | Siti Rahmawati | `petugas1@pens.ac.id` | `password` |
| **Petugas 2** | Ahmad Fauzi | `petugas2@pens.ac.id` | `password` |

---

## 📖 Dokumentasi Tambahan

- **[Dokumentasi Lengkap REST API (API.md)](./API.md):** Berisi daftar 11 endpoint API, contoh request, dan respon JSON aktual (status 200, 201, 404, 422).
- **[Panduan Skenario Demo UAS (DEMO.md)](./DEMO.md):** Urutan langkah lengkap untuk simulasi dan demonstrasi fitur aplikasi.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan akademik praktikum Web Framework Politeknik Elektronika Negeri Surabaya (PENS).
