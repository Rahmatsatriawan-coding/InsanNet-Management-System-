<?php

$envPath = dirname(__DIR__, 2) . '/.env';

if (!file_exists($envPath)) {
    die("File .env tidak ditemukan!");
}

// memeriksa konfigurasi .env
$config = parse_ini_file($envPath, false, INI_SCANNER_RAW);

if ($config === false) {
    die("Gagal membaca konfigurasi .env!");
}

// mengambil konfigurasi database
$host     = $config['DB_HOST'] ?? 'localhost';
$database = $config['DB_NAME'] ?? 'insannet';
$username = $config['DB_USER'] ?? 'root';
$password = $config['DB_PASS'] ?? '';

// menghubungkan ke MySQL
$koneksi = new mysqli(
    $host,
    $username,
    $password,
    $database
);

// memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

//charset utf8mb4
$koneksi->set_charset("utf8mb4");
