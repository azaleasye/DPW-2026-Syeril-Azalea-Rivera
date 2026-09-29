# Dokumentasi Jobsheet 10: Autentikasi & Manajemen Sesi

## 1. Konsep Dasar Autentikasi & Otorisasi

### 1.1 Autentikasi dan Otorisasi
Autentikasi digunakan untuk **memverifikasi identitas pengguna**, sedangkan otorisasi digunakan untuk **menentukan hak akses pengguna**. Pada jobsheet ini, autentikasi dilakukan melalui Login dan otorisasi menggunakan pemeriksaan session pada `auth.php`.

### 1.2 Penyimpanan Password dengan Hash
Password tidak boleh disimpan dalam bentuk **plaintext** karena jika database bocor, password pengguna dapat diketahui.
PHP menyediakan fungsi hashing:
```php
password_hash($password, PASSWORD_DEFAULT);
```

Digunakan untuk mengubah password menjadi hash saat registrasi.
```php
password_verify($password, $hash);
```
Digunakan untuk memeriksa apakah password yang dimasukkan sesuai dengan hash yang tersimpan saat login.

### 1.3 Kolom `password` pada Database
Kolom password menggunakan:
```sql
password VARCHAR(255)
```

Tipe `VARCHAR(255)` digunakan karena hasil `password_hash()` berupa teks yang cukup panjang. Ukuran tersebut memberikan ruang yang cukup untuk menyimpan hash password.

### 1.4 Session untuk Menyimpan Identitas Login
`$_SESSION` digunakan untuk menyimpan informasi pengguna yang sedang login sehingga identitasnya tetap tersedia ketika berpindah halaman. Contohnya:
```php
$_SESSION['user_id']
$_SESSION['nama']
$_SESSION['role']
```

Session memungkinkan server mengenali pengguna tanpa meminta login kembali pada setiap halaman.

**Alur sederhananya:**
`Login → Verifikasi Password → Simpan Identitas di Session → Akses Halaman → Cek Otorisasi`

