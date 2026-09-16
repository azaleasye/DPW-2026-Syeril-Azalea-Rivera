# Laporan Praktikum Jobsheet 6

| Informasi      | Detail                     |
| -------------- | -------------------------- |
| Nama           | Syeril Azalea Rivera       |
| Kelas          | TI 2F - 28                 |
| Program Studi  | D-IV - Teknik Informatika  |
| Mata Kuliah    | Desain dan Pemrograman Web |

## 1. Konsep Dasar: AJAX, JSON, Fetch, async/await
Jobsheet ini melanjutkan jobsheet-05 (JavaScript DOM & Event). Bedanya: data tabel **tidak lagi ditulis manual** di HTML, melainkan diambil dari file JSON memakai JavaScript.

### 1.1 Apa itu AJAX?
**AJAX** (*Asynchronous JavaScript and XML*) adalah teknik mengambil data **tanpa me-reload seluruh halaman**.

- Form biasa (jobsheet-01) untuk submit = halaman dimuat ulang seluruhnya.
- AJAX untuk JavaScript ambil data di **latar belakang**, lalu isi sebagian halaman saja (di jobsheet ini: mengisi `<tbody>` tabel).

### 1.2 Apa itu JSON?
**JSON** (*JavaScript Object Notation*) adalah format teks untuk menyimpan data terstruktur.

### 1.3 Apa itu `fetch()`?
`fetch(url)` adalah fungsi bawaan browser untuk **meminta** data dari sebuah alamat (file lokal atau server).

### 1.4 Apa itu Promise? Kenapa Butuh `await`?
Mengambil data butuh waktu. JavaScript **tidak berhenti total** menunggu (supaya halaman tidak membeku). Sebagai gantinya, `fetch()` mengembalikan **Promise** — “janji” bahwa hasilnya akan tersedia **nanti**.
**`await`** artinya: tunggu sampai Promise selesai, baru lanjut ke baris berikutnya.

### 1.5 Apa itu `async function`?
`await` **hanya boleh** dipakai di dalam fungsi yang ditandai `async`
`async` memberi tahu JavaScript bahwa fungsi ini berjalan **asinkron** — boleh “berhenti sejenak” di `await` tanpa memblokir seluruh halaman.

### 1.6 Menangani Kegagalan: `try` / `catch` / `finally`
```js
try {
    // kode yang mungkin gagal (misalnya fetch gagal)
} catch (err) {
    // dijalankan HANYA kalau ada error di blok try
} finally {
    // selalu dijalankan, entah berhasil atau gagal
}
```

- `try` untuk bungkus kode yang berpotensi gagal.
- `catch` untuk tangkap error supaya program tidak crash; bisa tampilkan pesan ke pengguna.
- `finally` untuk selalu dijalankan (cocok untuk menyembunyikan loading indicator).


## 2. Perubahan File HTML
Perubahan HTML supaya data bisa diisi dinamis oleh JavaScript.

### 2.1 Hapus isi table di dalam `<tbody>` 
```html
<tbody>
    <!-- Baris diisi dinamis oleh assets/js/buku.js via fetch('../data/buku.json') -->
</tbody>
```

### 2.3 Urutan Tag `<script>` yang Baru
1.  `buku/list.html`:
```html
<script src="../assets/js/buku.js"></script>
```
2. Di `anggota/list.html`:
```html
<script src="../assets/js/anggota.js"></script>
```

- `app.js` dimuat **lebih dulu** (fungsi umum: hamburger, hapus, filter, validasi).
- Baru kemudian `buku.js` / `anggota.js` (khusus halaman list).
- Halaman Beranda dan halaman tambah **tidak** memuat `buku.js`/`anggota.js` karena tidak punya tabel data dinamis.

### 2.4 Kenapa `buku.js` dan `anggota.js` Dipisah?
- Karena `app.js` adalah fungsi umum yang dipakai di **banyak halaman**.
- `buku.js` dan `anggota.js` adalah fungsi spesifik fetch untuk satu jenis data.
- Memisahkan file membuat kode lebih pendek, lebih mudah dicari, dan halaman yang tidak butuh tidak memuat kode yang tidak relevan.

