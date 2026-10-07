<?php

require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;

$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];

if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}

if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}

if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}

if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

if (!empty($errors)) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header(
        'Location: edit.php?id=' . urlencode($id)
    );

    exit;
}

$result = supabaseRequest(
    'PATCH',
    'buku',
    [
        'judul' => $judul,
        'pengarang' => $pengarang,
        'tahun' => (int) $tahun,
        'isbn' => $isbn,
        'stok' => (int) $stok,
        'kategori' => $kategori
    ],
    'id=eq.' . (int) $id
);

if ($result['status'] >= 200 && $result['status'] < 300) {

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Buku berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;
}

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => 'Buku gagal diperbarui.'
];

header(
    'Location: edit.php?id=' . urlencode($id)
);

exit;