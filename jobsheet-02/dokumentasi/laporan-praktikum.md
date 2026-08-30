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

## CSS Style
1. Perubahan File HTML
Menambahkan tag untuk file css style agar bisa dimuat di page html.
```html
<link rel="stylesheet" href="assets/css/style.css">
```
Link ditambahkan pada
| File HTML | Lokasi File | `href` yang Dipakai |
|---|---|---|
| `index.html` | folder root (`jobsheet-02/`) | `assets/css/style.css` |
| `buku/list.html` | dalam folder `buku/` | `../assets/css/style.css` |
| `buku/tambah.html` | dalam folder `buku/` | `../assets/css/style.css` |
| `anggota/list.html` | dalam folder `anggota/` | `../assets/css/style.css` |
| `anggota/tambah.html` | dalam folder `anggota/` | `../assets/css/style.css` |

2. CSS : Reset & Gaya Dasar Body

- Menambahkan **CSS Reset dengan Selektor Universal**

```css
/* ===== Reset & Base ===== */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
```
- Menambahkan Gaya Dasar `<body>`
```css
body {
    font-family: "Segoe UI", Arial, sans-serif;
    color: #2b2b2b;
    background-color: #f5f6f8;
    line-height: 1.5;
}
```
3.  CSS: Header & Navbar dengan Flexbox
Penerapan flexbox pada header dan navbar yang bertujuan untuk menyusun elemen secara horizontal

```css
/* ===== Header & Navbar (Flexbox) ===== */
header {
    background-color: #1d5b8a;
    color: #fff;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

header h1 {
    font-size: 1.4rem;
}

header nav ul {
    list-style: none;
    display: flex;
    gap: 1.25rem;
}

header nav a {
    color: #fff;
    font-weight: 500;
}
```

4. CSS: Layout `main` & `section` 
Memberikan style lebar pada **main** dan mengatur style **section** agar ditampilkan dalam
bentuk card.

```css
/* ===== Main Layout ===== */
main {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

section {
    background-color: #fff;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

section h2 {
    margin-bottom: 1rem;
    color: #1d5b8a;
}
```

5. CSS: Kartu Statistik dengan CSS Grid
Penggunaan style CSS Grid untuk menyusun card agar tersususn secara horizontal

```html
<main>
    <section>                          <!-- section ke-1: sambutan -->
        <h2>Selamat Datang...</h2>
        <p>...</p>
    </section>

    <section>                          <!-- section ke-2: ringkasan statistik -->
        <h2>Ringkasan</h2>
        <article>...Total Buku...</article>
        <article>...Total Anggota...</article>
        <article>...Sedang Dipinjam...</article>
    </section>
</main>
```

6. CSS: Styling Table
Memberikan style css pada table, table head, table body, tr, dan td, serta menambahkan hover.

```css
/* ===== Tabel ===== */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    padding: 0.65rem 0.75rem;
    border-bottom: 1px solid #e2e6ea;
}

thead {
    background-color: #1d5b8a;
    color: #fff;
}

tbody tr:nth-child(even) {
    background-color: #f7f9fb;
}

tbody tr:hover {
    background-color: #eef4fa;
}

td button {
    padding: 0.35rem 0.7rem;
    margin-right: 0.35rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
}
```

7. CSS: Styling Form
Menambahkan style CSS pada form input, mengatur jarak antar field, dan button styling.


```css
/* ===== Form ===== */
form p {
    margin-bottom: 1rem;
}

form label {
    display: block;
    margin-bottom: 0.35rem;
    font-weight: 600;
    color: #444;
}

form input,
form select {
    width: 100%;
    max-width: 400px;
    padding: 0.55rem 0.7rem;
    border: 1px solid #cdd4da;
    border-radius: 4px;
    font-size: 1rem;
}

form button[type="submit"] {
    background-color: #1d5b8a;
    color: #fff;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
}

form button[type="submit"]:hover {
    background-color: #164869;
}
```

8. CSS: Footer
Memberikan style css pada footer agar terletak di tengah dengan ukuran padding yang konsisten.

```css
/* ===== Footer ===== */
footer {
    text-align: center;
    padding: 1.25rem;
    color: #7a8794;
    font-size: 0.9rem;
}
```