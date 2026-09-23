# Laporan Praktikum Jobsheet 08: Koneksi PostgreSQL

## 1. Konsep Dasar Database & SQL 
Jobsheet ini mulai menggunakan koneksi database, agar data bisa disimpan tanpa kehilangan saat memulai session baru. Data tersebut disimpan di Database Management System/DMBS, dan disini menggunakan PostgreSQL.

Langkah yang dilakukan: 
1. Membuat koneksi db Postgresql
2. Create db simpus_mini

## 2. Skema Database: 01_buku_anggota_sql
Langkah yang dilakukan: 
1. Run script query pada folder database (anggota & buku)

**Penjelasan Struktur Table**
| Kolom       | Tipe Data      | Aturan               | Fungsi         |
| ----------- | -------------- | -------------------- | -------------- |
| `id`        | `SERIAL`       | `PRIMARY KEY`        | ID unik buku   |
| `judul`     | `VARCHAR(255)` | `NOT NULL`           | Judul buku     |
| `pengarang` | `VARCHAR(255)` | `NOT NULL`           | Nama pengarang |
| `tahun`     | `INTEGER`      | `NOT NULL`           | Tahun terbit   |
| `isbn`      | `VARCHAR(50)`  | -                    | Nomor ISBN     |
| `stok`      | `INTEGER`      | `NOT NULL DEFAULT 0` | Jumlah buku    |
| `kategori`  | `VARCHAR(50)`  | -                    | Kategori buku  |
