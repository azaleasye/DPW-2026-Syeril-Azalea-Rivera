<?php

$config = require __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['db_host']};"
         . "port={$config['db_port']};"
         . "dbname={$config['db_name']};"
         . "sslmode=require";

    $pdo = new PDO(
        $dsn,
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Koneksi database gagal.");
}
