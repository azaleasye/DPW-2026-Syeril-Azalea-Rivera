# Dokumentasi Jobsheet 11: Keamanan Web Dasar

## 1. Konsep Dasar Keamanan Web
### 1.1 Alasan Keamanan
Jobsheet 11 berfungsi untuk **mengaudit dan meningkatkan keamanan** aplikasi yang telah dibuat pada jobsheet sebelumnya. 

### 1.2 Kerentanan yang Diaudit
Terdapat lima aspek keamanan yang diperiksa:

| # | Kerentanan                | Tujuan Pemeriksaan                                                     |
| - | ------------------------- | ---------------------------------------------------------------------- |
| 1 | SQL Injection             | Memastikan input tidak dapat digunakan untuk menyisipkan perintah SQL. |
| 2 | XSS                       | Mencegah input pengguna menjadi kode HTML/JavaScript berbahaya.        |
| 3 | CSRF                      | Mencegah situs lain menjalankan aksi tanpa persetujuan pengguna.       |
| 4 | Validasi & Sanitasi Input | Memastikan data yang diterima sesuai dengan aturan yang ditentukan.    |
| 5 | Session Fixation          | Mencegah penyalahgunaan ID session pengguna.                           |

SQL Injection dan validasi input sudah diterapkan pada jobsheet sebelumnya melalui **prepared statement** dan **validasi server-side**. Jobsheet 11 melakukan audit untuk memastikan perlindungan tersebut tetap diterapkan.
Sementara itu, XSS, CSRF, dan Session Fixation membutuhkan penerapan keamanan tambahan.

### 1.3 Prinsip Dasar Keamanan
Prinsip utama keamanan web adalah **jangan mempercayai input dari luar aplikasi**. Data dari `$_POST`, `$_GET`, maupun sumber lain harus diperiksa sebelum digunakan.
Prinsip ini sudah diterapkan pada jobsheet sebelumnya melalui:
* **Validasi server-side** untuk memeriksa data yang masuk.
* **Prepared statement** untuk mencegah SQL Injection.
* **Guard authentication** untuk membatasi akses halaman.
* **Escaping output** untuk mencegah XSS.
* **CSRF token** untuk memastikan request berasal dari aplikasi.
* **Regenerasi session ID** untuk meningkatkan keamanan session.

Dengan demikian, Jobsheet 11 melanjutkan konsep keamanan yang sudah diterapkan sebelumnya dan menambahkan perlindungan terhadap **XSS, CSRF, dan Session Fixation**.