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

## 4. JavaScript: Konfirmasi Hapus via Event `submit`

Pada jobsheet sebelumnya, konfirmasi penghapusan dilakukan menggunakan event `click`. Setelah tombol Hapus diubah menjadi form dengan `method="post"`, konfirmasi perlu dilakukan pada event `submit` agar pengiriman data ke server dapat dibatalkan.

### 4.1 Kode `initHapusConfirm()`

```js id="r7jv2k"
function initHapusConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;

        if (!form.classList.contains("form-hapus")) return;

        const row = form.closest("tr");
        const nama = row
            ? row.querySelector("td")?.textContent
            : "data ini";

        const yakin = confirm(
            "Yakin ingin menghapus \"" + nama + "\"?"
        );

        if (!yakin) {
            e.preventDefault();
        }
    });
}
```

Fungsi tersebut menggunakan **event delegation** pada `document` untuk menangani form dengan class `form-hapus`.

### 4.2 Perubahan dari Event `click` ke `submit`
Tombol berada di dalam form yang benar-benar mengirim data ke server. Oleh karena itu, event `submit` digunakan agar konfirmasi dapat dilakukan **sebelum form dikirim ke server**.

`e.target` pada event `submit` merupakan elemen `<form>` yang sedang dikirim. Pemeriksaan `classList.contains("form-hapus")` memastikan fungsi hanya bekerja pada form Hapus.

### 4.3 Membatalkan Submit dengan `preventDefault()`
```js id="0b3h4p"
const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");

if (!yakin) {
    e.preventDefault();
}
```
Jika pengguna memilih **OK**, form akan dilanjutkan dan dikirim ke `hapus.php`.
Jika pengguna memilih **Cancel**, `e.preventDefault()` digunakan untuk membatalkan proses submit sehingga data tidak jadi dihapus.

### 4.4 Mencari Baris Data
```js id="6m2j8q"
const row = form.closest("tr");
```
Method `.closest("tr")` digunakan untuk mencari elemen `<tr>` yang menjadi induk dari form Hapus. Dari baris tersebut, nama atau judul data dapat digunakan untuk ditampilkan pada pesan konfirmasi.

### 4.5 Perbandingan dengan Jobsheet Sebelumnya

| Aspek               | Jobsheet-05/06      | Jobsheet-09            |
| ------------------- | ------------------- | ---------------------- |
| Event               | `click`             | `submit`               |
| Aksi setelah OK     | `row.remove()`      | Form dikirim ke server |
| Aksi setelah Cancel | Tidak ada           | `e.preventDefault()`   |
| Penghapusan data    | Hanya dari tampilan | Database               |

## 5. Pagination dan Pencarian Sisi Server
Pada jobsheet ini, `buku/list.php` dikembangkan dengan fitur **pagination** dan **pencarian sisi server**. Pagination membatasi jumlah data yang ditampilkan pada setiap halaman, sedangkan pencarian digunakan untuk menampilkan data berdasarkan kata kunci.

### 5.1 Query Pagination dan Pencarian

```php
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare(
        "SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw"
    );
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM buku
         WHERE judul ILIKE :kw
         ORDER BY id DESC
         LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = $pdo
        ->query("SELECT COUNT(*) FROM buku")
        ->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM buku
         ORDER BY id DESC
         LIMIT :limit OFFSET :offset"
    );
}

$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = max(
    1,
    (int) ceil($totalRows / $perPage)
);
```

### 5.2 Pagination
Pagination digunakan untuk membagi data menjadi beberapa halaman. Pada jobsheet ini, jumlah data yang ditampilkan adalah **5 data per halaman**.

```php
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
```

`$page` menentukan halaman yang sedang dibuka, sedangkan `$offset` menentukan jumlah data yang dilewati sebelum mengambil data.
Contohnya:

| Halaman | Offset | Data       |
| ------- | -----: | ---------- |
| 1       |      0 | Data 1–5   |
| 2       |      5 | Data 6–10  |
| 3       |     10 | Data 11–15 |

### 5.3 `LIMIT` dan `OFFSET`
Pagination menggunakan `LIMIT` dan `OFFSET` pada query SQL.

```sql
SELECT * FROM buku
ORDER BY id DESC
LIMIT :limit OFFSET :offset
```

* `LIMIT` membatasi jumlah data yang diambil.
* `OFFSET` menentukan jumlah data yang dilewati.
* `ORDER BY id DESC` menjaga urutan data tetap konsisten.

### 5.4 `bindValue()` dan `PDO::PARAM_INT`
```php
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
```

`bindValue()` digunakan untuk mengisi parameter pada prepared statement. `PDO::PARAM_INT` digunakan agar nilai `limit` dan `offset` diproses sebagai bilangan integer.

