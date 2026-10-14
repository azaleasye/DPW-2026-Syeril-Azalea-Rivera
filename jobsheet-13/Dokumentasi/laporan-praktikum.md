# Dokumentasi Jobsheet 13: Deployment & Dokumentasi

## 01 Konsep Dasar
### 1.1 Tujuan
Bertujuan untuk memahami konsep deployment dan pentingnya memisahkan konfigurasi database dari kode sumber aplikasi. Pemisahan ini dilakukan agar aplikasi SIMPUS-Mini dapat dijalankan pada lingkungan yang berbeda tanpa perlu mengubah kode koneksi database secara langsung.

## 1.2 Konsep Dasar
Deployment merupakan proses memindahkan aplikasi dari lingkungan pengembangan (development) ke lingkungan lain, seperti server produksi, layanan hosting, atau komputer yang digunakan untuk demonstrasi.

Pada Jobsheet 01 sampai Jobsheet 12, aplikasi SIMPUS-Mini dikembangkan dan dijalankan secara lokal menggunakan PHP dan PostgreSQL. Pada Jobsheet 13, aplikasi dipersiapkan agar lebih mudah dikonfigurasi ketika dipindahkan ke lingkungan lain.

## 1.3 Permasalahan Konfigurasi Database
Sebelumnya, konfigurasi database dituliskan langsung di dalam file includes/koneksi.php, meliputi host, port, nama database, username, dan password.

Cara tersebut dapat digunakan untuk pengembangan lokal, tetapi kurang fleksibel ketika aplikasi dipindahkan ke server lain. Selain itu, penyimpanan kredensial asli di dalam kode sumber yang diunggah ke repository publik dapat menimbulkan risiko keamanan.

## 1.4 Solusi Pemisahan Konfigurasi
Solusi yang diterapkan adalah memisahkan konfigurasi database ke dalam file includes/config.php dan menggunakan environment variable untuk membaca nilai konfigurasi dari lingkungan tempat aplikasi dijalankan.

Dengan pendekatan ini, file config.php bertanggung jawab menyediakan nilai konfigurasi, sedangkan koneksi.php bertanggung jawab membuat koneksi database menggunakan PDO.

## 02 Config & Koneksi
### 2.1 Tujuan
bertujuan untuk membuat file konfigurasi terpisah yang menyimpan parameter koneksi database. File tersebut menggunakan fungsi getenv() untuk membaca environment variable dan menyediakan nilai cadangan apabila variabel belum diatur.
### 2.2 Implementasi Config.php
Membuat File Config.php, penjelasan kode sbb: 
1. getenv('DB_HOST') digunakan untuk membaca environment variable yang berisi alamat host database.
2. getenv('DB_PORT') digunakan untuk membaca port PostgreSQL.
3. getenv('DB_NAME') digunakan untuk membaca nama database.
4. getenv('DB_USER') dan getenv('DB_PASS') digunakan untuk membaca username dan password database.
5. Operator ?: menyediakan nilai cadangan apabila hasil pembacaan environment variable bernilai falsy, misalnya false atau string kosong.
6. Pernyataan return mengembalikan konfigurasi dalam bentuk array asosiatif agar dapat digunakan oleh file PHP lainnya.

File includes/config.php berhasil dibuat sebagai tempat pengaturan konfigurasi database. Nilai cadangan disesuaikan dengan konfigurasi pengembangan lokal SIMPUS-Mini, yaitu database simpus_mini dengan username postgres-1.