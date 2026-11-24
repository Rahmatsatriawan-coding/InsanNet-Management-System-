<?php

require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

// Validasi session dan role
require_role('sales');

// id_sales selalu dari session, tidak dari GET/POST
$idSales = (int) $_SESSION['user']['id_pengguna'];

// Ambil data pelanggan sales
function daftar_pelanggan_sales(mysqli $koneksi, int $idSales): array
{
    $stmt = $koneksi->prepare(
        'SELECT
             p.id_pelanggan,
             p.nama_lengkap,
             p.no_hp_wa,
             p.alamat,
             p.foto_rumah,
             p.status_pelanggan,
             p.tanggal_daftar,
             pk.nama_paket,
             pk.kecepatan_mbps,
             pk.harga_bulanan,
             o.nama_odp,
             o.wilayah AS wilayah_odp
         FROM pelanggan p
         JOIN paket pk ON pk.id_paket = p.id_paket
         LEFT JOIN odp o ON o.id_odp = p.id_odp
         WHERE p.id_sales = ?
         ORDER BY p.tanggal_daftar DESC, p.id_pelanggan DESC'
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $idSales);
    if (!$stmt->execute()) {
        $stmt->close();
        return [];
    }

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

$daftarPelanggan = daftar_pelanggan_sales($koneksi, $idSales);
