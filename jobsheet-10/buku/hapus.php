<?php

require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {

    $result = supabaseRequest(
        'DELETE',
        'buku',
        null,
        'id=eq.' . (int) $id
    );

    if ($result['status'] >= 200 && $result['status'] < 300) {

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Buku berhasil dihapus.'
        ];

    } else {

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Buku gagal dihapus.'
        ];
    }
}

header('Location: list.php');
exit;