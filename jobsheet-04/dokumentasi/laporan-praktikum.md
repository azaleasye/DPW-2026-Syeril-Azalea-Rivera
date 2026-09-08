# Laporan Praktikum Jobsheet 3

| Informasi      | Detail                     |
| -------------- | -------------------------- |
| Nama           | Syeril Azalea Rivera       |
| Kelas          | TI 2F - 28                 |
| Program Studi  | D-IV - Teknik Informatika  |
| Mata Kuliah    | Desain dan Pemrograman Web |

# User Flow
## 1. Flow Login
```text
[Buka Halaman Login] -> [Masukkan Email/Username & Password] -> [Klik Login]
-> [Validasi Kredensial]
   -> (jika salah) -> [Tampilkan Pesan Error] -> [Kembali ke Form Login]
   -> (jika benar) -> [Arahkan ke Dashboard Petugas] -> [Selesai]
```

## 2. Flow Peminjaman
```text
[Petugas Login] -> [Dashboard] -> [Pilih Menu Peminjaman] -> [Pilih Anggota] -> [Pilih Buku]
-> [Cek Stok & Validasi Duplikasi]
   -> (jika stok = 0) -> [Tampilkan "Stok Habis"] -> [Gagal, pilih buku lain]
   -> (jika anggota masih pinjam buku yang sama) -> [Tampilkan "Buku ini masih dipinjam anggota"] -> [Gagal]
   -> (jika lolos kedua cek) -> [Isi Tanggal Pinjam (auto hari ini)] -> [Klik Simpan]
-> [Kurangi Stok Buku 1] -> [Simpan Transaksi] -> [Kembali ke Dashboard]
```

## 3. Flow Pengembalian
```text
[Petugas Login] -> [Dashboard] -> [Pilih Menu Pengembalian] -> [Cari Transaksi Aktif (nama anggota/judul buku)]
-> [Pilih Transaksi yang Ditemukan] -> [Cek Status Transaksi]
   -> (jika status sudah "Dikembalikan") -> [Tampilkan "Transaksi sudah selesai"] -> [Selesai (tidak bisa proses ulang)]
   -> (jika status "Dipinjam") -> [Cek Tanggal Jatuh Tempo]
       -> (jika melewati jatuh tempo) -> [Tampilkan Status "Terlambat"] -> [Hitung Denda/Keterlambatan]
       -> (jika tidak melewati) -> [Lanjutkan]
-> [Klik Kembalikan Buku] -> [Status menjadi "Dikembalikan"] -> [Tambah Stok Buku 1] -> [Kembali ke Dashboard]
```

## 4. Flow Riwayat
```text
[Petugas Login] -> [Dashboard] -> [Pilih Menu Riwayat] -> [Tampilkan Daftar Semua Transaksi] 
-> [Opsional: Cari berdasarkan nama/judul] atau [Filter Status (Semua/Dipinjam/Dikembalikan/Terlambat)] 
-> [Sistem Perbarui Tampilan Daftar] -> [Pilih Salah Satu Transaksi] -> [Lihat Detail Peminjaman] -> [Selesai]
```

# 1. Wireframe Login

```text
+----------------------------------------------------------------------------------+
|                                SIMPUS-Mini                                       |
|----------------------------------------------------------------------------------|
|                                                                                  |
|                              LOGIN PETUGAS                                       |
|                                                                                  |
|                  Email / Username                                                |
|                  +--------------------------------+                              |
|                  |                                |                              |
|                  +--------------------------------+                              |
|                                                                                  |
|                  Password                                                        |
|                  +--------------------------------+                              |
|                  |                                |                              |
|                  +--------------------------------+                              |
|                                                                                  |
|                  [            LOGIN            ]                                 |
|                                                                                  |
|                  Belum menjadi anggota?                                          |
|                  [ Registrasi Anggota Baru ]                                     |
|                                                                                  |
+----------------------------------------------------------------------------------+
| © 2026 SIMPUS-Mini                                                               |
+----------------------------------------------------------------------------------+
```

# 2. Wireframe Beranda / Dashboard Petugas

