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

## 4. Pengembalian Buku
Modul pengembalian buku merupakan kebalikan dari proses peminjaman. Jika peminjaman mengurangi stok buku, maka pengembalian akan menambah kembali stok buku dan mengubah status transaksi menjadi sudah dikembalikan.

### 4.1 `peminjaman/kembali.php`: Daftar Transaksi Aktif
Halaman `kembali.php` digunakan untuk menampilkan daftar buku yang sedang dipinjam dan belum dikembalikan.

Tambahan dan fungsinya:
* **`JOIN`**: menggabungkan tabel `peminjaman`, `buku`, dan `anggota` sehingga halaman dapat menampilkan judul buku dan nama anggota.
* **`WHERE p.status = 'dipinjam'`**: memastikan hanya transaksi yang masih aktif yang ditampilkan.
* **Pencarian dengan `GET` dan `ILIKE`**: memungkinkan pengguna mencari transaksi berdasarkan data yang tersedia.
* **Form pengembalian dengan `POST`**: memastikan proses pengembalian tidak dilakukan melalui URL atau `GET`.
* **CSRF token**: melindungi form pengembalian dari serangan CSRF.
* **Hidden input `id`**: mengirim ID transaksi yang akan dikembalikan ke `proses_kembali.php`.
* **Tombol "Kembalikan"**: digunakan untuk menjalankan proses pengembalian pada transaksi yang dipilih.

Dengan demikian, halaman ini berfungsi sebagai daftar transaksi aktif sekaligus menyediakan akses untuk memproses pengembalian.

### 4.2 `proses_kembali.php`: Memproses Pengembalian
File `proses_kembali.php` digunkan untuk memproses transaksi pengembalian dan mengembalikan stok buku.

Proses yang ditambahkan meliputi:
* Memastikan pengguna sudah login melalui `auth.php`.
* Memastikan request menggunakan method `POST`.
* Memverifikasi CSRF token.
* Mengambil ID transaksi yang akan dikembalikan.
* Memulai database transaction.
* Mengunci baris transaksi menggunakan `FOR UPDATE`.
* Memastikan transaksi masih memiliki status `dipinjam`.
* Mengubah status transaksi menjadi `dikembalikan`.
* Mengisi `tanggal_kembali` dengan tanggal saat pengembalian dilakukan.
* Menambah stok buku sebanyak 1.
* Menyimpan seluruh perubahan menggunakan `commit()`.
* Membatalkan seluruh perubahan dengan `rollBack()` jika terjadi error.

### 4.3 Transaction pada Pengembalian
Database transaction digunakan karena pengembalian melakukan dua perubahan yang harus berhasil secara bersamaan:
1. Mengubah status transaksi menjadi `dikembalikan` dan mengisi tanggal kembali.
2. Menambah stok buku sebanyak 1.

**Fungsinya:** menjaga agar data transaksi dan stok buku tetap konsisten.
Jika perubahan transaksi berhasil tetapi penambahan stok gagal, transaction akan di-`rollback` sehingga perubahan sebelumnya juga dibatalkan.

### 4.4 `FOR UPDATE` untuk Mencegah Pengembalian Ganda
Pada proses pengembalian, `FOR UPDATE` digunakan untuk mengunci baris transaksi peminjaman, bukan baris buku.
**Fungsinya:** mencegah dua proses pengembalian terhadap transaksi yang sama berjalan secara bersamaan.
Contohnya, jika tombol "Kembalikan" ditekan dua kali dengan cepat, kedua request tidak boleh sama-sama menganggap transaksi masih berstatus `dipinjam`. Penguncian memastikan proses kedua menunggu hingga transaction pertama selesai.

### 4.5 Pemeriksaan Status Transaksi
Sistem memeriksa status transaksi sebelum melakukan pengembalian.
Jika transaksi tidak ditemukan atau statusnya sudah bukan `dipinjam`, proses akan dihentikan.
**Fungsinya:** mencegah satu transaksi dikembalikan lebih dari satu kali. Hal ini penting agar stok buku tidak bertambah dua kali untuk satu pengembalian.

### 4.6 Mengubah Status dan Tanggal Pengembalian
Ketika pengembalian berhasil, status transaksi diubah menjadi `dikembalikan` dan `tanggal_kembali` diisi dengan tanggal saat proses dilakukan.
* `status` menunjukkan bahwa buku sudah dikembalikan.
* `tanggal_kembali` mencatat kapan buku dikembalikan.