## 3. Data JSON: `buku.json` & `anggota.json`
Langkah yang dilakukan:
1. Add new folder `data` di dalam `jobsheet-06`
2. Add new file `buku.json` dan `anggota.json` di dalam folder `data`

### 3.3 Kenapa Nama Kuncinya Sama dengan `name` di Form?
Kunci JSON (`judul`, `pengarang`, `tahun`, `stok` / `no_anggota`, `nama`, `alamat`, `no_hp`) **persis sama** dengan atribut `name` di form Tambah Buku / Tambah Anggota. Karena penamaan konsisten di HTML, JSON, akan membuat data lebih mudah dilacak.

### 3.4 Tipe Data di Dalam JSON
- Number
   contoh : `"tahun": 2005` 
   tanpa kutip dianggap angka (bisa dilakukan operasi seperti `tahun > 2000`).
- String
   contoh : `"no_anggota": "A001"` 
   tetap string/text  karena mengandung huruf, bukan angka murni.

### 3.5 Bagaimana Data Ini Jadi Objek JavaScript?
`await res.json()` mengubah teks JSON mentah menjadi array objek JavaScript yang bisa diakses lewat `.judul`, `.pengarang`, dst.

## 4. JS: Mengambil & Menampilkan Daftar Buku
Langkah yang dilakukan:
1. Add new file `buku.js` di dalam `assets/js`
2. Hubungkan di akhir `buku/list.html` (setelah `app.js`)

### 4.1 Kode Lengkap `muatDaftarBuku`
### 4.2 Mengambil Elemen yang Dibutuhkan
```js
const tbody = document.querySelector(".table-responsive table tbody");
const loading = document.getElementById("loading-indicator");
if (!tbody) return;
```
- `tbody` untuk tempat baris hasil fetch disisipkan.
- `loading` untuk elemen indikator “Memuat data...”.
- Guard clause `if (!tbody) return;` untuk aman kalau elemen tidak ditemukan.

### 4.3 Menampilkan & Menyembunyikan Loading
```js
loading.style.display = "block";
tbody.innerHTML = "";
```
- `display = "block"` untuk teks loading muncul (menimpa `display:none` dari HTML).
- `tbody.innerHTML = ""` untuk mengosongkan tbody (aman kalau fungsi dipanggil ulang).
- Di akhir, `finally` menyembunyikan loading lagi dengan `display = "none"`.

### 4.4 Simulasi Delay Jaringan
```js
await new Promise((resolve) => setTimeout(resolve, 600));
```
- Sengaja menunda 600 milidetik supaya loading indicator sempat terlihat.
- File JSON lokal biasanya sangat cepat; tanpa delay buatan, teks “Memuat data...” hampir tidak kelihatan.
- Ini **simulasi untuk belajar**, bukan delay jaringan sungguhan.

### 4.5 Mengambil Data dan Memeriksa Keberhasilannya
```js
const res = await fetch("../data/buku.json");
if (!res.ok) {
    throw new Error("Gagal mengambil data (status " + res.status + ")");
}
const daftarBuku = await res.json();
```
- Path `../data/buku.json` untuk karena halaman ada di folder `buku/`, naik dulu ke root lalu masuk `data/`.
- `res.ok` untuk `true` kalau berhasil (status 200-an). `fetch()` **tidak otomatis** gagal hanya karena file 404, jadi harus dicek manual.
- `throw new Error(...)` untuk melempar error agar masuk ke `catch`.
- `await res.json()` untuk ubah teks JSON jadi array objek JavaScript.

