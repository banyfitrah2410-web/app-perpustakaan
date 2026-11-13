# Dokumentasi REST API - Perpustakaan

Dokumentasi seluruh endpoint REST API yang dibangun pada **Pertemuan 9**. Seluruh endpoint menggunakan prefix `/api` dan mengembalikan format universal JSON dengan header `Accept: application/json`.

---

## 📌 Ringkasan 11 Endpoint API

| No | Method | URL | Request Body | Status Sukses | Status Gagal | Deskripsi |
|---|---|---|---|---|---|---|
| 1 | `GET` | `/api/books` | - | `200 OK` | - | Mengambil daftar buku (termasuk pagination & filter `?judul=`, `?category_id=`) |
| 2 | `GET` | `/api/books/{id}` | - | `200 OK` | `404 Not Found` | Mengambil detail buku berdasarkan ID |
| 3 | `POST` | `/api/books` | JSON Buku Baru | `201 Created` | `422 Unprocessable` | Menambahkan data buku baru |
| 4 | `PUT` | `/api/books/{id}` | JSON Update Buku | `200 OK` | `422 Unprocessable`, `404 Not Found` | Memperbarui data buku berdasarkan ID |
| 5 | `DELETE` | `/api/books/{id}` | - | `200 OK` | `404 Not Found` | Menghapus data buku berdasarkan ID |
| 6 | `GET` | `/api/members` | - | `200 OK` | - | Mengambil daftar anggota perpustakaan (paginated) |
| 7 | `GET` | `/api/members/{id}` | - | `200 OK` | `404 Not Found` | Mengambil detail anggota berdasarkan ID |
| 8 | `GET` | `/api/loans` | - | `200 OK` | - | Mengambil riwayat & transaksi peminjaman (paginated) |
| 9 | `POST` | `/api/loans` | JSON Peminjaman Baru | `201 Created` | `422 Unprocessable` | Mencatat transaksi peminjaman baru (multi-buku) |
| 10 | `PUT` | `/api/loans/{id}/kembalikan` | - | `200 OK` | `404 Not Found` | Memproses pengembalian buku peminjaman |
| 11 | `GET` | `/api/stats` | - | `200 OK` | - | Mengambil data statistik (total buku, anggota, peminjaman aktif) |

---

## 📖 Detail Endpoint dan Contoh Request / Response