Kedua data tersebut diperbarui dalam satu perintah sehingga informasi transaksi tetap konsisten.

### 4.7 Menambah Kembali Stok Buku
Setelah status peminjaman diperbarui, stok buku ditambah sebanyak 1.
**Fungsinya:** mengembalikan jumlah buku yang tersedia setelah buku dikembalikan oleh anggota.
Perubahan stok dilakukan dalam transaction yang sama dengan perubahan status peminjaman sehingga kedua proses berhasil atau dibatalkan secara bersamaan.

### 4.8 Guard Method dan CSRF
Proses pengembalian menggunakan dua lapisan keamanan, yaitu pemeriksaan HTTP method dan CSRF token.
* **Pemeriksaan `POST`**: memastikan proses pengembalian tidak dapat dijalankan melalui request `GET`.
* **CSRF token**: memastikan request berasal dari form aplikasi yang valid.
* **Keduanya digunakan bersama**: masing-masing memberikan perlindungan yang berbeda.

Pendekatan menggunakan beberapa lapisan keamanan ini disebut **defense in depth**, yaitu tidak bergantung pada satu mekanisme keamanan saja.

Dengan implementasi ini, fitur pengembalian berfungsi untuk **mencatat pengembalian, memperbarui status transaksi, mengisi tanggal pengembalian, mengembalikan stok buku, serta mencegah pengembalian ganda melalui transaction dan `FOR UPDATE`**.


## 4. Pengembalian Buku
Modul pengembalian buku merupakan kebalikan dari proses peminjaman. Jika peminjaman mengurangi stok buku, maka pengembalian akan menambah kembali stok buku dan mengubah status transaksi menjadi sudah dikembalikan.

### 4.1 `peminjaman/kembali.php`: Daftar Transaksi Aktif
Halaman `kembali.php` digunakan untuk menampilkan daftar buku yang sedang dipinjam dan belum dikembalikan.

Tambahan dan fungsinya:
* **`JOIN`**: menggabungkan tabel `peminjaman`, `buku`, dan `anggota` sehingga halaman dapat menampilkan judul buku dan nama anggota.
* **`WHERE p.status = 'dipinjam'`**: memastikan hanya transaksi yang masih aktif yang ditampilkan.
* **Pencarian dengan `GET` dan `ILIKE`**: memungkinkan pengguna mencari transaksi berdasarkan data yang tersedia.
* **Form pengembalian dengan `POST`**: memastikan proses pengembalian tidak dilakukan melalui URL atau `GET`.
* **CSRF token**: melindungi form pengembalian dari serangan CSRF.
* **Hidden input `id`**: mengirim ID transaksi yang akan dikembalikan ke `proses_kembali.php`.
* **Tombol "Kembalikan"**: digunakan untuk menjalankan proses pengembalian pada transaksi yang dipilih.

Dengan demikian, halaman ini berfungsi sebagai daftar transaksi aktif sekaligus menyediakan akses untuk memproses pengembalian.

### 4.2 `proses_kembali.php`: Memproses Pengembalian
File `proses_kembali.php` digunakan untuk memproses transaksi pengembalian dan mengembalikan stok buku.
Proses yang ditambahkan meliputi:
* Memastikan pengguna sudah login melalui `auth.php`.
* Memastikan request menggunakan method `POST`.
* Memverifikasi CSRF token.
* Mengambil ID transaksi yang akan dikembalikan.
* Memulai database transaction.
* Mengunci baris transaksi menggunakan `FOR UPDATE`.
* Memastikan transaksi masih memiliki status `dipinjam`.
* Mengubah status transaksi menjadi `dikembalikan`.
* Mengisi `tanggal_kembali` dengan tanggal saat pengembalian dilakukan.
* Menambah stok buku sebanyak 1.
* Menyimpan seluruh perubahan menggunakan `commit()`.
* Membatalkan seluruh perubahan dengan `rollBack()` jika terjadi error.

### 4.3 Transaction pada Pengembalian
Database transaction digunakan karena pengembalian melakukan dua perubahan yang harus berhasil secara bersamaan:
1. Mengubah status transaksi menjadi `dikembalikan` dan mengisi tanggal kembali.
2. Menambah stok buku sebanyak 1.

