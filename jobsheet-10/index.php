<?php

$page_title = "Beranda";

include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';


/*
|--------------------------------------------------------------------------
| Mengambil jumlah buku
|--------------------------------------------------------------------------
*/

$resultBuku = supabaseRequest(
    'GET',
    'buku',
    null,
    'select=id'
);

$totalBuku = 0;

if ($resultBuku['status'] >= 200 && $resultBuku['status'] < 300) {
    $totalBuku = count($resultBuku['data']);
}


/*
|--------------------------------------------------------------------------
| Mengambil jumlah anggota
|--------------------------------------------------------------------------
*/

$resultAnggota = supabaseRequest(
    'GET',
    'anggota',
    null,
    'select=id'
);

$totalAnggota = 0;

if ($resultAnggota['status'] >= 200 && $resultAnggota['status'] < 300) {
    $totalAnggota = count($resultAnggota['data']);
}

?>

<section>
    <h2>Selamat datang di Sistem Perpustakaan Mini</h2>

    <p>
        Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.
    </p>
</section>

<section>
    <h2>Ringkasan</h2>

    <article>
        <h3>Total Buku</h3>
        <p><?php echo $totalBuku; ?></p>
    </article>

    <article>
        <h3>Total Anggota</h3>
        <p><?php echo $totalAnggota; ?></p>
    </article>

    <article>
        <h3>Sedang Dipinjam</h3>
        <p>0</p>
    </article>

    <article>
        <h3>Buku Terlambat</h3>
        <p>0</p>
    </article>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>