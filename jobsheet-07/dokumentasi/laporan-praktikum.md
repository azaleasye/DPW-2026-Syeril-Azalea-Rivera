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

```php
<?php if (!empty($extra_scripts)): ?>
    <?php foreach ($extra_scripts as $src): ?>
        <script src="<?php echo $src; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
```

Kode tersebut akan memuat setiap file JavaScript yang terdapat di dalam `$extra_scripts`.
