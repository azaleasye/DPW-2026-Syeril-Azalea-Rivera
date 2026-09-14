# Laporan Praktikum Jobsheet 5

| Informasi      | Detail                     |
| -------------- | -------------------------- |
| Nama           | Syeril Azalea Rivera       |
| Kelas          | TI 2F - 28                 |
| Program Studi  | D-IV - Teknik Informatika  |
| Mata Kuliah    | Desain dan Pemrograman Web |

## 1. Konsep Dasar Javascript & DOM
Konfigurasi app.js ke dalam file html.
Langkah yang dilakukan : 
1. Add new folder js di dalam folder assets
2. Add new file app.js di dalam folder js
3. Menghubungkan js ke bagian akhir tiap file html, sebelum </body>
   ```html
   <script src="assets/js/app.js"></script>
   ```
### 1.1 Perbedaan HTML, CSS, dan JavaScript
Tiga bagian utama website:

- HTML → mengatur struktur dan isi website.
- CSS → mengatur tampilan website.
- JavaScript → mengatur perilaku dan interaksi website.

### 1.2 Menghubungkan JavaScript ke HTML
   ```html
   <script src="assets/js/app.js"></script>
   ```
- <script> → untuk menghubungkan JavaScript.
- src → menunjukkan lokasi file JavaScript.
- app.js → file JavaScript yang digunakan.   

### 1.3 Kenapa Script di Akhir <body>?
Karena browser membaca elemen html dari atas ke bawah 


## 2. Perubahan File HTML
Ada beberapa perubahan HTML agar JavaScript bisa menemukan dan mengatur elemen HTML.

### 2.1 Hamburger 
Sebelumnya menggunakan
   ```html
   <input type="checkbox">
   <label>☰</label>
   ```
Lalu diganti dengan 
   ```html
   <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">
    &#9776;
   </button>
   ```
Perubahannya : 
- Checkbox dihapus.
- <label> diganti menjadi <button>.
- id="nav-toggle-btn" digunakan agar JavaScript bisa menemukan tombol.
- class="nav-toggle-label" tetap digunakan agar CSS lama masih bisa dipakai.
- aria-label="Menu" membantu screen reader mengetahui bahwa tombol tersebut adalah menu.

### 2.2 Searching
```html
<div class="search-box">
    <label for="search-input">Cari Judul Buku</label>
    <input type="text" id="search-input" placeholder="Ketik judul buku...">
</div>
```
- Menambahkan search box, dengan pola yang sama seperti form, namun value nya tidak disimpan.
- Value dari searching hanya akan dibaca oleh JS setiap kali diketik pada bagian placeholder.
- id="search-input" adalah "kait" yang dicari document.getElementById("search-input")

### 2.3 Button Delete
Menambahkan class="button-hapus" agar document.querySelectorAll(".btn-hapus") di app.js bisa menemukan semua tombol Hapus sekaligus di satu halaman.
```html
<button type="button" class="btn-hapus">Hapus</button>
```

### 2.4 Form Tambah 
```html
<form id="form-tambah">
```
- Menambahkan id form tambah di (`buku/tambah.html` dan `anggota/tambah.html`)
- id="form-tambah" di tag <form>-nya — sebelumnya (dokumentasi jobsheet-01) tag <form> tidak punya atribut apa pun.
-  id ini adalah "kait" yang dicari document.getElementById("form-tambah") supaya JavaScript bisa memasang event listener submit untuk validasi.


## 3. CSS Pendukung Fitur JavaScript
### 3.1 Hamburger: dari `.nav-toggle` ke Tombol Asli
1. Menghapus .nav-toggle di CSS Style
   ```css
   .nav-toggle {
      display: none;
   }
   ```
2. Menambah background dan border di CSS .nav-toggle-label
Karena sekarang elemennya adalah`<button>`. Tombol HTML secara default punya latar abu-abu dan
bingkai 3D bawaan browser. Tanpa dua baris ini, tombol hamburger akan terlihat seperti kotak abu-abu biasa.
   ```css
   .nav-toggle-label {
      display: none;
      font-size: 1.6rem;
      color: #fff;
      background: none;
      border: none;
      cursor: pointer;
   }
   ```
3. Ubah .nav
Sekarang selector-nya jauh lebih sederhana: **`header nav.nav-open`** —
elemen `<nav>` di dalam `<header>` yang **punya class `nav-open`**.
Tidak ada lagi pseudo-class atau sibling combinator sama sekali, karena
status "menu terbuka" sekarang murni ditentukan oleh **ada atau
tidaknya** class `nav-open` — dan yang menambah/menghapus class
   ```css
   header nav.nav-open {
      display: block;
   }
   ```
   
### 3.2 Gaya Baru: Pesan Error Validasi
1. Menambahkan css pesan error
- `display: block;` 
   memastikan pesan error tampil di **baris baru** sendiri, di bawah kotak input, bukan menempel sejajar di sampingnya
- `color: #d9534f;` 
   warna merah, sama persis dengan warna tombol Hapus yg menandakan "sesuatu yang perlu perhatian/tindakan" di seluruh aplikasi.
- `font-size: 0.85rem;` dan `margin-top: 0.25rem;` 
   teks sedikit lebih kecil dari input di atasnya, dengan jarak tipis supaya terlihat jelas
   sebagai keterangan tambahan, bukan menyatu dengan input.

### 3.3 Gaya Baru: Kolom Pencarian
- `.search-box { margin-bottom: 1rem; }` 
   memberi jarak antara kotak pencarian dan tabel di bawahnya.
- `.search-box input` 
   menata kotak input pencarian mirip gaya input form, karena
   kolom pencarian memang tidak perlu selebar field form biasa.