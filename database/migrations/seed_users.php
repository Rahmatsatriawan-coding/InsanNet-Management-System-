<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/app/config/database.php';

$sampleUsers = [
    [
        'Administrator',
        'admin',
        'Admin123',
        'admin@insannet.com',
        '081234567890',
        'admin',
    ],
    [
        'Budi Santoso',
        'sales01',
        'Sales123',
        'sales@insannet.com',
        '081234567891',
        'sales',
    ],
    [
        'Andi Pratama',
        'teknisi01',
        'Teknisi123',
        'teknisi@insannet.com',
        '081234567892',
        'teknisi',
    ],
];

$find = $koneksi->prepare(
    'SELECT id_pengguna, password_hash FROM pengguna WHERE username = ? LIMIT 1'
);
$insert = $koneksi->prepare(
    'INSERT INTO pengguna
     (nama_lengkap, username, password_hash, email, no_hp, peran, status_aktif)
     VALUES (?, ?, ?, ?, ?, ?, 1)'
);
$update = $koneksi->prepare(
    'UPDATE pengguna SET password_hash = ? WHERE id_pengguna = ?'
);

$inserted = 0;
$converted = 0;
$skipped = 0;
foreach ($sampleUsers as [$nama, $username, $password, $email, $noHp, $peran]) {
    $find->bind_param('s', $username);
    $find->execute();
    $find->bind_result($existingId, $existingPassword);
    $found = $find->fetch();
    $find->free_result();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    if ($found) {
        if (hash_equals($password, (string) $existingPassword)) {
            $update->bind_param('si', $passwordHash, $existingId);
            $update->execute();
            $converted++;
        } else {
            $skipped++;
        }
        continue;
    }

    $insert->bind_param('ssssss', $nama, $username, $passwordHash, $email, $noHp, $peran);
    $insert->execute();
    $inserted++;
}

$find->close();
$insert->close();
$update->close();
echo "Akun ditambahkan: {$inserted}; hash password lama diperbarui: {$converted}; akun dilewati: {$skipped}.\n";
