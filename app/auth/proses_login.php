<?php

require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/catat_log.php';
require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('405 - Metode tidak diizinkan.');
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header('Location: ../../public/login.html?error=1');
    exit;
}

$stmt = $koneksi->prepare(
    'SELECT id_pengguna, nama_lengkap, username, password_hash, peran, status_aktif
     FROM pengguna
     WHERE username = ?
     LIMIT 1'
);
if (!$stmt) {
    throw new RuntimeException('Gagal menyiapkan pemeriksaan login.');
}

$stmt->bind_param('s', $username);
if (!$stmt->execute()) {
    $stmt->close();
    throw new RuntimeException('Gagal memeriksa kredensial login.');
}

$stmt->bind_result($idPengguna, $namaLengkap, $namaPengguna, $passwordHash, $peran, $statusAktif);
$found = $stmt->fetch();
$stmt->close();

if (!$found || (int) $statusAktif !== 1 || !password_verify($password, $passwordHash)) {
    header('Location: ../../public/login.html?error=1');
    exit;
}

session_regenerate_id(true);
$_SESSION['user'] = [
    'id_pengguna' => (int) $idPengguna,
    'nama_lengkap' => $namaLengkap,
    'username' => $namaPengguna,
    'peran' => $peran,
];

catat_log($koneksi, (int) $idPengguna, 'login', 'pengguna', (int) $idPengguna, 'Login berhasil');

$destinations = [
    'admin'   => '../../public/admin/dashboard.php',
    'sales'   => '../../public/sales/registrasi.php',
    'teknisi' => '../../public/teknisi/tugas.php',
];
header('Location: ' . ($destinations[$peran] ?? '../../public/index.html'));
exit;