**Fungsinya:** menjaga agar data transaksi dan stok buku tetap konsisten.
Jika perubahan transaksi berhasil tetapi penambahan stok gagal, transaction akan di-`rollback` sehingga perubahan sebelumnya juga dibatalkan.

### 4.4 `FOR UPDATE` untuk Mencegah Pengembalian Ganda
Pada proses penembalian, `FOR UPDATE` digunakan untuk mengunci baris transaksi peminjaman, bukan baris buku.
**Fungsinya:** mencegah dua proses pengembalian terhadap transaksi yang sama berjalan secara bersamaan.
Contohnya, jika tombol "Kembalikan" ditekan dua kali dengan cepat, kedua request tidak boleh sama-sama menganggap transaksi masih berstatus `dipinjam`. Penguncian memastikan proses kedua menunggu hingga transaction pertama selesai.

### 4.5 Pemeriksaan Status Transaksi
Sistem memeriksa status transaksi sebelum melakukan pengembalian.
Jika transaksi tidak ditemukan atau statusnya sudah bukan `dipinjam`, proses akan dihentikan.
**Fungsinya:** mencegah satu transaksi dikembalikan lebih dari satu kali. Hal ini penting agar stok buku tidak bertambah dua kali untuk satu pengembalian.

### 4.6 Mengubah Status dan Tanggal Pengembalian
Ketika pengembalian berhasil, status transaksi diubah menjadi `dikembalikan` dan `tanggal_kembali` diisi dengan tanggal saat proses dilakukan.
**Fungsinya:**
* `status` menunjukkan bahwa buku sudah dikembalikan.
* `tanggal_kembali` mencatat kapan buku dikembalikan.

Kedua data tersebut diperbarui dalam satu perintah sehingga informasi transaksi tetap konsisten.

### 4.7 Menambah Kembali Stok Buku
Setelah status peminjaman diperbarui, stok buku ditambah sebanyak 1.
**Fungsinya:** mengembalikan jumlah buku yang tersedia setelah buku dikembalikan oleh anggota.
Perubahan stok dilakukan dalam transaction yang sama dengan perubahan status peminjaman sehingga kedua proses berhasil atau dibatalkan secara bersamaan.

### 4.8 Guard Method dan CSRF
Proses pengembalian menggunakan dua lapisan keamanan, yaitu pemeriksaan HTTP method dan CSRF token.
* **Pemeriksaan `POST`**: memastikan proses pengembalian tidak dapat dijalankan melalui request `GET`.
* **CSRF token**: memastikan request berasal dari form aplikasi yang valid.
* **Keduanya digunakan bersama**: masing-masing memberikan perlindungan yang berbeda.

Pendekatan menggunakan beberapa lapisan keamanan ini disebut **defense in depth**, yaitu tidak bergantung pada satu mekanisme keamanan saja.

## Ringkasan Perubahan 
- Tambah `sql/03_peminjaman.sql` — tabel `peminjaman` (relasi ke `buku` dan `anggota`), melengkapi ERD yang sudah dirancang di Jobsheet 8.
- Tambah modul **Peminjaman** (menghubungkan seluruh entitas yang sudah dibangun sejak Jobsheet 8-10 sekaligus):
  - `peminjaman/tambah.php` + `proses_tambah.php`: pilih anggota + buku (dropdown hanya `stok > 0`), simpan transaksi **dan** kurangi stok buku dalam satu **transaction** (`beginTransaction`/`commit`/`rollBack`) dengan `SELECT ... FOR UPDATE` untuk mencegah race condition stok.
  - `peminjaman/kembali.php` + `proses_kembali.php`: daftar transaksi aktif (`status = 'dipinjam'`), tombol Kembalikan menambah kembali stok buku dalam transaction serupa.
  - `peminjaman/riwayat.php`: histori peminjaman per anggota (JOIN `peminjaman` + `buku`).
- `includes/header.php`: navbar menambahkan menu Peminjaman Baru, Pengembalian, Riwayat (hanya saat login).
- `index.php`: kartu "Sedang Dipinjam" kini `COUNT(*) FROM peminjaman WHERE status = 'dipinjam'` (sebelumnya statis `0`).