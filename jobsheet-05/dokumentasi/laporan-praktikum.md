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

## 6. JS: Filter Tabel Real-Time
Fungsi ini menghubungkan kolom pencarian baru dengan tabel di bawahnya.

1. Mengambil Kotak Input dan Tabelnya
- `input` 
   kotak pencarian, dicari lewat `id="search-input"`
- `table` 
   dicari dengan selector `".table-responsive table"`,
   descendant selector yang mengambil elemen `<table>` di dalam `<div
   class="table-responsive">` 
- Guard clause `if (!input || !table) return;` 
   memastikan fungsi ini aman dipanggil di halaman mana pun (termasuk
   Beranda yang tidak punya kolom pencarian maupun tabel sama sekali).

2. Event `keyup`: Bereaksi Setiap Ketikan
event `keyup` terjadi setiap kali sebuah tombol keyboard **dilepas** (setelah ditekan) saat fokus berada di elemen `input`.

3. Mengambil Kata Kunci Pencarian
- **`input.value`**  
   nilai/teks yang **sedang** diketik di dalam kotak input saat ini (berbeda dengan `placeholder` yang hanya teks contoh, bukan nilai sungguhan).
- **`.toLowerCase()`**  
   mengubah semua huruf jadi huruf kecil. Ini penting supaya pencarian **tidak peka huruf besar/kecil** (*case-insensitive*) — mengetik "laskar" tetap menemukan "Laskar Pelangi" meskipun huruf "L"-nya besar di data aslinya.

5. Mengulang Setiap Baris Tabel
- `table.querySelectorAll("tbody tr")` — 
   mengambil **semua** baris data di dalam `<tbody>`, terpisah dari baris judul kolom di `<thead>` — sehingga baris judul **tidak ikut** disaring/disembunyikan.
- `row.textContent` 
   mengambil **seluruh teks** di dalam baris itu (gabungan semua sel `<td>`-nya jadi satu string panjang, termasuk teks tombol "Edit"/"Hapus" di dalamnya), lalu diubah ke huruf kecil juga (`.toLowerCase()`) supaya konsisten dengan `keyword`.
- **`teks.includes(keyword)`** 
   mengembalikan `true` kalau `teks`**mengandung** `keyword` di bagian mana pun.
- **`row.style.display = ... ? "" : "none";"`** 
   mengatur properti CSS

6. Kenapa Baris Disembunyikan, Bukan Dihapus?
Karena fungsi ini memakai `row.style.display = "none"`, bukan `row.remove()` seperti pada tombol Hapus 
- `.remove()`  
   menghapus permanen elemen dari DOM (perlu dibuat ulang untuk memunculkannya lagi), sedangkan
- `style.display = "none"` 
   hanya **menyembunyikan sementara** elemennya tetap ada di DOM, hanya tidak terlihat. 

## 7. JS: Validasi Form

Validasi form menggunakan tiga fungsi utama:
* `tampilkanError()` → menampilkan pesan error pada field.
* `hapusError()` → menghapus pesan error yang sudah ada.
* `initValidasiForm()` → menjalankan validasi saat form di-submit.

### 7.1 Fungsi `tampilkanError()`
Fungsi ini digunakan untuk menampilkan pesan error pada input.
```js
function tampilkanError(input, pesan) {
    hapusError(input);

    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;

    input.insertAdjacentElement("afterend", span);
}
```

* `createElement("span")` membuat elemen baru.
* `className = "error"` memberikan class CSS.
* `textContent` mengisi pesan error.
* `insertAdjacentElement("afterend", ...)` menempatkan pesan setelah input.

### 7.2 Fungsi `hapusError()`
Digunakan untuk menghapus pesan error pada input.
```js
function hapusError(input) {
    const next = input.nextElementSibling;

    if (next && next.classList.contains("error")) {
        next.remove();
    }
}
```

* `nextElementSibling` mengambil elemen setelah input.
* `classList.contains("error")` memastikan elemen tersebut adalah pesan error.
* `remove()` menghapus pesan error.

### 7.3 Fungsi `initValidasiForm()`
Fungsi utama yang menjalankan validasi ketika form di-submit.
```js
function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        // pengecekan setiap field

        if (!valid) {
            e.preventDefault();
        }
    });
}
```

* `getElementById()` mencari form dengan `id="form-tambah"`.
* `addEventListener("submit", ...)` menjalankan validasi saat form dikirim.
* `valid` digunakan untuk menentukan apakah form valid.
* `e.preventDefault()` mencegah form dikirim jika terdapat kesalahan.

### 7.4 Validasi Setiap Field
```js
const judul = form.querySelector("[name='judul'], [name='nama']");

if (judul && judul.value.trim() === "") {
    tampilkanError(judul, "Field ini wajib diisi.");
    valid = false;
} else if (judul) {
    hapusError(judul);
}
```

* `querySelector()` mencari field berdasarkan atribut `name`.
* `trim()` menghapus spasi di awal dan akhir.
* Jika kosong, error ditampilkan dan `valid` menjadi `false`.
* Jika sudah benar, error dihapus.

Untuk tahun:
```js
const tahun = form.querySelector("[name='tahun']");

if (tahun) {
    const nilai = parseInt(tahun.value, 10);

    if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
        tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
        valid = false;
    } else {
        hapusError(tahun);
    }
}
```

* `parseInt()` mengubah input menjadi angka.
* `isNaN()` mengecek apakah hasilnya bukan angka.
* Tahun harus berada dalam rentang `1900–2026`.
* Field `stok` menggunakan pola yang sama, tetapi tidak boleh bernilai negatif.

### 7.5 Mencegah Submit
```js
if (!valid) {
    e.preventDefault();
}
```

Jika ada field yang tidak valid, `preventDefault()` mencegah form dikirim atau halaman di-reload.
Jika semua field valid, form tetap menjalankan perilaku bawaan.

## 7.6 Hubungan dengan Form Jobsheet-01
Form pada jobsheet sebelumnya belum memiliki `action` dan `method`, sehingga data belum benar-benar dikirim ke server.
Validasi JavaScript hanya berfungsi sebagai **pemeriksaan sebelum submit**. Jadi, validasi ini belum membuat data tersimpan ke database atau server.