### 4.6 Membuat Baris Tabel dari Data
```js
daftarBuku.forEach(function (buku) {
    const tr = document.createElement("tr");
    tr.innerHTML = "...";
    tbody.appendChild(tr);
});
```
- `forEach` untuk ulang setiap objek buku.
- `createElement("tr")` untuk buat baris baru dari kode.
- `tr.innerHTML` untuk isi 5 sel (`judul`, `pengarang`, `tahun`, `stok`, tombol aksi).
- `appendChild(tr)` untuk sisipkan baris ke `tbody`.
- Setelah selesai, `tbody` berisi 10 baris meskipun di HTML aslinya kosong.

### 4.7 Menangkap dan Menampilkan Error
```js
catch (err) {
    tbody.innerHTML =
        "<tr><td colspan=\"5\">Gagal memuat data: " + err.message + "</td></tr>";
}
```
- Kalau fetch gagal atau `throw` dipanggil, blok `catch` yang dijalankan.
- `err.message` untuk teks penjelasan error.
- `colspan="5"` untuk satu sel merentang 5 kolom supaya pesan error tampil rapi penuh.

### 4.8 Blok `finally`
```js
finally {
    loading.style.display = "none";
}
```

- `finally` **selalu** dijalankan, berhasil maupun gagal.
- Kalau menyembunyikan loading hanya di akhir `try`, saat error loading bisa macet tampil selamanya.

### 4.9 Memanggil Fungsi Saat Halaman Siap
```js
document.addEventListener("DOMContentLoaded", muatDaftarBuku);
```
Memastikan HTML siap jadi DOM dulu, baru ambil data.

## 5. JS: Mengambil & Menampilkan Daftar Anggota
Struktur anggota.js ini sama dengan buku.js, yang berbeda hanya nama fungsi, variabel, object. dsb.
Langkah yang dilakukan:
1. Add new file `anggota.js` di dalam `assets/js`
2. Hubungkan di akhir `anggota/list.html` (setelah `app.js`)

### 5.3 Kenapa Kolom yang Diakses Harus Sama dengan JSON?
Kalau salah ketik kunci (misalnya `anggota.nomor` padahal di JSON `no_anggota`), JavaScript tidak akan menganggapnya error, nilai jadi `undefined`, dan sel tabel tampil kosong. Jadi nama kunci harus sama.

## 6. JS: Event Delegation pada Tombol Hapus
Sebelumnya : 
- `querySelectorAll(".btn-hapus")` mencari tombol yang **sudah ada** saat `DOMContentLoaded`.
- Lalu memasang listener ke **tiap** tombol satu per satu.

Sesudah : 
```js
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
        }
    });
}
```

### 6.3 Apa Masalah yang Diperbaiki?
Di jobsheet-06, `<tbody>` kosong saat halaman baru dimuat. Tombol `.btn-hapus` baru dibuat oleh `buku.js`/`anggota.js` **setelah** `fetch` selesai (plus delay 600ms).
Jika menggunakan versi lama : 
1. `DOMContentLoaded` terpicu.
2. `querySelectorAll(".btn-hapus")` jalan → **belum ada** tombol.
3. Tidak ada listener yang terpasang.
4. Tombol Hapus yang muncul belakangan **tidak bereaksi** saat diklik.

### 6.4 Solusi: Event Delegation
**Event delegation** = pasang **satu** listener di elemen root yang stabil (`document`), bukan di tiap tombol.
Teknik ini memanfaatkan **event bubbling**: klik di tombol menjalar ke atas (tombol → `td` → `tr` → ... → `document`).
- `e.target` → elemen paling spesifik yang diklik.
- `e.target.closest(".btn-hapus")` → cari ke atas, apakah klik itu dari tombol Hapus.
- `if (!btn) return;` → kalau klik di tempat lain, abaikan.
- Sisa logika (`closest("tr")`, `confirm()`, `row.remove()`) sama seperti jobsheet-05.

### 6.5 Kenapa Berhasil untuk Tombol yang “Belum Ada”?
Karena listener ada di `document` (selalu ada sejak awal), ia **tidak peduli** kapan tombol dibuat. Selama tombol sudah ada di DOM **saat diklik**, klik itu tetap menjalar ke `document` dan terdeteksi.



