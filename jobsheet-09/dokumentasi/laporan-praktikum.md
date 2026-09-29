# Laporan Praktikum Jobsheet 09: CRUD Penuh

## 1. Konsep Dasar CRUD

### 1.1 Pengertian CRUD
CRUD merupakan singkatan dari **Create, Read, Update, dan Delete**, yaitu empat operasi dasar yang digunakan dalam pengelolaan data pada aplikasi.

| Operasi    | Pengertian                    | Perintah SQL | Implementasi |
| ---------- | ----------------------------- | ------------ | ------------ |
| **Create** | Menambahkan data baru         | `INSERT`     | Jobsheet 8   |
| **Read**   | Membaca atau menampilkan data | `SELECT`     | Jobsheet 8   |
| **Update** | Mengubah data yang sudah ada  | `UPDATE`     | Jobsheet 9   |
| **Delete** | Menghapus data                | `DELETE`     | Jobsheet 9   |

Keempat operasi tersebut menjadi dasar dalam pengembangan aplikasi yang menggunakan database. Pada aplikasi **SIMPUS-Mini**, CRUD digunakan untuk mengelola data buku dan anggota.

### 1.2 Perbedaan Create dan Update
Operasi **Create** dan **Update** memiliki alur form yang hampir sama, tetapi terdapat perbedaan pada kondisi awal dan query yang digunakan.

| Aspek          | Create (`tambah.php`) | Update (`edit.php`)          |
| -------------- | --------------------- | ---------------------------- |
| Kondisi form   | Kosong                | Terisi data sebelumnya       |
| Perintah SQL   | `INSERT`              | `UPDATE`                     |
| Identitas data | Tidak diperlukan      | Diperlukan, menggunakan `id` |

Pada operasi **Update**, aplikasi harus mengetahui data mana yang akan diubah. Oleh karena itu, kolom `id` yang sebelumnya digunakan sebagai **primary key** menjadi penting untuk menentukan baris data yang akan diperbarui.

### 1.3 Pengamanan pada Operasi Delete
Operasi **Delete** memerlukan perhatian lebih karena data yang telah dihapus tidak dapat dikembalikan secara langsung melalui aplikasi.

Untuk mengurangi risiko penghapusan data secara tidak sengaja, operasi Delete pada jobsheet ini menggunakan **method `POST`**. Dengan demikian, penghapusan data tidak dilakukan melalui URL atau request `GET` seperti tautan biasa.

### 1.4 Struktur Fitur CRUD
Implementasi CRUD pada tabel `buku` memiliki struktur sebagai berikut:

```text
buku/
├── list.php                    → Read
├── tambah.php                  → Form Create
├── proses_tambah.php           → Proses Create
├── edit.php                    → Form Update
├── proses_edit.php             → Proses Update
└── hapus.php                   → Delete
```
Struktur yang sama juga diterapkan pada folder `anggota`.
Dengan demikian, aplikasi memiliki empat operasi utama, yaitu **Create** untuk menambahkan data, **Read** untuk menampilkan data, **Update** untuk mengubah data, dan **Delete** untuk menghapus data.


## 2. Mengubah Data: `edit.php` & `proses_edit.php`
Fitur **Update** digunakan untuk mengubah data yang sudah tersimpan di database. Prosesnya terdiri dari dua file, yaitu `edit.php` untuk menampilkan data lama pada form dan `proses_edit.php` untuk menjalankan perintah `UPDATE`.

### 2.1 `buku/edit.php`
Halaman `edit.php` mengambil `id` dari URL menggunakan `$_GET` untuk menentukan data buku yang akan diedit.
```php
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header('Location: list.php');
    exit;
}
```

`WHERE id = :id` digunakan untuk mengambil satu data berdasarkan `id`. Hasil query kemudian disimpan ke variabel `$buku` menggunakan `fetch()`.

### 2.2 Mengisi Form dengan Data Lama
Data yang telah diambil dari database dimasukkan ke dalam atribut `value` pada form.

```php
<input type="text"
       id="judul"
       name="judul"
       value="<?php echo $buku['judul']; ?>"
       required>
```

Dengan cara ini, ketika halaman edit dibuka, form sudah berisi data sebelumnya sehingga pengguna hanya perlu mengubah bagian yang diperlukan. Untuk elemen `<select>`, opsi yang sesuai dengan data lama diberi atribut `selected`.

```php
<option value="<?php echo $value; ?>"
    <?php echo $buku['kategori'] === $value ? 'selected' : ''; ?>>
    <?php echo $label; ?>
</option>
```

