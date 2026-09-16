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

- Form biasa (jobsheet-01) → submit = halaman dimuat ulang seluruhnya.
- AJAX → JavaScript ambil data di **latar belakang**, lalu isi sebagian halaman saja (di jobsheet ini: mengisi `<tbody>` tabel).

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

- `try` → bungkus kode yang berpotensi gagal.
- `catch` → tangkap error supaya program tidak crash; bisa tampilkan pesan ke pengguna.
- `finally` → selalu dijalankan (cocok untuk menyembunyikan loading indicator).


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