### 5.5 Pencarian Sisi Server dengan `ILIKE`
Pencarian dilakukan menggunakan parameter `q` dari URL.

```php
$keyword = trim($_GET['q'] ?? '');

$stmt = $pdo->prepare(
    "SELECT * FROM buku
     WHERE judul ILIKE :kw
     ORDER BY id DESC
     LIMIT :limit OFFSET :offset"
);

$stmt->bindValue('kw', '%' . $keyword . '%');
```

`ILIKE` merupakan operator PostgreSQL untuk pencarian teks yang **tidak membedakan huruf besar dan kecil**.
Karakter `%` digunakan sebagai wildcard. Contohnya, kata kunci `bumi` dengan pola `%bumi%` dapat menemukan judul yang mengandung kata tersebut. Jumlah data hasil pencarian juga dihitung menggunakan kondisi yang sama:

```php
SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw
```
Hal ini diperlukan agar jumlah halaman sesuai dengan jumlah hasil pencarian.

### 5.6 Menghitung Jumlah Halaman

```php
$totalPages = max(
    1,
    (int) ceil($totalRows / $perPage)
);
```

`ceil()` digunakan untuk membulatkan hasil pembagian ke atas. Misalnya terdapat 12 data dengan 5 data per halaman, maka diperlukan 3 halaman.

### 5.7 Form Pencarian
Form pencarian menggunakan method `GET` karena pencarian hanya membaca data dan tidak mengubah database.

```html
<form method="get" action="list.php">
    <label for="search-input">Cari Judul Buku</label>
    <input
        type="text"
        id="search-input"
        name="q"
        value="<?php echo $keyword; ?>"
        placeholder="Ketik judul buku..."
    >
    <button type="submit">Cari</button>
</form>
```

Penggunaan `value="<?php echo $keyword; ?>"` membuat kata kunci tetap tampil pada input setelah pencarian dilakukan.

### 5.8 Navigasi Halaman
Navigasi halaman dibuat menggunakan perulangan `for`.
```php
<nav class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a
            href="list.php?page=<?php echo $i; ?><?php
                echo $keyword !== ''
                    ? '&q=' . urlencode($keyword)
                    : '';
            ?>"
            class="<?php echo $i === $page ? 'active' : ''; ?>"
        >
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</nav>
```

Setiap nomor halaman dibuat menjadi tautan. Jika pencarian sedang digunakan, `urlencode($keyword)` digunakan agar kata kunci tetap terbawa saat berpindah halaman.

Class `active` diberikan pada halaman yang sedang dibuka sehingga dapat diberi tampilan khusus melalui CSS.

### 5.9 Alur Pagination dan Pencarian
```text
URL page dan q
      ↓
Mengambil halaman dan kata kunci
      ↓
Menghitung jumlah data
      ↓
SELECT dengan LIMIT dan OFFSET
      ↓
Menampilkan hasil
      ↓
Membuat navigasi halaman
```

Dengan fitur ini, `list.php` dapat menampilkan data dalam jumlah terbatas sekaligus menyediakan pencarian berdasarkan judul buku tanpa mengambil seluruh data ke halaman terlebih dahulu.

## 6. CSS Pendukung Fitur Baru
CSS tambahan digunakan untuk mendukung fitur **Edit, Hapus, pagination, dan pencarian**.

### 6.1 Tautan Edit

Tautan `<a>` untuk Edit diberi tampilan seperti tombol menggunakan `display: inline-block`, `padding`, `border-radius`, dan `font-size`. Selector dengan koma digunakan agar tombol lama dan tautan Edit memiliki warna yang sama.

### 6.2 Form Hapus
Form Hapus dibuat `inline` agar tombol Hapus tetap sejajar dengan tautan Edit dalam satu sel `<td>`. Tanpa aturan ini, `<form>` sebagai elemen block dapat membuat tombol berada di baris baru.

### 6.3 Navigasi Pagination
* `display: flex` menyusun nomor halaman secara horizontal.
* `gap` memberikan jarak antar tombol.
* `.pagination a` memberikan bentuk tombol pada setiap nomor halaman.
* `.pagination a.active` menandai halaman yang sedang dibuka dengan warna biru.

### 6.4 Form Pencarian
* `display: flex` menyusun form pencarian secara horizontal.
* `gap` memberikan jarak antara input dan tombol.
* `align-items: flex-end` menyamakan posisi bagian bawah input dan tombol.
* `.search-box button` memberikan tampilan tombol biru dengan teks putih.
* `cursor: pointer` mengubah kursor saat tombol diarahkan.