### 2.3 Mengirim `id` melalui Form
`id` buku disimpan menggunakan input tersembunyi agar tetap dikirim ke server saat form di-submit.

```php
<input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
```

Input dengan `type="hidden"` tidak terlihat oleh pengguna, tetapi nilainya tetap dikirim melalui `POST`. `id` tersebut digunakan untuk menentukan data yang akan diperbarui.

### 2.4 `proses_edit.php`
File `proses_edit.php` menerima data dari form menggunakan `$_POST`, melakukan validasi, kemudian menjalankan perintah `UPDATE`.

```php
$id = $_POST['id'] ?? null;

$stmt = $pdo->prepare(
    "UPDATE buku SET
        judul = :judul,
        pengarang = :pengarang,
        tahun = :tahun,
        isbn = :isbn,
        stok = :stok,
        kategori = :kategori
     WHERE id = :id"
);

$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori,
    'id' => $id
]);
```

Perintah `UPDATE` digunakan untuk mengubah data yang sudah ada. Bagian `SET` menentukan kolom yang diperbarui, sedangkan `WHERE id = :id` memastikan hanya data dengan `id` yang sesuai yang diubah.
Validasi pada `proses_edit.php` tetap menggunakan aturan yang sama seperti `proses_tambah.php`. Jika validasi gagal, pengguna diarahkan kembali ke `edit.php` dengan `id` data yang sedang diedit.

### 2.5 Kesimpulan

Alur proses Update dapat diringkas sebagai berikut:
```text
list.php
   ↓
edit.php?id=3
   ↓
SELECT data berdasarkan id
   ↓
Form terisi data lama
   ↓
Submit form
   ↓
proses_edit.php
   ↓
UPDATE ... WHERE id = 3
   ↓
Data berhasil diperbarui
```

Hal yang paling penting pada operasi `UPDATE` adalah penggunaan klausa **`WHERE`** agar perubahan hanya dilakukan pada data yang dituju.

## 3. Menghapus Data: `hapus.php`
`hapus.php` digunakan untuk menghapus data buku dari database. Meskipun kode yang digunakan relatif singkat, proses Delete perlu memperhatikan metode HTTP dan penggunaan klausa `WHERE`.

### 3.1 Kode `hapus.php`
```php
<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Buku berhasil dihapus.'
    ];
}

header('Location: list.php');
exit;
```

### 3.2 Penggunaan Method `POST`
Proses Delete menggunakan method **`POST`**, bukan `GET`. Hal ini dilakukan agar penghapusan data tidak dapat dipicu hanya dengan membuka sebuah URL.

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}
```

`$_SERVER['REQUEST_METHOD']` digunakan untuk mengetahui metode HTTP yang digunakan. Jika request bukan `POST`, pengguna diarahkan kembali ke `list.php` tanpa menjalankan proses penghapusan.

### 3.3 Menghapus Data dengan `DELETE`
Data yang dikirim melalui form diterima menggunakan `$_POST`.

```php
$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare(
        "DELETE FROM buku WHERE id = :id"
    );

    $stmt->execute(['id' => $id]);
}
```

Perintah `DELETE` digunakan untuk menghapus data dari tabel `buku`. Klausa `WHERE id = :id` memastikan hanya data dengan `id` yang sesuai yang dihapus. Penggunaan `WHERE` sangat penting. Jika `DELETE FROM buku` dijalankan tanpa `WHERE`, seluruh data dalam tabel dapat terhapus.

### 3.4 Form untuk Menghapus Data
Pada `list.php`, proses Delete dipicu menggunakan form dengan method `POST`.

```html
<form class="form-hapus" method="post" action="hapus.php">
    <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
    <button type="submit" class="btn-hapus">Hapus</button>
</form>
```

Form tersebut memiliki tiga bagian utama:
* `method="post"` digunakan agar request menggunakan `POST`.
* `<input type="hidden">` menyimpan `id` buku yang akan dihapus.
* `<button type="submit">` digunakan untuk mengirim form ke `hapus.php`.

Setelah proses penghapusan selesai, pengguna diarahkan kembali ke `list.php` dan flash message digunakan untuk menampilkan informasi bahwa data berhasil dihapus.

### 3.5 Alur Proses Delete
```text
list.php
   ↓
Klik tombol Hapus
   ↓
Form POST mengirim id
   ↓
hapus.php
   ↓
Validasi method POST
   ↓
DELETE FROM buku WHERE id = :id
   ↓
Flash message
   ↓
Kembali ke list.php
```

Dengan demikian, operasi Delete dilakukan secara terarah menggunakan `POST` dan `WHERE id` untuk memastikan hanya data yang dipilih yang dihapus.
