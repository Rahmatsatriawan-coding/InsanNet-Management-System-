<?php

function daftar_pengguna(mysqli $koneksi): array
{
    $stmt = $koneksi->prepare(
        'SELECT id_pengguna, nama_lengkap, username, email, no_hp, peran, status_aktif, created_at
         FROM pengguna ORDER BY id_pengguna'
    );
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

function ambil_pengguna(mysqli $koneksi, int $idPengguna): ?array
{
    $stmt = $koneksi->prepare(
        'SELECT id_pengguna, nama_lengkap, username, email, no_hp, peran, status_aktif
         FROM pengguna WHERE id_pengguna = ?'
    );
    $stmt->bind_param('i', $idPengguna);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

function daftar_paket(mysqli $koneksi): array
{
    $stmt = $koneksi->prepare(
        'SELECT id_paket, nama_paket, kecepatan_mbps, harga_bulanan, deskripsi, status_aktif
         FROM paket ORDER BY id_paket'
    );
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

function ambil_paket(mysqli $koneksi, int $idPaket): ?array
{
    $stmt = $koneksi->prepare(
        'SELECT id_paket, nama_paket, kecepatan_mbps, harga_bulanan, deskripsi, status_aktif
         FROM paket WHERE id_paket = ?'
    );
    $stmt->bind_param('i', $idPaket);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

function daftar_odp(mysqli $koneksi): array
{
    $stmt = $koneksi->prepare(
        'SELECT id_odp, id_penginput, nama_odp, wilayah, alamat, latitude, longitude,
         kapasitas_port, jumlah_tarikan, keterangan, foto_odp, tanggal_input, status_aktif, updated_at
         FROM odp ORDER BY id_odp'
    );
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

function ambil_odp(mysqli $koneksi, int $idOdp): ?array
{
    $stmt = $koneksi->prepare(
        'SELECT id_odp, id_penginput, nama_odp, wilayah, alamat, latitude, longitude,
         kapasitas_port, jumlah_tarikan, keterangan, foto_odp, tanggal_input, status_aktif, updated_at
         FROM odp WHERE id_odp = ?'
    );
    $stmt->bind_param('i', $idOdp);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}
