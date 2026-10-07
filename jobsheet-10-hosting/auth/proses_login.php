<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

/*
|--------------------------------------------------------------------------
| Ambil user berdasarkan username
|--------------------------------------------------------------------------
*/

$result = supabaseRequest(
    'GET',
    'users',
    null,
    'select=*&username=eq.' . urlencode($username)
);

$user = null;

if ($result['status'] >= 200 && $result['status'] < 300) {
    $user = $result['data'][0] ?? null;
}


/*
|--------------------------------------------------------------------------
| Cek username dan password
|--------------------------------------------------------------------------
*/

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    header('Location: ../index.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Login gagal
|--------------------------------------------------------------------------
*/

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => 'Username atau password salah.'
];

header('Location: login.php');
exit;