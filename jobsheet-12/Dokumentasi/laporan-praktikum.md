# Dokumentasi Jobsheet 12: Integrasi Modul Peminjaman

## 1. Konsep Dasar: Relasi Tabel & Transaction
Jobsheet 12 memperkenalkan dua konsep utama, yaitu **relasi antar tabel** dan **transaction**. Keduanya digunakan untuk membangun fitur peminjaman buku yang melibatkan beberapa tabel sekaligus.

### 1.1 Peminjaman Membutuhkan Tabel Sendiri
Tabel `buku` dan `anggota` sebelumnya berdiri sendiri. Pada proses peminjaman, diperlukan hubungan antara **buku yang dipinjam** dan **anggota yang meminjam**, serta data transaksi seperti tanggal pinjam, tanggal kembali, dan status. Karena itu dibuat tabel `peminjaman` untuk menyimpan hubungan tersebut.

Ditambahkan script migrasi 03_peminjaman.sql.

### 1.2 Foreign Key
Relasi antara tabel dibuat menggunakan **foreign key**:

```sql id="8ewwjo"
buku_id INTEGER NOT NULL REFERENCES buku(id),
anggota_id INTEGER NOT NULL REFERENCES anggota(id),
```

`REFERENCES buku(id)` memastikan nilai `buku_id` harus berasal dari `id` yang benar-benar ada di tabel `buku`. Hal yang sama berlaku untuk `anggota_id`. Dengan foreign key, database dapat mencegah data peminjaman yang merujuk ke buku atau anggota yang tidak tersedia.

### 1.3 Transaction
Proses peminjaman melibatkan minimal dua operasi database:
1. `INSERT` data peminjaman.
2. `UPDATE` stok buku.

Jika salah satu operasi gagal, data dapat menjadi tidak konsisten. Misalnya, peminjaman berhasil dicatat tetapi stok tidak berkurang. **Transaction** digunakan agar beberapa operasi tersebut dianggap sebagai satu kesatuan. Jika semuanya berhasil, perubahan disimpan dengan `commit`. Jika terjadi kesalahan, perubahan dibatalkan dengan `rollBack`.
Konsep utamanya:
```text id="v1m4qz"
beginTransaction()
       ↓
   INSERT peminjaman
       ↓
   UPDATE stok
       ↓
    commit()
```

Jika terjadi error:
```text id="n7o4sx"
rollBack()
```

### 1.4 Race Condition
**Race condition** terjadi ketika dua proses berjalan hampir bersamaan dan menggunakan data yang sama.
Contohnya, stok sebuah buku hanya **1**, kemudian dua petugas melakukan peminjaman secara bersamaan. Jika keduanya membaca stok `1` sebelum stok diperbarui, keduanya dapat menganggap buku masih tersedia.
Untuk menceah kondisi tersebut digunakan:

```sql id="k4kz4p"
SELECT ... FOR UPDATE
```

Perintah ini mengunci baris yang sedang diproses selama transaction sehingga proses lain tidak dapat mengubahnya secara bersamaan.

### 1.5 Struktur Modul Peminjaman

```text id="j7lqv0"
peminjaman/
├── tambah.php + proses_tambah.php
├── kembali.php + proses_kembali.php
└── riwayat.php
```

Fungsinya:
* `tambah.php` dan `proses_tambah.php` → mencatat peminjaman dan mengurangi stok.
* `kembali.php` dan `proses_kembali.php` → mencatat pengembalian dan menambah stok.
* `riwayat.php` → menampilkan riwayat peminjaman.

Karena fitur tersebut menggunakan data dari tabel `peminjaman`, `buku`, dan `anggota`, diperlukan **relasi tabel** dan `JOIN` untuk menggabungkan data dari beberapa tabel.

## 2. Skema `peminjaman` & Relasi Antar Tabel
### 2.1 Skema Tabel `peminjaman`
Tabel `peminjaman` digunakan untuk menghubungkan data buku dan anggota serta menyimpan informasi transaksi peminjaman.

```sql
CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    buku_id INTEGER NOT NULL REFERENCES buku(id),
    anggota_id INTEGER NOT NULL REFERENCES anggota(id),
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_kembali DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam'
);
```

### 2.2 Foreign Key `buku_id` dan `anggota_id`
Kedua kolom tersebut merupakan **foreign key** yang menghubungkan tabel `peminjaman` dengan tabel `buku` dan `anggota`.
* `buku_id` → mengacu pada `buku(id)`.
* `anggota_id` → mengacu pada `anggota(id)`.

Dengan aturan tersebut, database hanya menerima peminjaman yang memiliki buku dan anggota yang valid.

### 2.3 Kolom Tanggal
* `DATE` digunakan untuk menyimpan tanggal tanpa waktu.
* `tanggal_pinjam` otomatis menggunakan tanggal hari ini melalui `CURRENT_DATE`.
* `tanggal_kembali` boleh bernilai `NULL` karena belum diketahui ketika buku baru dipinjam.

### 2.4 Kolom `status`
Kolom `status` menyimpan kondisi transaksi:
* `dipinjam` → status awal ketika transaksi dibuat.
* `dikembalikan` → status setelah buku dikembalikan.

### 2.5 Relasi Antar Tabel
Tabel `peminjaman` berfungsi sebagai penghubung antara `buku` dan `anggota`. Satu buku dapat memiliki banyak riwayat peminjaman dari waktu ke waktu, sedangkan satu anggota dapat memiliki banyak transaksi peminjaman.

