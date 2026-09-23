# Laporan Praktikum Jobsheet 07: PHP Dasar & Form Handling

## 01 Konsep Dasar
Langkah yang dilakukan: 
1. Merubah file `.html` menjadi `.php`
2. Menjalankan server php dari folder jobsheet-07
    ```bash
    php -S localhost:8000
    ```

## 02 Includes Header & Footer
Dengan `include`, bagian yang sama cukup dibuat **satu kali**, kemudian digunakan oleh banyak halaman.
Langkah yang dilakukan: 
1. Menambah folder includes yang berisi header.php dan footer.php
2. Memanggil includes di semua page

Struktur sederhananya:

```text
jobsheet-07/
├── index.php
├── buku/
│   ├── list.php
│   └── tambah.php
├── anggota/
│   ├── list.php
│   └── tambah.php
└── includes/
    ├── header.php
    └── footer.php
```

## 2. `includes/header.php` & `includes/footer.php`
PHP `include` digunakan untuk menggunakan kembali bagian halaman yang sama, seperti `header` dan `footer`, tanpa harus menulisnya berulang kali di setiap file.

### 2.1 `include`
`include` digunakan untuk menyisipkan isi file PHP lain ke dalam file yang sedang dijalankan.

Contoh:
```php
include __DIR__ . '/includes/header.php';
```

Artinya, PHP mengambil isi `header.php` dari folder `includes` dan menyisipkannya ke posisi `include`.

### 2.2 `__DIR__`
`__DIR__` adalah konstanta bawaan PHP yang berisi lokasi folder file PHP yang sedang dijalankan.
Jika `index.php` berada di root folder `jobsheet-07`, maka:

```php
__DIR__ . '/includes/header.php'
```

mengarah ke:
```text
jobsheet-07/includes/header.php
```

Untuk file yang berada di dalam folder `buku`:
```php
include __DIR__ . '/../includes/header.php';
```

Karena harus naik satu folder terlebih dahulu menggunakan `../`.

### 2.3 `$page_title`
`$page_title` digunakan untuk menentukan judul halaman sebelum `header.php` dipanggil.


```php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
```

Nilai `$page_title` tetap dapat digunakan di dalam `header.php`, sehingga judul halaman dapat dibuat berbeda untuk setiap halaman.

```php
<title>
    SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?>
</title>
```

Jika `$page_title` berisi `"Beranda"`, maka hasilnya menjadi:

```text
SIMPUS-Mini | Beranda
```

### 2.4 `$base`
`$base` digunakan untuk menentukan path relatif secara otomatis berdasarkan posisi halaman.

```php
<a href="<?php echo $base; ?>index.php">Beranda</a>
```

Jika halaman berada di root, `$base` bernilai kosong sehingga menjadi:
```text
index.php
```

Jika halaman berada di folder `buku`, `$base` dapat bernilai:
```text
../
```

sehingga link menjadi:
```text
../index.php
```

Dengan begitu, satu `header.php` dapat digunakan oleh halaman yang berada di folder berbeda.

### 2.5 `header.php` dan `footer.php`
`header.php` membuka struktur halaman sampai `<main>`
Sedangkan `footer.php` menutup struktur tersebut, sehingga PHP menyatukan `header`, isi halaman, dan `footer` menjadi satu dokumen HTML sebelum dikirim ke browser.
Keduanya digunakan bersama:

```php
include __DIR__ . '/includes/header.php';
// isi halaman
include __DIR__ . '/includes/footer.php';
```

### 2.6 `$extra_scripts`
`$extra_scripts` digunakan untuk menambahkan JavaScript tambahan pada halaman tertentu.
Kode akan memuat setiap file JavaScript yang terdapat di dalam `$extra_scripts`.
```php
<?php if (!empty($extra_scripts)): ?>
    <?php foreach ($extra_scripts as $src): ?>
        <script src="<?php echo $src; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
```

## 3. Session & Alur Data
Session digunakan PHP untuk menyimpan data sementara sehingga data tetap dapat diakses ketika pengguna berpindah halaman.
Data yang disimpan di `$_SESSION` **bukan penyimpanan permanen** seperti database. Karena itu data dapat hilang ketika session berakhir.

