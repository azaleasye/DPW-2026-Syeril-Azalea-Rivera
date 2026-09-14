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
