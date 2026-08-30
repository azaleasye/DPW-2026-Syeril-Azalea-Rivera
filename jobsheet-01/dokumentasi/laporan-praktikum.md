# Laporan Praktikum Jobsheet 1

| Informasi      | Detail                     |
| -------------- | -------------------------- |
| Nama           | Syeril Azalea Rivera       |
| Kelas          | TI 2F - 28                 |
| Program Studi  | D-IV - Teknik Informatika  |
| Mata Kuliah    | Desain dan Pemrograman Web |


## Index.html
Merupakan file index yg terletak pada root, berfungsi sebagai home dengan 
navigasi menu antar page dan ringkasan mengenai jumlah buku, anggota, dan daftar pinjam. 
Index terdiri dari header, main, dan footer yang semuanya dibungkus oleh tag body.


## Buku
1. list.html
Page ini berfungsi untuk menampilkan daftar buku pada perpustakaan dengan struktur **table**

```html
<table>
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
```
2. tambah.html
Page ini berfungsi sebagai **form** (formulir isian) untuk input/menambah buku
baru beserta datanya. 

```html
<p>
    <label for="judul">Judul</label><br>
    <input type="text" id="judul" name="judul" required>
</p>
```

## Anggota
1. list.html
Page ini berfungsi untuk menampilkan daftar anggota pada perpustakaan dengan struktur **table**

```html
<table>
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
```

2. tambah.html
Page ini berfungsi sebagai **form** (formulir isian) untuk input/menambah anggota
baru beserta datanya. 

```html
<p>
    <label for="nama">Nama</label><br>
    <input type="text" id="nama" name="nama" required>
</p>
```

## Latihan Reflektif
1. Kenapa field "Alamat" dan "No. HP" tidak diberi `required`, sedangkan
   "Nama" dan "No. Anggota" diberi?
   
   Penggunaan atribut `required` berfungsi agar field tersebut selalu diisi (wajib diisi). 
   Karena itu Nama dan Anggota diberi atribut tsb karena datanya wajib diisi, sedangkan 
   Alamat dan No. HP bersifat opsional, jadi tidak wajib diisi.

2. Apa yang akan terjadi (di browser) kalau kamu klik tombol "Simpan"
   tanpa mengisi field "Nama"? Coba buka filenya di browser dan praktikkan.

   Jika tidak di isi maka akan menampilkan validasi "please fill out this field"
   saat disubmit, karena field tersebut wajib diisi.


3. Form ini juga **belum punya `action`** pada tag `<form>`-nya — apa
   dampaknya saat tombol "Simpan" ditekan?

   Data yang sudah diinput tidak akan tersimpan dan hilang saat refresh browser, karena button tidak 
   memiliki action yang bisa memproses dan menyimpan datanya.