### 1. `GET /api/books` - Daftar Buku (Pagination & Filter)
Mengambil daftar buku yang dibungkus `BookResource::collection()` lengkap dengan pagination (`meta` & `links`) dan nested object category. Mendukung query filter:
- `?judul=<keyword>`
- `?category_id=<id>`

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": [
        {
            "id": 1,
            "judul": "Clean Code",
            "penulis": "Robert C. Martin",
            "penerbit": "Prentice Hall",
            "tahun_terbit": "2008",
            "isbn": "9780132350884",
            "stok": 5,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Teknologi"
            },
            "created_at": "2026-09-28T08:10:23.000000Z",
            "updated_at": "2026-09-28T08:10:23.000000Z"
        },
        {
            "id": 3,
            "judul": "Laskar Pelangi (Edisi Khusus)",
            "penulis": "Andrea Hirata",
            "penerbit": "Bentang Pustaka",
            "tahun_terbit": "2008",
            "isbn": "978-979-3062-79-2",
            "stok": 15,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Teknologi"
            },
            "created_at": "2026-11-13T10:05:07.000000Z",
            "updated_at": "2026-11-13T10:05:07.000000Z"
        },
        {
            "id": 4,
            "judul": "Pemrograman Web Modern (Edisi Revisi)",
            "penulis": "John Doe, M.Kom",
            "penerbit": "Informatika Bandung",
            "tahun_terbit": "2025",
            "isbn": "978-602-1234-56-7",
            "stok": 12,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Teknologi"
            },
            "created_at": "2026-11-13T10:05:42.000000Z",
            "updated_at": "2026-11-13T10:05:42.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/books?page=1",
        "last": "http://localhost/api/books?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/books?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/books",
        "per_page": 10,
        "to": 3,
        "total": 3
    }
}
```

#### Contoh Response dengan Filter (`GET /api/books?judul=Clean&category_id=1` - `200 OK`):
```json
{
    "data": [
        {
            "id": 1,
            "judul": "Clean Code",
            "penulis": "Robert C. Martin",
            "penerbit": "Prentice Hall",
            "tahun_terbit": "2008",
            "isbn": "9780132350884",
            "stok": 5,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Teknologi"
            },
            "created_at": "2026-09-28T08:10:23.000000Z",
            "updated_at": "2026-09-28T08:10:23.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/books?page=1",
        "last": "http://localhost/api/books?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/books?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/books",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

---

### 2. `GET /api/books/{id}` - Detail Buku
Mengambil detail satu buku beserta relasi kategori.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": {
        "id": 7,
        "judul": "Pemrograman Web Modern",
        "penulis": "John Doe",
        "penerbit": "Informatika",
        "tahun_terbit": "2024",
        "isbn": "978-449-3679-8",
        "stok": 8,
        "sampul": null,
        "category": {
            "id": 1,
            "nama_kategori": "Teknologi"
        },
        "created_at": "2026-11-13T10:06:58.000000Z",
        "updated_at": "2026-11-13T10:06:58.000000Z"
    }
}
```

#### Contoh Response Gagal (`GET /api/books/99999` - `404 Not Found`):
```json
{
    "message": "No query results for model [App\\Models\\Book] 99999"
}
```

---

### 3. `POST /api/books` - Tambah Buku
Menambahkan buku baru dengan validasi `StoreBookRequest`.

#### Request Header:
```http
Content-Type: application/json
Accept: application/json
```

#### Request Body:
```json
{
    "judul": "Pemrograman Web Modern",
    "penulis": "John Doe",
    "penerbit": "Informatika",
    "tahun_terbit": 2024,
    "isbn": "978-449-3679-8",
    "stok": 8,
    "category_id": 1
}
```

#### Contoh Response Sukses (`201 Created`):
```json
{
    "data": {
        "id": 7,
        "judul": "Pemrograman Web Modern",
        "penulis": "John Doe",
        "penerbit": "Informatika",
        "tahun_terbit": 2024,
        "isbn": "978-449-3679-8",
        "stok": 8,
        "sampul": null,
        "category": {
            "id": 1,
            "nama_kategori": "Teknologi"
        },
        "created_at": "2026-11-13T10:06:58.000000Z",
        "updated_at": "2026-11-13T10:06:58.000000Z"
    }
}
```

#### Contoh Response Gagal Validasi (`POST /api/books` dengan body kosong - `422 Unprocessable Content`):
```json
{
    "message": "Judul buku wajib diisi. (and 5 more errors)",
    "errors": {
        "judul": [
            "Judul buku wajib diisi."
        ],
        "penulis": [
            "Nama penulis wajib diisi."
        ],
        "penerbit": [
            "Nama penerbit wajib diisi."
        ],
        "tahun_terbit": [
            "Tahun terbit wajib diisi."
        ],
        "stok": [
            "Stok wajib diisi."
        ],
        "category_id": [
            "Kategori wajib dipilih."
        ]
    }
}
```

---

### 4. `PUT /api/books/{id}` - Update Buku
Memperbarui data buku yang sudah ada.

#### Request Body:
```json
{
    "judul": "Pemrograman Web Modern (Edisi Revisi)",
    "penulis": "John Doe, M.Kom",
    "penerbit": "Informatika Bandung",
    "tahun_terbit": 2025,
    "isbn": "978-449-3679-8",
    "stok": 12,
    "category_id": 1
}
```

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": {
        "id": 7,
        "judul": "Pemrograman Web Modern (Edisi Revisi)",
        "penulis": "John Doe, M.Kom",
        "penerbit": "Informatika Bandung",
        "tahun_terbit": 2025,
        "isbn": "978-449-3679-8",
        "stok": 12,
        "sampul": null,
        "category": {
            "id": 1,
            "nama_kategori": "Teknologi"
        },
        "created_at": "2026-11-13T10:06:58.000000Z",
        "updated_at": "2026-11-13T10:06:58.000000Z"
    }
}
```

