<?php

require __DIR__ . '/includes/koneksi.php';

$result = supabaseRequest(
    'POST',
    'buku',
    [
        'judul' => 'Belajar PHP REST API',
        'pengarang' => 'Syeril Azalea',
        'tahun' => 2026,
        'isbn' => '9781234567890',
        'stok' => 5,
        'kategori' => 'Pemrograman'
    ]
);

echo '<pre>';
print_r($result);
echo '</pre>';