```text
+----------------------------------------------------------------------------------+
| SIMPUS-Mini       | Beranda | Buku | Anggota | Peminjaman | Riwayat | Petugas | Logout|
|----------------------------------------------------------------------------------|
|                                                                                  |
|  +----------------+  +----------------+  +----------------+                      |
|  | Total Buku     |  | Total Anggota  |  | Sedang Dipinjam|                      |
|  |      12        |  |       8        |  |       3        |                      |
|  +----------------+  +----------------+  +----------------+                      |
|                                                                                  |
|  Aksi Cepat:                                                                     |
|                                                                                  |
|  [ + Peminjaman Baru ]              [ + Pengembalian ]                           |
|                                                                                  |
|  Transaksi Terbaru                                                               |
|  ------------------------------------------------------------------------------  |
|  | Anggota          | Buku             | Tgl Pinjam | Status                  |  |
|  ------------------------------------------------------------------------------  |
|  | Siti Aminah      | Laskar Pelangi   | 01-09-2026 | Dipinjam                |  |
|  ------------------------------------------------------------------------------  |
|  | Budi Santoso     | Bumi Manusia     | 02-09-2026 | Dikembalikan            |  |
|  ------------------------------------------------------------------------------  |
|                                                                                  |
+----------------------------------------------------------------------------------+
| © 2026 SIMPUS-Mini                                                               |
+----------------------------------------------------------------------------------+
```

# 3. Wireframe Peminjaman

```text
+----------------------------------------------------------------------------------------+
| SIMPUS-Mini        | Beranda | Buku | Anggota | Peminjaman | Riwayat | Petugas | Logout|
|----------------------------------------------------------------------------------------|
|                                                                                        |
| Cari / Pilih Anggota                                                                   |
| +------------------------------------------------------------+                         |
| | Cari nama atau nomor anggota...                            |                         |
| +------------------------------------------------------------+                         |
|                                                                                        |
| Pilih Buku                                                                             |
| +------------------------------------------------------------+                         |
| | Cari judul buku...                                         |                         |
| +------------------------------------------------------------+                   |
|                                                                                  |
| Tanggal Pinjam                         Jatuh Tempo                              |
| +--------------------------+              +--------------------------+          |
| |     auto hari ini        |              |                          |          |
| +--------------------------+              +--------------------------+          |
|                                                                                  |
| [ Batal ]                                  [ Save Peminjaman ]                   |
|                                                                                  |
+----------------------------------------------------------------------------------+
| © 2026 SIMPUS-Mini                                                               |
+----------------------------------------------------------------------------------+
```


# 4. Wireframe Pengembalian

```text
+----------------------------------------------------------------------------------+
| SIMPUS-Mini        | Beranda | Buku | Anggota | Peminjaman | Riwayat | Petugas | Logout|
|----------------------------------------------------------------------------------|
|                                                                                  |
| Cari Transaksi Aktif                                                             |
| +------------------------------------------------------------+                   |
| | Cari nama anggota atau judul buku...                       |                   |
| +------------------------------------------------------------+                   |
|                                                                                  |
|  ------------------------------------------------------------------------------  |
|  | Anggota          | Buku             | Tgl Pinjam | Status   | Action       |  |
|  ------------------------------------------------------------------------------  |
|  | Siti Aminah      | Laskar Pelangi   | 01-09-2026 | Dipinjam | edit         |  |
|                                                                                  |
| +------------------------------------------------------------------------------+ |
| | Nama Anggota : Siti Aminah                                                  | |
| | Buku         : Laskar Pelangi                                               | |
| | Tgl Pinjam   : 01-09-2026                                                   | |
| | Jatuh Tempo  : 08-09-2026                                                   | |
| | Status       : Dipinjam                                                     | |
| |                                                                              | |
| |                              [ Kembalikan Buku ]                             | |
| +------------------------------------------------------------------------------+ |
|                                                                                  |
+----------------------------------------------------------------------------------+
| © 2026 SIMPUS-Mini                                                               |
+----------------------------------------------------------------------------------+
```

# 5. Wireframe Riwayat

```text
+----------------------------------------------------------------------------------+
| SIMPUS-Mini       | Beranda | Buku | Anggota | Peminjaman | Riwayat | Petugas | Logout|
|----------------------------------------------------------------------------------|
|                                                                                  |
| [ Cari anggota atau buku... ]        [ Semua Status ▼ ]                          |
|                                                                                  |
|----------------------------------------------------------------------------------|
| Anggota       | Buku            | Tgl Pinjam | Jatuh Tempo | Status            |
|----------------------------------------------------------------------------------|
| Siti Aminah   | Laskar Pelangi  | 01-09-2026 | 08-09-2026  | Dikembalikan      |
|----------------------------------------------------------------------------------|
| Budi Santoso  | Bumi Manusia    | 03-09-2026 | 10-09-2026  | Dipinjam          |
|----------------------------------------------------------------------------------|
| Andi          | Negeri 5 Menara | 20-08-2026 | 27-08-2026  | Terlambat         |
|----------------------------------------------------------------------------------|
|                                                                                  |
+----------------------------------------------------------------------------------+
| © 2026 SIMPUS-Mini                                                               |
+----------------------------------------------------------------------------------+
```

