# Laporan Praktikum Jobsheet 08: Koneksi PostgreSQL

## 1. Konsep Dasar Database & SQL 
Jobsheet ini mulai menggunakan koneksi database, agar data bisa disimpan tanpa kehilangan saat memulai session baru. Data tersebut disimpan di Database Management System/DMBS, dan disini menggunakan PostgreSQL.

## 2. Skema Database: 01_buku_anggota_sql
| Kolom       | Tipe Data      | Aturan               | Fungsi         |
| ----------- | -------------- | -------------------- | -------------- |
| `id`        | `SERIAL`       | `PRIMARY KEY`        | ID unik buku   |
| `judul`     | `VARCHAR(255)` | `NOT NULL`           | Judul buku     |
| `pengarang` | `VARCHAR(255)` | `NOT NULL`           | Nama pengarang |
| `tahun`     | `INTEGER`      | `NOT NULL`           | Tahun terbit   |
| `isbn`      | `VARCHAR(50)`  | -                    | Nomor ISBN     |
| `stok`      | `INTEGER`      | `NOT NULL DEFAULT 0` | Jumlah buku    |
| `kategori`  | `VARCHAR(50)`  | -                    | Kategori buku  |

**Bagaimana Kolom-Kolom Ini Berhubungan dengan Kode PHP?**
**nama setiap kolom** di sini: `judul`, `pengarang`, `tahun`,`isbn`, `stok`, `kategori` untuk tabel `buku`; `nama`, `no_anggota`, `alamat`, `no_hp` untuk tabel `anggota` — **persis sama** dengan nama kunci array asosiatif yang dipakai `proses_tambah.php` pada jobsheet-07

## 3. Persiapan Database Sebelum Menjalankan
Langkah yang dilakukan: 
1. Langkah 1: Pastikan PostgreSQL & Ekstensi PHP Siap
-  Memastikan PostgreSQL sudah terinstall
-  Install extenstion psql dan pdo_sql di laragon
2. Langkah 2: Membuat Database
    ```bash
    createdb simpus_mini
    ```
3. Langkah 3: Menjalankan Skema
    ```bash
    psql -d simpus_mini -f sql/01_buku_anggota.sql
    ```
4. Langkah 4: Menyesuaikan Kredensial
   Menyesuaikan kredensial di `includes/koneksi.php` (`$user`, `$pass`) dengan environment lokal.

# 4. Koneksi PHP ke Database: `koneksi.php`

`koneksi.php` adalah file yang menjadi **penghubung antara PHP dan database PostgreSQL**.

## 4.2 Konfigurasi Database

```php
$host = "localhost";
$port = "5432";
$db   = "simpus_mini";
$user = "postgres";
$pass = "postgres";
```

| Variabel | Fungsi                                                  |
| -------- | ------------------------------------------------------- |
| `$host`  | Alamat PostgreSQL, `localhost` berarti komputer sendiri |
| `$port`  | Port PostgreSQL, biasanya `5432`                        |
| `$db`    | Nama database                                           |
| `$user`  | Username PostgreSQL                                     |
| `$pass`  | Password PostgreSQL                                     |

## 4.3 Membuat Koneksi

```php
$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
```

`new PDO()` membuat **objek koneksi** ke database.

```text
pgsql:host=...;port=...;dbname=...
```
disebut **DSN (Data Source Name)** yang berisi jenis database, host, port, dan nama database.
Objek `$pdo` nantinya digunakan untuk menjalankan query seperti `SELECT`, `INSERT`, `UPDATE`, dan `DELETE`.

## 4.4 Menangani Error

```php
try {
    $pdo = new PDO(...);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
```

* `try` → menjalankan kode yang mungkin gagal.
* `catch` → menangkap error dari PDO.
* `PDOException` → jenis error dari PDO.
* `die()` → menghentikan program dan menampilkan pesan error.
* `ERRMODE_EXCEPTION` → membuat error query ditangani sebagai exception.

## 4.5 Memanggil `koneksi.php`

Halaman lain yang membutuhkan database dapat menggunakan:

```php
require __DIR__ . '/includes/koneksi.php';
```

`require` digunakan karena `koneksi.php` **wajib berhasil dimuat**. Jika file tidak ditemukan, PHP akan menghentikan eksekusi.
Jadi, **`koneksi.php` menyediakan `$pdo` sebagai jembatan agar halaman PHP dapat berkomunikasi dengan database PostgreSQL.**

# 5. Menyimpan Data: Prepared Statement & `INSERT`
Data akan disimpan **langsung ke database PostgreSQL** menggunakan `INSERT` dan **prepared statement**.

## 5.1 Kode `buku/proses_tambah.php`

```php
require __DIR__ . '/../includes/koneksi.php';

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
     RETURNING id"
);

$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori,
]);
```

Validasi seperti pengecekan judul, tahun, dan stok **tetap sama seperti jobsheet-07**. Yang berubah hanya cara menyimpan datanya.

## 5.2 `INSERT` dan `prepare()`
Artinya menambahkan **data baru ke tabel `buku`**.