#### Contoh Response Gagal Validasi (`PUT /api/books/{id}` field kosong - `422 Unprocessable Content`):
```json
{
    "message": "The judul field is required. (and 5 more errors)",
    "errors": {
        "judul": [
            "The judul field is required."
        ],
        "penulis": [
            "The penulis field is required."
        ],
        "penerbit": [
            "The penerbit field is required."
        ],
        "tahun_terbit": [
            "The tahun terbit field is required."
        ],
        "stok": [
            "The stok field is required."
        ],
        "category_id": [
            "The category id field is required."
        ]
    }
}
```

---

### 5. `DELETE /api/books/{id}` - Hapus Buku
Menghapus buku dari database berdasarkan ID.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "message": "Buku berhasil dihapus."
}
```

#### Contoh Response Gagal (`DELETE /api/books/99999` - `404 Not Found`):
```json
{
    "message": "No query results for model [App\\Models\\Book] 99999"
}
```

---

### 6. `GET /api/members` - Daftar Anggota
Mengambil data seluruh anggota perpustakaan secara terpaginasi (10 anggota per halaman).

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": [
        {
            "id": 1,
            "nama": "Budi Santoso",
            "nim": "2310501001",
            "email": "budi.santoso@pens.ac.id",
            "nomor_telepon": "081298765432",
            "alamat": "Jl. Raya Surabaya No. 12",
            "status": "aktif",
            "created_at": "2026-09-28T09:41:50.000000Z",
            "updated_at": "2026-09-28T09:41:50.000000Z"
        },
        {
            "id": 2,
            "nama": "Bany",
            "nim": "2410",
            "email": "bany@gmail.com",
            "nomor_telepon": "087123456789",
            "alamat": "JL. apa adanya",
            "status": "aktif",
            "created_at": "2026-09-28T09:46:36.000000Z",
            "updated_at": "2026-09-28T09:50:52.000000Z"
        },
        {
            "id": 3,
            "nama": "Najiv",
            "nim": "1703",
            "email": "najiv@gmail.com",
            "nomor_telepon": "087123456789",
            "alamat": "JL. terserah",
            "status": "aktif",
            "created_at": "2026-09-28T09:49:41.000000Z",
            "updated_at": "2026-09-28T09:49:41.000000Z"
        },
        {
            "id": 4,
            "nama": "Atta",
            "nim": "2001",
            "email": "Atta@gmail.com",
            "nomor_telepon": "087123456789",
            "alamat": "JL. sembarang",
            "status": "nonaktif",
            "created_at": "2026-09-28T09:50:38.000000Z",
            "updated_at": "2026-09-28T09:50:38.000000Z"
        },
        {
            "id": 5,
            "nama": "Najiv",
            "nim": "1705",
            "email": "najivv@gmail.com",
            "nomor_telepon": "087123456789",
            "alamat": "JL. apa adanya",
            "status": "nonaktif",
            "created_at": "2026-09-28T09:51:44.000000Z",
            "updated_at": "2026-09-28T09:55:33.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/members?page=1",
        "last": "http://localhost/api/members?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/members?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/members",
        "per_page": 10,
        "to": 5,
        "total": 5
    }
}
```

---

