<?php

require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

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

    header(
        'Location: edit.php?id=' . urlencode($id)
    );

    exit;
}

$result = supabaseRequest(
    'PATCH',
    'anggota',
    [
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp
    ],
    'id=eq.' . (int) $id
);

if ($result['status'] >= 200 && $result['status'] < 300) {

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Anggota berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;
}

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => 'Anggota gagal diperbarui.'
];

header(
    'Location: edit.php?id=' . urlencode($id)
);

exit;