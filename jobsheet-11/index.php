<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
?>
            <section>
                <h2>Selamat datang di Sistem Perpustakaan Mini</h2>
                <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
            </section>

            <section>
                <h2>Ringkasan</h2>
                <article>
                    <h3>Total Buku</h3>
                    <p>12</p>
                </article>
                <article>
                    <h3>Total Anggota</h3>
                    <p>8</p>
                </article>
                <article>
                    <h3>Sedang Dipinjam</h3>
                    <p>3</p>
                </article>
                <article>
                    <h3>Buku Terlambat</h3>
                    <p>5</p>
                </article>
            </section>
<?php include __DIR__ . '/includes/footer.php'; ?>