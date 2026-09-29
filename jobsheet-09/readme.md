# Laporan Praktikum Jobsheet 8

## Identitas 

| Informasi      | Detail                                  |
| -------------- | --------------------------------------- |
| Nama           | Syeril Azalea Rivera                    |
| Kelas          | TI 2F - 28                              |
| Program Studi  | D-IV - Teknik Informatika               |
| Mata Kuliah    | Desain dan Pemrograman Web              |
| Materi         | Jobsheet 08: Koneksi Database  |


## Struktur Folder

```text
jobsheet-08/
├── index.php                      # Kartu statistik dari SELECT COUNT(*)
├── includes/
│   ├── header.php, footer.php      # Tidak berubah dari jobsheet-07
│   └── koneksi.php                  # BARU — koneksi PDO ke PostgreSQL
├── sql/
│   └── 01_buku_anggota.sql          # BARU — skema tabel buku & anggota
├── buku/
│   ├── list.php                     # SELECT * FROM buku, bukan $_SESSION
│   ├── tambah.php                   # Tidak berubah dari jobsheet-07
│   └── proses_tambah.php            # INSERT via prepared statement
├── anggota/
│   ├── list.php
│   ├── tambah.php
│   └── proses_tambah.php            # INSERT via prepared statement
├── docs/wireframe.md                 # Identik dengan jobsheet-07
├── README.md
└── Dokumentasi/                      # Folder dokumentasi ini
```


