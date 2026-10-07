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