### 2.6 Menjalankan Script Query
Skema dapat dijalankan menggunakan:

```bash
psql -d simpus_mini -f sql/03_peminjaman.sql
```

## 3. Peminjaman Baru & Database Transaction

### 3.1 `peminjaman/tambah.php`: Menyiapkan Form Peminjaman
Halaman `tambah.php` digunakan untuk membuat transaksi peminjaman baru. Data anggota dan buku diambil dari database lalu ditampilkan dalam bentuk dropdown

* **`auth.php`**: memastikan hanya pengguna yang sudah login yang dapat mengakses halaman peminjaman.
* **`koneksi.php`**: menyediakan koneksi ke database melalui `$pdo`.
* **Data anggota**: mengambil daftar anggota dari tabel `anggota` untuk digunakan sebagai pilihan peminjam.
* **Data buku tersedia**: mengambil buku dengan `stok > 0` agar hanya buku yang masih tersedia yang dapat dipinjam.
* **Flash message**: menampilkan informasi keberhasilan atau kegagalan dari proses sebelumnya.
* **Pengecekan data kosong**: memberikan informasi jika belum ada anggota atau tidak ada buku yang tersedia.
* **CSRF token**: melindungi form dari serangan Cross-Site Request Forgery.
* **Fungsi `e()`**: mengamankan data yang ditampilkan dari database agar karakter HTML/JavaScript tidak dieksekusi.
* **`required` pada form**: memastikan anggota dan buku harus dipilih sebelum form dikirim.
* **`anggota_id` dan `buku_id`**: mengirim ID anggota dan buku yang dipilih ke `proses_tambah.php`.

### 3.2 `proses_tambah.php`: Memproses Data Peminjaman
`proses_tambah.php` berfungsi menerima data dari form, melakukan validasi, mencatat peminjaman, dan mengurangi stok buku.

* **`auth.php`**: memastikan proses hanya dapat dilakukan oleh pengguna yang sudah login.
* **`csrf.php` dan `csrf_verify()`**: memverifikasi token keamanan sebelum data diproses.
* **`$_POST`**: mengambil ID anggota dan buku yang dikirim dari form.
* **Validasi input**: memastikan anggota dan buku sudah dipilih sebelum proses database dilakukan.
* **Flash message**: memberikan informasi kepada pengguna jika proses berhasil atau gagal.

### 3.3 Database Transaction
Database transaction digunakan karena proses peminjaman melibatkan beberapa perubahan database yang harus dilakukan sebagai satu kesatuan.

Transaction terdiri dari:
* **`beginTransaction()`**: memulai transaction dan menandai bahwa perubahan berikutnya belum disimpan secara permanen.
* **`commit()`**: menyimpan seluruh perubahan jika semua proses berhasil.
* **`rollBack()`**: membatalkan seluruh perubahan jika terjadi error.

Fungsi utamanya adalah menjaga agar data peminjaman dan stok buku tetap konsisten. Jika pencatatan peminjaman gagal, stok buku juga tidak akan ikut berubah.

### 3.4 Mencegah Race Condition dengan `FOR UPDATE`
Sistem mengecek stok menggunakan `SELECT ... FOR UPDATE`.
Fungsinya adalah **mengunci baris buku selama transaction berlangsung**. Dengan demikian, transaksi lain tidak dapat mengubah atau menggunakan data stok yang sama sebelum transaction selesai.

Hal ini mencegah **race condition**, misalnya ketika hanya tersedia 1 buku tetapi dua transaksi mencoba meminjam buku tersebut secara bersamaan.
Setelah stok diperiksa:
* Jika stok masih tersedia, proses dilanjutkan.
* Jika stok habis atau buku tidak ditemukan, sistem menghasilkan error dan transaction dibatalkan.

### 3.5 Mencatat Data Peminjaman
Setelah stok dipastikan tersedia, sistem menambahkan data ke tabel `peminjaman`.
Data yang dicatat meliputi:
* `buku_id`: ID buku yang dipinjam.
* `anggota_id`: ID anggota yang meminjam.
* `tanggal_pinjam`: tanggal saat peminjaman dilakukan.
* `status`: status awal peminjaman, yaitu `dipinjam`.

**Fungsinya:** menyimpan informasi transaksi sehingga peminjaman dapat dilacak dan nantinya dapat digunakan untuk proses pengembalian maupun riwayat peminjaman.

### 3.6 Mengurangi Stok Buku
Setelah data peminjaman berhasil dicatat, sistem mengurangi stok buku sebanyak satu.
**Fungsinya:** memperbarui jumlah buku yang tersedia sehingga stok pada database sesuai dengan kondisi setelah peminjaman.
Pengurangan stok dilakukan dalam transaction yang sama dengan pencatatan peminjaman. Dengan demikian, kedua perubahan tersebut akan berhasil atau dibatalkan secara bersamaan.

### 3.7 Penanganan Error
Jika terjadi kesalahan selama proses database, sistem menggunakan `catch` untuk menangkap error.
* Menjalankan `rollBack()` untuk membatalkan perubahan
* Menyimpan pesan error ke dalam flash message.
* Mengembalikan pengguna ke halaman `tambah.php`.

Dengan implementasi ini, fitur peminjaman memiliki fungsi untuk **mencatat transaksi, memperbarui stok, menjaga konsistensi database, melindungi form dengan CSRF, serta mencegah race condition pada stok buku**.
