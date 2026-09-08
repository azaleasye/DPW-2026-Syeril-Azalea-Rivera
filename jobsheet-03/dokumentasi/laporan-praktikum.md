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

## Dokumentasi
### 02. Perubahan File
1. Menambahkan meta viewport pada total 5 page html

```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```
Agar tampilan sesuai dan akurat dengan ukuran layar device yang sebenarnya.

2. Menambahkan Pasangan Checkbox dan Hamburger Menu

```html
    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <label for="nav-toggle" class="nav-toggle-label">&#9776;</label>
```
3. Menambahkan pembungkus div table responsive

```html
   <div class="table-responsive">
```

### 03. CSS Hamburger & CheckBox
1. Sembunyikan Checkbox

```css
.nav-toggle {
    display: none;
}
```

Checkbox asli disembunyikan karena tidak perlu terlihat oleh pengguna.
Checkbox hanya digunakan untuk menyimpan status:
* **Dicentang** → menu terbuka.
* **Tidak dicentang** → menu tertutup.

2. Label Menjadi Tombol Hamburger

```css
.nav-toggle-label {
    display: none;
    font-size: 1.6rem;
    color: #fff;
    cursor: pointer;
}
```

Karena checkbox disembunyikan, `<label>` digunakan sebagai tombol penggantinya. Label berisi ikon hamburger **☰**, Karena label terhubung dengan checkbox menggunakan:

```html
<label for="nav-toggle">☰</label>
<input type="checkbox" id="nav-toggle">
```

Maka ketika pengguna mengklik **☰**, checkbox akan otomatis dicentang atau tidak dicentang. Pada layar besar, hamburger disembunyikan dengan display none sebelumnya


2. Sembunyikan Menu di HP

```css
header nav {
    display: none;
    width: 100%;
    order: 3;
    margin-top: 1rem;
}
```

Aturan ini digunakan pada layar kecil, misalnya HP.

```css
display: none;
```

Artinya menu disembunyikan terlebih dahulu.

```css
width: 100%;
```

Artinya menu menggunakan lebar penuh.

```css
order: 3;
```

Artinya posisi menu berada setelah elemen lain di dalam Flexbox.


3. Menghubungkan Status ke nav

```css
.nav-toggle:checked ~ nav {
    display: block;
}
```

Ini adalah bagian utama dari **checkbox hack**. Jika checkbox `.nav-toggle` dicentang, tampilkan `<nav>`.

* `.nav-toggle` → checkbox.
* `:checked` → checkbox sedang dicentang.
* `~` → memilih elemen saudara setelah checkbox.
* `nav` → menu yang akan ditampilkan.

Alur lengkap: 
1. Pengguna klik ikon **☰**.
2. Checkbox menjadi dicentang.
3. CSS `:checked` aktif.
4. `<nav>` berubah menjadi display block lalu menu muncul
5. Menu muncul.


5. Mengapa Aturan Ditulis Lagi di Dalam Media Query?

Karena CSS menggunakan aturan yang berbeda untuk ukuran layar yang berbeda. Artinya, di layar besar, tombol hamburger disembunyikan. Tetapi di dalam media query, Jika layar berukuran maksimal 480px, tombol hamburger ditampilkan.

Karena ketika ukuran layar memenuhi:

```css
max-width: 480px
```

aturan di dalam `@media` akan aktif dan menggantikan aturan sebelumnya/defaultnya.


6. Menu Menjadi Vertikal 

```css
header nav ul {
    flex-direction: column;
    gap: 0.75rem;
}
```

### 04. CSS Table Responsive
1. Menambahkan CSS Table Responsive
Menambahkan satu properti untuk menampilkan table sesuai lebar aslinya di layar hp, namun dengan pendekatan scroll ke samping agar pengguna tinggal geser (swipe di HP, atau scroll horizontal di trackpad/mouse) untuk melihat kolom yang belum terlihat.

```css
.table-responsive {
    overflow-x: auto;
}
```

overflow-x mengatur perilaku konten yang melebihi lebar elemen, khusus arah horizontal (ada juga overflow-y untuk arah vertikal, dan overflow untuk keduanya sekaligus).

Nilai auto berarti: browser hanya menampilkan scrollbar kalau memang dibutuhkan (isi di dalamnya melebihi lebar kotak). Kalau tabelnya cukup sempit untuk muat (misalnya di layar desktop lebar), tidak ada scrollbar yang muncul sama sekali — perilaku ini lebih ramah dibanding nilai scroll yang akan selalu menampilkan scrollbar meskipun tidak diperlukan.

