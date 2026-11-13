# Skenario Demo Aplikasi Perpustakaan Digital Kampus (UAS)

Dokumen ini memuat panduan langkah demi langkah untuk mendemokan aplikasi Sistem Perpustakaan Digital Kampus, mencakup fitur autentikasi, manajemen data (CRUD), relasi transaksi, konsumsi REST API internal, hingga pengujian API eksternal via Postman.

---

## 🛠️ Persiapan Sebelum Demo

1. **Jalankan Database Server:**
   Pastikan MySQL di Laragon / XAMPP sudah berjalan aktif.
2. **Jalankan Dua Instance Server Laravel:**
   - **Terminal 1 (Web Server Utama):**
     ```bash
     php artisan serve --port=8000
     ```
   - **Terminal 2 (Internal API Server):**
     ```bash
     php artisan serve --port=8011
     ```
3. **Reset Database & Data Dummy:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Data otomatis terisi: 5 kategori, 20 buku, 15 anggota, 10 riwayat transaksi peminjaman, dan 3 user ber-role admin & petugas)*.

---

## 🎬 Urutan Skenario Demo

### 1. Autentikasi (Login & Proteksi Route)
- Akses `http://127.0.0.1:8000/` di browser.
- Karena belum login, aplikasi otomatis mengarahkan (redirect) ke `http://127.0.0.1:8000/login`.
- Masukkan kredensial **Admin**:
  - **Email:** `admin@pens.ac.id`
  - **Password:** `password`
- Klik **Login**.
- Pengguna diarahkan ke halaman **Dashboard** (`/`), dan nama **Bambang Sutrisno (Admin)** tampil di navbar.

### 2. Tampilkan Dashboard (Konsumsi API Internal)
- Amati halaman Dashboard:
  - Kartu statistik menampilkan:
    - **Total Buku:** 20
    - **Total Anggota:** 15
    - **Peminjaman Aktif:** sesuai jumlah transaksi berstatus `dipinjam` di database.
  - Tunjukkan bahwa data ini ditarik via Laravel HTTP Client dari endpoint `GET /api/stats` pada server port `8011`.
  - *(Opsional pengujian daya tahan)*: Jika server port `8011` dimatikan, halaman Dashboard tidak error 500, melainkan fallback menampilkan angka `0` berkat penanganan `ConnectionException`.

### 3. Tambah Kategori Buku (Role Admin)
- Klik menu **Kategori** di navbar (menu ini hanya muncul untuk role admin).
- Klik tombol **Tambah Kategori**.
- Masukkan:
  - **Nama Kategori:** `Ensiklopedia`
  - **Deskripsi:** `Buku referensi lengkap memuat rangkuman ilmu pengetahuan dunia.`
- Klik **Simpan**.
- Kategori baru muncul di daftar kategori dengan pesan sukses.

### 4. Tambah Data Buku Baru
- Klik menu **Buku** di navbar.
- Tunjukkan pagination rapi bergaya teks/link di bagian bawah tabel buku.
- Klik **Tambah Buku**.
- Isi formulir buku:
  - **Judul:** `Ensiklopedia Sains dan Teknologi Nusantara`
  - **Penulis:** `Tim Ahli PENS`
  - **Penerbit:** `Informatika Bandung`
  - **Tahun Terbit:** `2024`
  - **ISBN:** `978-602-9999-01-1`
  - **Stok:** `10`
  - **Kategori:** Pilih `Ensiklopedia`
- Klik **Simpan**.
- Buku baru berhasil tersimpan dan tampil di tabel buku.

### 5. Tambah Anggota Perpustakaan
- Klik menu **Anggota** di navbar.
- Klik tombol **Tambah Anggota**.
- Isi formulir anggota:
  - **Nama:** `Achmad Maulana`
  - **NIM:** `2410502099`
  - **Email:** `achmad.maulana@pens.ac.id`
  - **Nomor Telepon:** `081234567890`
  - **Alamat:** `Jl. Raya ITS No. 10, Sukolilo, Surabaya`
  - **Status:** `Aktif`
- Klik **Simpan**.
- Anggota baru berhasil ditambahkan ke database.

### 6. Transaksi Peminjaman Baru (Multi-Buku)
- Klik menu **Peminjaman** di navbar.
- Klik tombol **Catat Peminjaman**.
- Masukkan data peminjaman:
  - **Pilih Anggota:** `Achmad Maulana`
  - **Tanggal Pinjam:** (Tanggal hari ini)
  - **Tanggal Kembali:** (7 hari ke depan)
  - **Pilih Buku:** Centang minimal 2 buku (misal: `Ensiklopedia Sains dan Teknologi Nusantara` dan salah satu buku novel).
- Klik **Simpan Transaksi**.
- Transaksi peminjaman multi-buku berhasil dicatat dengan status default `dipinjam`.

### 7. Tinjau Daftar Peminjaman Aktif
- Pada tabel **Peminjaman**, periksa baris transaksi yang baru dibuat:
  - Nama anggota, nama petugas, judul buku yang dipinjam, dan rentang tanggal tampil lengkap.
  - Status peminjaman menampilkan badge `Dipinjam`.

### 8. Proses Pengembalian Buku
- Pada baris transaksi peminjaman yang aktif, klik tombol **Kembalikan**.
- Status peminjaman seketika terupdate menjadi `Dikembalikan`.
- Kolom **Tgl Dikembalikan** terisi otomatis dengan tanggal hari ini.

### 9. Tampilkan Laporan Peminjaman (Konsumsi API Internal)
- Klik menu **Laporan** di navbar (atau tombol *Lihat Laporan Peminjaman* di Dashboard).
- Tunjukkan tabel laporan peminjaman:
  - Menampilkan seluruh transaksi peminjaman yang dikonsumsi secara asinkron dari `GET /api/loans`.
  - Dilengkapi fitur pagination `« Sebelumnya` dan `Selanjutnya »`.
  - Tunjukkan bahwa data peminjaman terbaru yang baru diproses pengembaliannya sudah tercatat rapi di laporan.

### 10. Demo REST API di Postman / cURL
Buka Postman atau terminal cURL untuk menguji endpoint publik API:
1. `GET http://127.0.0.1:8000/api/books` - Format JSON Resource dengan pagination & meta.
2. `GET http://127.0.0.1:8000/api/books?judul=Ensiklopedia` - Pencarian terfilter.
3. `GET http://127.0.0.1:8000/api/stats` - Statistik agregat `{"total_buku": ..., "total_anggota": ..., "peminjaman_aktif": ...}`.
4. `GET http://127.0.0.1:8000/api/loans` - Riwayat peminjaman beserta relasi nested `member`, `petugas`, dan array `books`.
5. `POST http://127.0.0.1:8000/api/books` dengan body kosong - Mengembalikan HTTP status `422 Unprocessable Content` dengan pesan validasi bahasa Indonesia.

