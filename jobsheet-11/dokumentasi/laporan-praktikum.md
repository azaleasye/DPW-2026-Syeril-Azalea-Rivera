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

## 2. XSS & Fungsi `e()`
### 2.1 Pengertian XSS
**XSS (Cross-Site Scripting)** adalah celah keamanan yang memungkinkan penyerang menyisipkan kode HTML atau JavaScript melalui input aplikasi. Kode tersebut kemudian dapat dijalankan di browser pengguna lain.
Contohnya, pengguna memasukkan:

```html
<script>alert('halaman ini sudah diretas!')</script>
```

Jika data tersebut langsung ditampilkan dengan:
```php
<td><?php echo $buku['judul']; ?></td>
```

browser dapat menganggapnya sebagai kode HTML/JavaScript dan menjalankannya.

### 2.2 Fungsi `e()` sebagai Perlindungan XSS
Untuk mencegah XSS, dibuat helper `e()` pada `includes/helpers.php`:
```php
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
```

Fungsi `e()` menggunakan `htmlspecialchars()` untuk mengubah karakter khusus HTML seperti `<`, `>`, `"`, dan `'` menjadi HTML entity. 
```

akan ditampilkan sebagai teks biasa, bukan dijalankan sebagai JavaScript.

Parameter yang digunakan:
* `(string)` memastikan nilai diproses sebagai teks.
* `?? ''` memberikan nilai kosong jika data `null`.
* `ENT_QUOTES` melakukan escape pada tanda kutip tunggal dan ganda.
* `UTF-8` memastikan karakter ditangani dengan encoding yang benar.

### 2.3 Penggunaan `e()` pada Output
```php
<td><?php echo e($buku['judul']); ?></td>
```

`e()` digunakan pada data yang berasal dari database atau input pengguna, seperti:
* Judul buku
* Pengarang
* Nama anggota
* Alamat
* Nomor HP
* Nilai pencarian
* Nama petugas pada navbar

### 2.4 `e()` pada Atribut `value`
Data yang dimasukkan ke atribut HTML juga perlu di-escape:
```php
<input type="text"
       id="judul"
       name="judul"
       value="<?php echo e($buku['judul']); ?>"
       required>
```
Hal ini penting karena karakter seperti tanda kutip dapat memengaruhi struktur atribut HTML. `ENT_QUOTES` pada `e()` membantu mencegah data pengguna menyisipkan atribut atau kode HTML tambahan.

### 2.5 Kolom Angka Tidak Wajib Menggunakan `e()`
Kolom seperti `tahun` dan `stok` bertipe `INTEGER` di database sehingga hanya menyimpan angka.
Penggunaan `e()` pada nilai angka tidak salah, tetapi perlindungan XSS terutama diperlukan pada data teks yang berasal dari pengguna.

Penerapan XSS dan Fungsi 'e()' dilakukan pada Buku: list & edit, serta Anggota: list & edit.

## 3. CSRF & Token Verifikasi
### 3.1 Pengertian CSRF
**CSRF (Cross-Site Request Forgery)** adalah celah keamanan yang memungkinkan situs lain mengirimkan request ke aplikasi atas nama pengguna yang sedang login tanpa sepengetahuan pengguna.

Contohnya, situs lain dapat membuat form tersembunyi yang mengirim request `POST` ke `hapus.php`. Walaupun aplikasi sudah membatasi penghapusan hanya menggunakan `POST`, request tersebut tetap dapat dikirim oleh situs lain.

Karena browser tetap mengirim cookie session pengguna yang sedang login, server dapat menganggap request tersebut sebagai request yang sah. Oleh karena itu, diperlukan **CSRF token** sebagai lapisan keamanan tambahan.

### 3.2 Konsep Token CSRF
CSRF token adalah nilai acak yang dibuat dan disimpan oleh server dalam session. Token tersebut kemudian disertakan pada setiap form yang melakukan request `POST`. Saat request diterima, server membandingkan token dari form dengan token yang tersimpan di session.

Alur CSRF:
```text
Form aplikasi → Token CSRF → Server
                           ↓
                    Verifikasi token
                    ↓             ↓
                  Valid         Tidak valid
                    ↓             ↓
                 Proses        HTTP 403
```

Situs lain tidak mengetahui token yang benar sehingga request palsu akan ditolak.

### 3.3 Membuat Token dengan `csrf_token()`
Dilakukan pada Includes: csrf.php

```php
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
```

Fungsi tersebut:
* `random_bytes(32)` menghasilkan data acak yang aman secara kriptografis.
* `bin2hex()` mengubah data tersebut menjadi teks hexadecimal.
* Token disimpan dalam `$_SESSION['csrf_token']`.
* Token hanya dibuat sekali selama session masih aktif.

### 3.4 Menyisipkan Token ke Form
Dilakukan pada Includes: csrf.php
Token dimasukkan ke dalam form menggunakan `csrf_field()`:

```php
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}
```

Kemudian digunakan di dalam form:
```php
<?php echo csrf_field(); ?>
```

Token disimpan sebagai `input type="hidden"` sehingga tidak terlihat oleh pengguna, tetapi tetap dikirim bersama request.

### 3.5 Memverifikasi Token dengan `csrf_verify()`
Pada sisi server, token diperiksa menggunakan:
```php
function csrf_verify()
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        $token === '' ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $token)
    ) {
        http_response_code(403);
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.');
    }
}
```

Fungsi Penting:
* `$_POST['csrf_token']` mengambil token dari form.
* `hash_equals()` membandingkan token secara aman.
* `http_response_code(403)` memberikan status **403 Forbidden** jika token tidak valid.
* `die()` menghentikan proses sehingga operasi database tidak dijalankan.

### 3.6 Memanggil `csrf_verify()` pada Proses Data
Setiap proses `POST` yang dilindungi memanggil `csrf_verify()` sebelum mengolah data.

Contoh:
```php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();
$judul = trim($_POST['judul'] ?? '');
```

Urutannya adalah:
1. `auth.php` memastikan pengguna sudah login.
2. `csrf.php` menyediakan fungsi CSRF.
3. `csrf_verify()` memeriksa token.
4. Data baru diproses jika token valid.

Pemeriksaan `auth.php` dilakukan lebih dahulu agar pengguna yang belum login langsung diarahkan ke halaman login.
