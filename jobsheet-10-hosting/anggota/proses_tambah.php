<?php

require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if (!empty($errors)) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

$result = supabaseRequest(
    'POST',
    'anggota',
    [
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp
    ]
);

if ($result['status'] >= 200 && $result['status'] < 300) {

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Anggota berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;
}

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => 'Anggota gagal ditambahkan.'
];

header('Location: tambah.php');
exit;