# Laporan Praktikum Jobsheet 09: CRUD Penuh

# 1. Konsep Dasar CRUD

## 1.1 Pengertian CRUD
CRUD merupakan singkatan dari **Create, Read, Update, dan Delete**, yaitu empat operasi dasar yang digunakan dalam pengelolaan data pada aplikasi.

| Operasi    | Pengertian                    | Perintah SQL | Implementasi |
| ---------- | ----------------------------- | ------------ | ------------ |
| **Create** | Menambahkan data baru         | `INSERT`     | Jobsheet 8   |
| **Read**   | Membaca atau menampilkan data | `SELECT`     | Jobsheet 8   |
| **Update** | Mengubah data yang sudah ada  | `UPDATE`     | Jobsheet 9   |
| **Delete** | Menghapus data                | `DELETE`     | Jobsheet 9   |

Keempat operasi tersebut menjadi dasar dalam pengembangan aplikasi yang menggunakan database. Pada aplikasi **SIMPUS-Mini**, CRUD digunakan untuk mengelola data buku dan anggota.

## 1.2 Perbedaan Create dan Update
Operasi **Create** dan **Update** memiliki alur form yang hampir sama, tetapi terdapat perbedaan pada kondisi awal dan query yang digunakan.

| Aspek          | Create (`tambah.php`) | Update (`edit.php`)          |
| -------------- | --------------------- | ---------------------------- |
| Kondisi form   | Kosong                | Terisi data sebelumnya       |
| Perintah SQL   | `INSERT`              | `UPDATE`                     |
| Identitas data | Tidak diperlukan      | Diperlukan, menggunakan `id` |

Pada operasi **Update**, aplikasi harus mengetahui data mana yang akan diubah. Oleh karena itu, kolom `id` yang sebelumnya digunakan sebagai **primary key** menjadi penting untuk menentukan baris data yang akan diperbarui.

## 1.3 Pengamanan pada Operasi Delete
Operasi **Delete** memerlukan perhatian lebih karena data yang telah dihapus tidak dapat dikembalikan secara langsung melalui aplikasi.

Untuk mengurangi risiko penghapusan data secara tidak sengaja, operasi Delete pada jobsheet ini menggunakan **method `POST`**. Dengan demikian, penghapusan data tidak dilakukan melalui URL atau request `GET` seperti tautan biasa.

## 1.4 Struktur Fitur CRUD
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

