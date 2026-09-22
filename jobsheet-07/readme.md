# Laporan Praktikum Jobsheet 5

## Identitas 

| Informasi      | Detail                                  |
| -------------- | --------------------------------------- |
| Nama           | Syeril Azalea Rivera                    |
| Kelas          | TI 2F - 28                              |
| Program Studi  | D-IV - Teknik Informatika               |
| Mata Kuliah    | Desain dan Pemrograman Web              |
| Materi         | Jobsheet 07: PHP Dasar & Form Handling  |


## Struktur Folder

```text
DPW-2026-Syeril-Azalea-Rivera/
├── index.php                   # Beranda, kini file PHP
├── includes/
│   ├── header.php              # BARU — bagian atas HTML + navbar, dipakai ulang
│   └── footer.php              # BARU — bagian bawah HTML + footer, dipakai ulang
├── assets/
│   ├── css/style.css           # Ditambah gaya .flash
│   └── js/app.js               # Tidak berubah dari jobsheet-06
├── buku/
│   ├── list.php                # Render dari $_SESSION, bukan lagi fetch/JSON
│   ├── tambah.php              # Form kini punya method="post" & action
│   └── proses_tambah.php       # BARU — validasi server + simpan ke $_SESSION
├── anggota/
│   ├── list.php
│   ├── tambah.php
│   └── proses_tambah.php       # BARU
├── docs/wireframe.md           # Identik dengan jobsheet-06
├── README.md
└── Dokumentasi/                # Folder dokumentasi ini
```


