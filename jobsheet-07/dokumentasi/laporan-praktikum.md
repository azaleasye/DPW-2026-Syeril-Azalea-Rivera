# Laporan Praktikum Jobsheet 08: Koneksi PostgreSQL

## 1. Konsep Dasar Database & SQL 
Jobsheet ini mulai menggunakan koneksi database, agar data bisa disimpan tanpa kehilangan saat memulai session baru. Data tersebut disimpan di Database Management System/DMBS, dan disini menggunakan PostgreSQL.

## 2. Skema Database: 01_buku_anggota_sql
| Kolom       | Tipe Data      | Aturan               | Fungsi         |
| ----------- | -------------- | -------------------- | -------------- |
| `id`        | `SERIAL`       | `PRIMARY KEY`        | ID unik buku   |
| `judul`     | `VARCHAR(255)` | `NOT NULL`           | Judul buku     |
| `pengarang` | `VARCHAR(255)` | `NOT NULL`           | Nama pengarang |
| `tahun`     | `INTEGER`      | `NOT NULL`           | Tahun terbit   |
| `isbn`      | `VARCHAR(50)`  | -                    | Nomor ISBN     |
| `stok`      | `INTEGER`      | `NOT NULL DEFAULT 0` | Jumlah buku    |
| `kategori`  | `VARCHAR(50)`  | -                    | Kategori buku  |

**Bagaimana Kolom-Kolom Ini Berhubungan dengan Kode PHP?**
**nama setiap kolom** di sini: `judul`, `pengarang`, `tahun`,`isbn`, `stok`, `kategori` untuk tabel `buku`; `nama`, `no_anggota`, `alamat`, `no_hp` untuk tabel `anggota` — **persis sama** dengan nama kunci array asosiatif yang dipakai `proses_tambah.php` pada jobsheet-07

## 3. Persiapan Database Sebelum Menjalankan
Langkah yang dilakukan: 
1. Langkah 1: Pastikan PostgreSQL & Ekstensi PHP Siap
-  Memastikan PostgreSQL sudah terinstall
-  Install extenstion psql dan pdo_sql di laragon
2. Langkah 2: Membuat Database
    ```bash
    createdb simpus_mini
    ```
3. Langkah 3: Menjalankan Skema
    ```bash
    psql -d simpus_mini -f sql/01_buku_anggota.sql
    ```
4. Langkah 4: Menyesuaikan Kredensial
   Menyesuaikan kredensial di `includes/koneksi.php` (`$user`, `$pass`) dengan environment lokal.