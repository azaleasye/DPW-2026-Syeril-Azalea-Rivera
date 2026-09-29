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

## 2. Tabel `users` & Registrasi

### 2.1 Skema `sql/02_users.sql`

```sql
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'petugas'
);
```

Beberapa bagian penting:

* `id SERIAL PRIMARY KEY` — ID bertambah otomatis dan menjadi primary key.
* `username ... UNIQUE` — setiap username harus berbeda.
* `password VARCHAR(255)` — digunakan untuk menyimpan hash password.
* `role ... DEFAULT 'petugas'` — akun baru otomatis memiliki role `petugas`.

Role disiapkan untuk pengembangan hak akses berikutnya, tetapi perbedaan akses berdasarkan role belum diterapkan pada jobsheet ini.

### 2.2 Halaman `register.php`

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';
```

Kode tersebut:

* Memulai session jika belum aktif.
* Mengarahkan pengguna ke `index.php` jika sudah login.
* Memuat `header.php` untuk tampilan halaman.

Form registrasi menggunakan input `nama`, `username`, dan `password`. Field password menggunakan `type="password"` dan `minlength="6"` untuk membatasi password minimal 6 karakter.

### 2.3 `proses_register.php`: Validasi

```php
$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

if ($username === '') {
    $errors[] = "Username wajib diisi.";
}

if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}
```

Validasi dilakukan di server menggunakan array `$errors`. Fungsi `strlen()` digunakan untuk memastikan password memiliki minimal 6 karakter.

Validasi server tetap diperlukan meskipun sudah ada `minlength` pada HTML karena validasi HTML dapat dilewati.

### 2.4 Mengecek Username

```php
$cek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$cek->execute(['username' => $username]);

if ($cek->fetch()) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Username sudah digunakan.'
    ];

    header('Location: register.php');
    exit;
}
```

Sebelum data disimpan, sistem memeriksa apakah username sudah digunakan. Meskipun database memiliki constraint `UNIQUE`, pengecekan ini digunakan agar aplikasi dapat memberikan pesan error yang lebih mudah dipahami.

### 2.5 Menyimpan User dan Hash Password

```php
$stmt = $pdo->prepare(
    "INSERT INTO users (nama, username, password, role)
     VALUES (:nama, :username, :password, 'petugas')"
);

$stmt->execute([
    'nama' => $nama,
    'username' => $username,
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);
```

`password_hash()` digunakan untuk mengubah password asli menjadi hash sebelum disimpan ke database.

`PASSWORD_DEFAULT` menggunakan algoritma hashing default yang disediakan PHP. Role ditentukan langsung sebagai `'petugas'` agar pengguna tidak dapat menentukan role sendiri melalui form registrasi.

**Alur registrasi:**

`Form Registrasi → Validasi → Cek Username → Hash Password → INSERT ke users`
