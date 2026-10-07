# Laporan Praktikum Jobsheet 11

## Identitas 

| Informasi      | Detail                                    |
| -------------- | ------------------------------------------|
| Nama           | Syeril Azalea Rivera                      |
| Kelas          | TI 2F - 28                                |
| Program Studi  | D-IV - Teknik Informatika                 |
| Mata Kuliah    | Desain dan Pemrograman Web                |
| Materi         | Jobsheet 11: Keamanan Web Dasar           |


Struktur Folder
jobsheet-11/
├── includes/
│   ├── helpers.php                    # BARU — fungsi e() untuk XSS
│   ├── csrf.php                       # BARU — token CSRF
│   ├── auth.php                       # Tidak berubah dari jobsheet-10
│   └── header.php                     # require_once helpers.php & csrf.php
├── auth/
│   ├── proses_login.php               # + session_regenerate_id(true), csrf_verify()
│   └── ...                            # + csrf_field() di form
├── buku/, anggota/
│   ├── list.php, edit.php             # Output dibungkus e()
│   ├── tambah.php, edit.php           # + csrf_field() di form
│   └── proses_*.php, hapus.php        # + csrf_verify()
├── dokumentasi/
│   ├── laporan-praktikum.md           # Laporan Praktikum jobsheet
│   └── security-checklist.md          # BARU — audit lengkap
├── README.md