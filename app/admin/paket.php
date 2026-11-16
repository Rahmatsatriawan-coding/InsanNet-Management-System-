<?php

require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/form_response.php';
require_once dirname(__DIR__) . '/includes/data_master.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/catat_log.php';

require_role('admin');
require_form_post();
verify_csrf_token();

$fallback = public_base_path() . '/admin/dashboard.html';
$currentUserId = (int) $_SESSION['user']['id_pengguna'];
$action = (string) ($_POST['action'] ?? '');

if ($action === 'deactivate') {
    $id = filter_var($_POST['id_paket'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        redirect_form_result('invalid', $fallback);
    }

    try {
        form_transaction($koneksi, function () use ($koneksi, $currentUserId, $id): void {
            $stmt = $koneksi->prepare('UPDATE paket SET status_aktif = 0 WHERE id_paket = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $changed = $stmt->affected_rows;
            $stmt->close();
            if ($changed === 0) {
                throw new DomainException('Paket tidak ditemukan atau sudah nonaktif.');
            }

            catat_log($koneksi, $currentUserId, 'nonaktifkan', 'paket', $id, 'Paket dinonaktifkan');
        });
        redirect_form_result('success', $fallback);
    } catch (DomainException $exception) {
        redirect_form_result('not_found', $fallback);
    } catch (mysqli_sql_exception $exception) {
        redirect_form_result('error', $fallback);
    }
}

if ($action !== 'create' && $action !== 'update') {
    redirect_form_result('invalid', $fallback);
}

$nama = trim((string) ($_POST['nama_paket'] ?? ''));
$kecepatan = filter_var($_POST['kecepatan_mbps'] ?? null, FILTER_VALIDATE_INT);
$harga = trim((string) ($_POST['harga_bulanan'] ?? ''));
$deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));
$statusAktif = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);
$editId = filter_var($_POST['id_paket'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);

$valid = $nama !== ''
    && form_text_length_valid($nama, 100)
    && $kecepatan !== false
    && $kecepatan > 0
    && is_numeric($harga)
    && (float) $harga > 0
    && ($statusAktif === 0 || $statusAktif === 1);
if ($action === 'update') {
    $valid = $valid && $editId !== false && $editId !== null && $editId > 0;
}

if (!$valid) {
    redirect_form_result('invalid', $fallback);
}
if ($action === 'update' && ambil_paket($koneksi, $editId) === null) {
    redirect_form_result('not_found', $fallback);
}

try {
    form_transaction($koneksi, function () use (
        $koneksi,
        $currentUserId,
        $action,
        $editId,
        $nama,
        $kecepatan,
        $harga,
        $deskripsi,
        $statusAktif
    ): void {
        if ($action === 'create') {
            $stmt = $koneksi->prepare(
                'INSERT INTO paket
                 (nama_paket, kecepatan_mbps, harga_bulanan, deskripsi, status_aktif, id_admin)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('sidsii', $nama, $kecepatan, $harga, $deskripsi, $statusAktif, $currentUserId);
            $stmt->execute();
            $id = (int) $koneksi->insert_id;
            $stmt->close();
            catat_log($koneksi, $currentUserId, 'tambah', 'paket', $id, 'Paket ditambahkan');
            return;
        }

        $stmt = $koneksi->prepare(
            'UPDATE paket SET nama_paket = ?, kecepatan_mbps = ?, harga_bulanan = ?,
             deskripsi = ?, status_aktif = ? WHERE id_paket = ?'
        );
        $stmt->bind_param('sidsii', $nama, $kecepatan, $harga, $deskripsi, $statusAktif, $editId);
        $stmt->execute();
        $stmt->close();
        catat_log($koneksi, $currentUserId, 'ubah', 'paket', $editId, 'Data paket diubah');
    });
    redirect_form_result('success', $fallback);
} catch (mysqli_sql_exception $exception) {
    redirect_form_result('error', $fallback);
}
