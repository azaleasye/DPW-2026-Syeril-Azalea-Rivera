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

## 4. JS: Menu Hamburger
Fungsi pertama di `app.js`, dan yang paling sederhana — cocok jadi titik
awal belajar membaca kode JavaScript.

```js
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}
```

1. Mengambil Dua Elemen yang Dibutuhkan
- `const` 
   untuk mendeklarasikan sebuah **variabel**
   (tempat menyimpan nilai) yang nilainya **tidak akan diganti** lagi
   setelah didefinisikan.
- `toggleBtn` 
   diisi dengan tombol hamburger `id="nav-toggle-btn"`
- `nav` 
   diisi dengan elemen `<nav>` yang berada di dalam `<header>` dan memakai selector CSS `"header nav"`

2. Penjaga Keamanan (Guard Clause)
- Tanda seru `!` 
   di depan sebuah nilai berarti **kebalikan/negasi**
   `!toggleBtn` bernilai benar (*true*) kalau `toggleBtn` adalah `null`
   (tidak ditemukan elemennya).
- `||` berarti **atau** 
   kondisi ini benar kalau **salah satu saja** dari `toggleBtn` atau `nav` tidak ditemukan.
- Kalau kondisi ini benar, `return;` 
   menghentikan fungsi sebelum baris berikutnya (`toggleBtn.addEventListener(...)`) sempat dijalankan dan
   mencegah error "Cannot read properties of null" yang akan muncul jika mencoba memanggil `.addEventListener` pada nilai `null`.

3. Memasang Event Listener
- `toggleBtn.addEventListener("click", ...)` 
   "setiap kali tombol ini diklik, jalankan fungsi berikut."
- **`nav.classList`** 
   adalah objek yang mewakili **daftar semua class** yang dimiliki elemen `nav` saat ini (mirip atribut `class="..."` di
    HTML, tapi dalam bentuk yang bisa diprogram).
- **`.toggle("nav-open")`** 
   adalah method yang **membalik status** satu class tertentu:
   - Kalau elemen `nav` **belum** punya class `nav-open` → class itu
      **ditambahkan**.
   - Kalau elemen `nav` **sudah** punya class `nav-open` → class itu
      **dihapus**.

4. Menghubungkan Kembali ke CSS

```css
header nav.nav-open {
    display: block;
}
```

Alur lengkapnya sekarang:
1. Pengguna mengklik tombol hamburger (`#nav-toggle-btn`).
2. Event listener `click` di [§4.4](#44-memasang-event-listener) terpicu.
3. `nav.classList.toggle("nav-open")` menambahkan class `nav-open` ke
   elemen `<nav>`.
4. CSS `header nav.nav-open { display: block; }` otomatis berlaku
   karena elemen `<nav>` sekarang cocok dengan selector itu → menu
   **muncul**.
5. Klik tombol sekali lagi → `classList.toggle()` **menghapus** class
   `nav-open` → elemen `<nav>` tidak lagi cocok dengan selector CSS
   tadi → menu kembali ke `display: none` dari gaya dasarnya → menu
   **tersembunyi lagi**.


## 5. JS: Konfirmasi Hapus
1. Memasang Event Listener ke Banyak Tombol 
- `querySelectorAll(".btn-hapus")`
   mengambil **semua** tombol Hapus di satu halaman. 
- Karena hasilnya berupa **kumpulan** elemen (bukan satu elemen seperti
   `getElementById`), perlu **`.forEach(...)`** untuk mengulang satu per satu, dan memasang `addEventListener` **terpisah** ke tiap tombol setiap tombol Hapus jadi punya "pendengar klik"-nya sendiri-sendiri.

2. Mencari Baris Tabel yang Jadi Induk Tombol
**`.closest("tr")`** adalah method yang mencari **ke atas** dari elemen
`btn` (tombol yang diklik) menuju elemen induknya, berhenti begitu
menemukan elemen pertama yang cocok dengan selector `"tr"`.

3. Mengambil Nama/Judul dari Baris Itu
```js
const nama = row ? row.querySelector("td")?.textContent : "data ini";
```

- **`kondisi ? nilaiJikaBenar : nilaiJikaSalah`** 
   adalah **ternary operator** atau bentuk singkat dari `if/else` yang ditulis dalam satu
   baris sebagai sebuah *nilai* (bukan sebagai blok kode terpisah).
- **`row.querySelector("td")`** 
   untuk mengambil sel `<td>` **pertama** di dalam baris itu.
- **`?.`** (disebut *optional chaining*) 
   biasa untuk mengakses properti, tapi **aman** kalau nilai di depannya `null`/ `undefined`. 
- **`.textContent`** 
   mengambil **teks yang tampil** di dalam elemen itu.

4. Menampilkan Dialog Konfirmasi
**`confirm(pesan)`** adalah fungsi bawaan browser (bukan sesuatu yang perlu diimpor/didefinisikan) yang menampilkan **kotak dialog bawaan browser** berisi pesan, dengan dua tombol: **OK** dan **Cancel**.

5. Menghapus Baris dari Tampilan
- `&&` berarti **dan** 
    kode di dalam `if` hanya dijalankan kalau **kedua** kondisi benar: pengguna menekan OK (`yakin` bernilai`true`) **dan** baris `row` memang ditemukan.
- **`row.remove()`** 
   method DOM yang menghapus elemen itu **dari tampilan halaman** secara langsung — baris tabel itu akan lenyap dari layar seketika, tanpa perlu me-reload halaman.