### `VALUES`

```sql
VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
```

Bagian `:judul`, `:pengarang`, dan lainnya disebut **placeholder**. Placeholder akan diisi dengan nilai sebenarnya saat `execute()` dijalankan.

### `RETURNING id`

```sql
RETURNING id
```

Khusus PostgreSQL, digunakan untuk mendapatkan `id` yang otomatis dibuat setelah data berhasil ditambahkan.

### `prepare()`

```php
$pdo->prepare(...)
```

Digunakan untuk **menyiapkan query terlebih dahulu**, tetapi belum menjalankannya. Hasilnya disimpan dalam:

```php
$stmt
```

## 5.3 Apa Itu Prepared Statement?
Prepared statement menjalankan query dalam dua tahap:
1. prepare()
    Menyiapkan struktur query
2. execute()
   Mengisi data dan menjalankan query

Cara ini lebih aman daripada memasukkan nilai langsung ke dalam string SQL karena membantu mencegah **SQL Injection**. 
Contoh yang tidak disarankan:
```php
$pdo->query("INSERT INTO buku (judul) VALUES ('" . $judul . "')");
```

Dengan prepared statement, data pengguna diperlakukan sebagai **data**, bukan sebagai bagian dari perintah SQL.

## 5.4 Menjalankan Query dengan `execute()`
```php
$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori,
]);
```

`execute()` digunakan untuk **menjalankan query yang sudah disiapkan**. Nama pada array harus sesuai dengan placeholder. Setelah `execute()` berhasil, data benar-benar tersimpan di tabel `buku`.

# 6. Membaca Data: `SELECT`
Setelah data disimpan menggunakan `INSERT`, data perlu dibaca kembali untuk ditampilkan di halaman. Pada bab ini digunakan SQL `SELECT`.

## 6.1 `buku/list.php`: Mengambil Data Buku
Mengambil data dari database
```php
require __DIR__ . '/../includes/koneksi.php';

$daftarBuku = $pdo
    ->query("SELECT * FROM buku ORDER BY id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
```

### `SELECT * FROM buku`
```sql
SELECT * FROM buku
```

Artinya mengambil **semua kolom dan semua data** dari tabel `buku`.

### `ORDER BY id DESC`
```sql
ORDER BY id DESC
```

Mengurutkan berdasarkan `id` dari **terbesar ke terkecil**. Karena `id` bertambah setiap ada data baru, buku yang **baru ditambahkan akan muncul paling atas**.

## 6.2 `query()` untuk Menjalankan SELECT
```php
$pdo->query("SELECT * FROM buku ORDER BY id DESC");
```

`query()` digunakan untuk menjalankan SQL yang **tidak menggunakan data dari pengguna**.
Contohnya:
```sql
SELECT * FROM buku
```

Query tersebut selalu memiliki struktur yang sama sehingga tidak membutuhkan placeholder.

## 6.3 Mengambil Hasil dengan `fetchAll()`
`fetchAll()` mengambil **semua baris hasil query** dan mengubahnya menjadi array PHP.
`PDO::FETCH_ASSOC` membuat setiap baris menjadi **array asosiatif** berdasarkan nama kolom.

Contohnya:
```php
$buku['judul']
$buku['pengarang']
$buku['tahun']
```

Hasilnya memiliki struktur yang mirip dengan data `$_SESSION['buku']` pada jobsheet-07.

## 6.4 Menampilkan Data ke Tabel
Kode `foreach` untuk menampilkan data **tidak perlu berubah**:

```php
<?php foreach ($daftarBuku as $buku): ?>
<tr>
    <td><?php echo $buku['judul']; ?></td>
    ...
</tr>
<?php endforeach; ?>
```

Hal ini karena `$daftarBuku` tetap berbentuk array asosiatif meskipun sumber datanya sekarang berasal dari database.

Jadi:

```text
Database
   ↓
SELECT
   ↓
fetchAll()
   ↓
$daftarBuku
   ↓
foreach
   ↓
Tabel HTML
```

## 6.5 `index.php`: Menghitung Jumlah Data

Untuk dashboard, jumlah buku dan anggota dapat dihitung langsung dari database:
```php
require __DIR__ . '/includes/koneksi.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
```

### `COUNT(*)`
Digunakan untuk **menghitung jumlah baris** dalam tabel `buku`. Database langsung menghitung jumlah datanya sehingga kita tidak perlu mengambil semua data terlebih dahulu.

### `fetchColumn()`
Digunakan untuk mengambil **satu nilai** dari hasil query. Cocok digunakan dengan `COUNT(*)` karena hasilnya hanya berupa satu angka.

## 6.6 Perbedaan dengan Jobsheet-07
Sebelumnya:
```php
$totalBuku = count($_SESSION['buku'] ?? []);
```

Sekarang:
```php
$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
```

Perbedaannya:
```text
Jobsheet-07
Session → count() → jumlah data

Jobsheet-08
Database → COUNT(*) → jumlah data
```

Jadi konsepnya tetap sama, tetapi sumber datanya berubah dari **session sementara** menjadi **database**.