### 3.1 `session_start()`
`session_start()` digunakan untuk mengaktifkan session pada halaman PHP. Perintah ini harus dijalankan sebelum menggunakan `$_SESSION` dan sebelum ada output HTML.

```php
<?php
session_start();
?>
```

Pada jobsheet ini, `session_start()` berada di `header.php`, sehingga otomatis dijalankan oleh setiap halaman yang menggunakan `include` terhadap `header.php`.

### 3.2 `$_SESSION`
`$_SESSION` dapat dianggap sebagai tempat penyimpanan data sementara untuk satu pengguna.
Pada jobsheet ini terdapat tiga data utama:
* `$_SESSION['buku']` → menyimpan array data buku.
* `$_SESSION['anggota']` → menyimpan array data anggota.
* `$_SESSION['flash']` → menyimpan pesan sukses atau gagal sementara.

### 3.3 Menambahkan Data ke Session
Data yang dikirim dari form dapat ditambahkan ke array di dalam session.

Contoh:
```php
$_SESSION['buku'][] = [
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => $tahun,
    'stok' => $stok
];
```

`[]` digunakan untuk menambahkan data baru ke akhir array `$_SESSION['buku']`, sehingga data buku sebelumnya tetap tersimpan, bukan mengganti/menimpa data sebelumnya dgn data baru.

## 4. Memproses Form: `proses_tambah.php`
`proses_tambah.php` digunakan untuk menerima data dari form, melakukan validasi, menyimpan data ke `$_SESSION`, kemudian mengarahkan pengguna ke halaman yang sesuai.

### 4.1 Form dengan `POST`
Form diarahkan ke `proses_tambah.php` menggunakan `method="post"` dan `action`.

```php
<form id="form-tambah" method="post" action="proses_tambah.php">
```

`method="post"` digunakan untuk mengirim data form tanpa menampilkannya di URL, sedangkan `action` menentukan file yang menerima data tersebut.

### 4.2 Mengambil Data dengan `$_POST`
Data form diambil menggunakan `$_POST` berdasarkan atribut `name` pada input.

Contoh:
```php
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$stok = $_POST['stok'] ?? '';
```

Misalnya input memiliki:
```html
<input type="text" name="judul">
```

Maka nilainya dapat diambil dengan:
```php
$_POST['judul']
```

`trim()` digunakan untuk menghapus spasi di awal dan akhir teks, sedangkan `?? ''` memberikan nilai kosong jika data tidak dikirim.

### 4.3 Validasi Server-Side
Sebelum disimpan, data diperiksa kembali di server.

```php
$errors = [];

if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}

if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}

if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}
```

`$errors` digunakan untuk menampung pesan kesalahan. `is_numeric()` digunakan untuk memastikan nilai berupa angka.

### 4.4 Jika Data Tidak Valid
Jika terdapat error, pesan disimpan ke session lalu pengguna dikembalikan ke form.

```php
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}
```

`implode()` menggabungkan beberapa pesan error menjadi satu teks. `header()` melakukan redirect ke `tambah.php`, sedangkan `exit` menghentikan proses PHP setelah redirect.

### 4.5 Jika Data Valid
Jika data valid, data disimpan ke `$_SESSION['buku']`.

```php
if (!isset($_SESSION['buku'])) {
    $_SESSION['buku'] = [];
}

$_SESSION['buku'][] = [
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'stok' => (int) $stok
];
```

`$_SESSION['buku'][]` menambahkan data buku baru ke akhir array tanpa menghapus data sebelumnya. `(int)` digunakan untuk mengubah nilai `tahun` dan `stok` menjadi integer.

Setelah berhasil disimpan, pengguna diarahkan ke `list.php`.

```php
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Buku berhasil ditambahkan.'
];

header('Location: list.php');
exit;
```

### 4.6 Mengapa Validasi Server Penting?
Validasi HTML dan JavaScript berjalan di browser sehingga masih dapat dilewati. Validasi pada `proses_tambah.php` berjalan di server sehingga tetap dilakukan ketika data dikirim ke server.
