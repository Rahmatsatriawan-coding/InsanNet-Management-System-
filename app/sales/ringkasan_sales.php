<?php

require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

// Validasi session dan role
require_role('sales');

// id_sales selalu dari session
$idSales = (int) $_SESSION['user']['id_pengguna'];

// Periode bulan berjalan dalam format YYYY-MM sesuai kolom `periode` di tabel target_sales
$periodeSekarang = date('Y-m');
$bulanSekarang   = (int) date('m');
$tahunSekarang   = (int) date('Y');

// Hitung target dan capaian sales
$targetBulanIni = 0;

$stmtTarget = $koneksi->prepare(
    'SELECT target_pendaftaran
     FROM target_sales
     WHERE id_sales = ? AND periode = ?
     LIMIT 1'
);

if ($stmtTarget) {
    $stmtTarget->bind_param('is', $idSales, $periodeSekarang);
    $stmtTarget->execute();
    $rowTarget = $stmtTarget->get_result()->fetch_assoc();
    $stmtTarget->close();

    if ($rowTarget) {
        $targetBulanIni = (int) $rowTarget['target_pendaftaran'];
    }
}

$capaianBulanIni = 0;

$stmtCapaian = $koneksi->prepare(
    'SELECT COUNT(*) AS jumlah
     FROM pelanggan
     WHERE id_sales = ?
       AND MONTH(tanggal_daftar) = ?
       AND YEAR(tanggal_daftar)  = ?'
);

if ($stmtCapaian) {
    $stmtCapaian->bind_param('iii', $idSales, $bulanSekarang, $tahunSekarang);
    $stmtCapaian->execute();
    $rowCapaian = $stmtCapaian->get_result()->fetch_assoc();
    $stmtCapaian->close();

    if ($rowCapaian) {
        $capaianBulanIni = (int) $rowCapaian['jumlah'];
    }
}

$persentaseCapaian = 0;
if ($targetBulanIni > 0) {
    $persentaseCapaian = min(100, (int) round(($capaianBulanIni / $targetBulanIni) * 100));
}