### 7. `GET /api/members/{id}` - Detail Anggota
Mengambil data detail satu anggota perpustakaan.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": {
        "id": 1,
        "nama": "Budi Santoso",
        "nim": "2310501001",
        "email": "budi.santoso@pens.ac.id",
        "nomor_telepon": "081298765432",
        "alamat": "Jl. Raya Surabaya No. 12",
        "status": "aktif",
        "created_at": "2026-09-28T09:41:50.000000Z",
        "updated_at": "2026-09-28T09:41:50.000000Z"
    }
}
```

#### Contoh Response Gagal (`GET /api/members/99999` - `404 Not Found`):
```json
{
    "message": "No query results for model [App\\Models\\Member] 99999"
}
```

---

### 8. `GET /api/loans` - Daftar Transaksi Peminjaman
Mengambil seluruh data peminjaman dengan eager loading relasi `member`, `user` (petugas), dan `loanItems.book`.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": [
        {
            "id": 2,
            "member": {
                "id": 1,
                "nama": "Budi Santoso"
            },
            "petugas": {
                "id": 1,
                "name": "Admin Perpustakaan"
            },
            "tanggal_pinjam": "2026-11-13",
            "tanggal_kembali": "2026-11-20",
            "tanggal_dikembalikan": "2026-11-13",
            "status": "dikembalikan",
            "books": [
                {
                    "id": 3,
                    "judul": "Laskar Pelangi (Edisi Khusus)"
                }
            ]
        },
        {
            "id": 3,
            "member": {
                "id": 1,
                "nama": "Budi Santoso"
            },
            "petugas": {
                "id": 1,
                "name": "Admin Perpustakaan"
            },
            "tanggal_pinjam": "2026-11-13",
            "tanggal_kembali": "2026-11-20",
            "tanggal_dikembalikan": "2026-11-13",
            "status": "dikembalikan",
            "books": [
                {
                    "id": 4,
                    "judul": "Pemrograman Web Modern (Edisi Revisi)"
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost/api/loans?page=1",
        "last": "http://localhost/api/loans?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/loans?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/loans",
        "per_page": 10,
        "to": 2,
        "total": 2
    }
}
```

---

### 9. `POST /api/loans` - Catat Transaksi Peminjaman Baru
Mencatat peminjaman baru beserta daftar buku (`book_ids[]`). Mendukung peminjaman multi-buku.

#### Request Body:
```json
{
    "member_id": 1,
    "user_id": 1,
    "tanggal_pinjam": "2026-11-13",
    "tanggal_kembali": "2026-11-20",
    "book_ids": [
        7
    ]
}
```

#### Contoh Response Sukses (`201 Created`):
```json
{
    "data": {
        "id": 4,
        "member": {
            "id": 1,
            "nama": "Budi Santoso"
        },
        "petugas": {
            "id": 1,
            "name": "Admin Perpustakaan"
        },
        "tanggal_pinjam": "2026-11-13",
        "tanggal_kembali": "2026-11-20",
        "tanggal_dikembalikan": null,
        "status": "dipinjam",
        "books": [
            {
                "id": 7,
                "judul": "Pemrograman Web Modern (Edisi Revisi)"
            }
        ]
    }
}
```

#### Contoh Response Gagal Validasi (`POST /api/loans` tanpa payload - `422 Unprocessable Content`):
```json
{
    "message": "Anggota wajib dipilih. (and 4 more errors)",
    "errors": {
        "member_id": [
            "Anggota wajib dipilih."
        ],
        "user_id": [
            "Petugas (user_id) wajib diisi."
        ],
        "tanggal_pinjam": [
            "Tanggal pinjam wajib diisi."
        ],
        "tanggal_kembali": [
            "Tanggal kembali wajib diisi."
        ],
        "book_ids": [
            "Pilih minimal satu buku."
        ]
    }
}
```

---

### 10. `PUT /api/loans/{id}/kembalikan` - Pengembalian Buku
Memperbarui status peminjaman menjadi `dikembalikan` dan mengisi `tanggal_dikembalikan` dengan tanggal hari ini.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "data": {
        "id": 4,
        "member": {
            "id": 1,
            "nama": "Budi Santoso"
        },
        "petugas": {
            "id": 1,
            "name": "Admin Perpustakaan"
        },
        "tanggal_pinjam": "2026-11-13",
        "tanggal_kembali": "2026-11-20",
        "tanggal_dikembalikan": "2026-11-13",
        "status": "dikembalikan",
        "books": [
            {
                "id": 7,
                "judul": "Pemrograman Web Modern (Edisi Revisi)"
            }
        ]
    }
}
```

#### Contoh Response Gagal (`PUT /api/loans/99999/kembalikan` - `404 Not Found`):
```json
{
    "message": "No query results for model [App\\Models\\Loan] 99999"
}
```

---

### 11. `GET /api/stats` - Statistik Perpustakaan
Mengembalikan data agregat perpustakaan berupa total buku, total anggota, dan jumlah peminjaman aktif.

#### Contoh Response Sukses (`200 OK`):
```json
{
    "total_buku": 4,
    "total_anggota": 5,
    "peminjaman_aktif": 0
}
```

---
