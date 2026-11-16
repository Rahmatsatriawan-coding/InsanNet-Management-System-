<?php

function catat_log(
    mysqli $koneksi,
    int $idPengguna,
    string $aksi,
    ?string $tabelTerkait = null,
    ?int $idData = null,
    ?string $detail = null
): void {
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = $koneksi->prepare(
        'INSERT INTO log_aktivitas (id_pengguna, aksi, tabel_terkait, id_data, detail, ip_address)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        throw new RuntimeException('Gagal menyiapkan pencatatan log aktivitas.');
    }

    $stmt->bind_param('isisss', $idPengguna, $aksi, $tabelTerkait, $idData, $detail, $ipAddress);
    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException('Gagal menyimpan log aktivitas.');
    }

    $stmt->close();
}
