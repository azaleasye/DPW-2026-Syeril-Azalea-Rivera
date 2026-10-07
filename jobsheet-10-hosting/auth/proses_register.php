<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

if ($username === '') {
    $errors[] = "Username wajib diisi.";
}

if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: register.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Cek username
|--------------------------------------------------------------------------
*/

$cek = supabaseRequest(
    'GET',
    'users',
    null,
    'select=id&username=eq.' . urlencode($username)
);

if ($cek['status'] >= 200 && $cek['status'] < 300) {

    if (!empty($cek['data'])) {

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Username sudah digunakan.'
        ];

        header('Location: register.php');
        exit;
    }

} else {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal mengecek username.'
    ];

    header('Location: register.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Simpan user baru
|--------------------------------------------------------------------------
*/

$result = supabaseRequest(
    'POST',
    'users',
    [
        'nama' => $nama,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'petugas'
    ]
);


/*
|--------------------------------------------------------------------------
| Cek hasil registrasi
|--------------------------------------------------------------------------
*/

if ($result['status'] >= 200 && $result['status'] < 300) {

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Registrasi berhasil, silakan login.'
    ];

    header('Location: login.php');
    exit;
}


$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => 'Registrasi gagal.'
];

header('Location: register.php');
exit;