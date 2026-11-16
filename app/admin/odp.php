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
    $id = filter_var($_POST['id_odp'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        redirect_form_result('invalid', $fallback);
    }

    try {
        form_transaction($koneksi, function () use ($koneksi, $currentUserId, $id): void {
            $stmt = $koneksi->prepare('UPDATE odp SET status_aktif = 0 WHERE id_odp = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $changed = $stmt->affected_rows;
            $stmt->close();
            if ($changed === 0) {
                throw new DomainException('ODP tidak ditemukan atau sudah nonaktif.');
            }

            catat_log($koneksi, $currentUserId, 'nonaktifkan', 'odp', $id, 'ODP dinonaktifkan');
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

$nama = trim((string) ($_POST['nama_odp'] ?? ''));
$wilayah = trim((string) ($_POST['wilayah'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));
$latitudeText = trim((string) ($_POST['latitude'] ?? ''));
$longitudeText = trim((string) ($_POST['longitude'] ?? ''));
$kapasitasText = trim((string) ($_POST['kapasitas_port'] ?? ''));
$tarikanText = trim((string) ($_POST['jumlah_tarikan'] ?? ''));
$keterangan = trim((string) ($_POST['keterangan'] ?? ''));
$tanggal = trim((string) ($_POST['tanggal_input'] ?? ''));
$statusAktif = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);
$editId = filter_var($_POST['id_odp'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);

$latitude = $latitudeText === '' ? null : filter_var($latitudeText, FILTER_VALIDATE_FLOAT);
$longitude = $longitudeText === '' ? null : filter_var($longitudeText, FILTER_VALIDATE_FLOAT);
$kapasitas = $kapasitasText === '' ? null : filter_var($kapasitasText, FILTER_VALIDATE_INT);
$tarikan = $tarikanText === '' ? null : filter_var($tarikanText, FILTER_VALIDATE_INT);
$date = $tanggal === '' ? null : DateTime::createFromFormat('!Y-m-d', $tanggal);
$validDate = $tanggal === '' || ($date && $date->format('Y-m-d') === $tanggal);
$validCoordinates = ($latitudeText === '' || ($latitude !== false && $latitude >= -90 && $latitude <= 90))
    && ($longitudeText === '' || ($longitude !== false && $longitude >= -180 && $longitude <= 180));
$validCounts = ($kapasitasText === '' || ($kapasitas !== false && $kapasitas >= 0))
    && ($tarikanText === '' || ($tarikan !== false && $tarikan >= 0));
$valid = $nama !== ''
    && form_text_length_valid($nama, 100)
    && form_text_length_valid($wilayah, 50)
    && form_text_length_valid($alamat, 255)
    && $validCoordinates
    && $validCounts
    && $validDate
    && ($statusAktif === 0 || $statusAktif === 1);
if ($action === 'update') {
    $valid = $valid && $editId !== false && $editId !== null && $editId > 0;
}

if (!$valid) {
    redirect_form_result('invalid', $fallback);
}
if ($action === 'update' && ambil_odp($koneksi, $editId) === null) {
    redirect_form_result('not_found', $fallback);
}

try {
    form_transaction($koneksi, function () use (
        $koneksi,
        $currentUserId,
        $action,
        $editId,
        $nama,
        $wilayah,
        $alamat,
        $latitude,
        $longitude,
        $kapasitas,
        $tarikan,
        $keterangan,
        $tanggal,
        $statusAktif
    ): void {
        if ($action === 'create') {
            $stmt = $koneksi->prepare(
                'INSERT INTO odp
                 (id_penginput, nama_odp, wilayah, alamat, latitude, longitude, kapasitas_port,
                  jumlah_tarikan, keterangan, tanggal_input, status_aktif)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param(
                'isssddiissi',
                $currentUserId,
                $nama,
                $wilayah,
                $alamat,
                $latitude,
                $longitude,
                $kapasitas,
                $tarikan,
                $keterangan,
                $tanggal,
                $statusAktif
            );
            $stmt->execute();
            $id = (int) $koneksi->insert_id;
            $stmt->close();
            catat_log($koneksi, $currentUserId, 'tambah', 'odp', $id, 'ODP ditambahkan');
            return;
        }

        $stmt = $koneksi->prepare(
            'UPDATE odp SET nama_odp = ?, wilayah = ?, alamat = ?, latitude = ?, longitude = ?,
             kapasitas_port = ?, jumlah_tarikan = ?, keterangan = ?, tanggal_input = ?,
             status_aktif = ? WHERE id_odp = ?'
        );
        $stmt->bind_param(
            'sssddiissii',
            $nama,
            $wilayah,
            $alamat,
            $latitude,
            $longitude,
            $kapasitas,
            $tarikan,
            $keterangan,
            $tanggal,
            $statusAktif,
            $editId
        );
        $stmt->execute();
        $stmt->close();
        catat_log($koneksi, $currentUserId, 'ubah', 'odp', $editId, 'Data ODP diubah');
    });
    redirect_form_result('success', $fallback);
} catch (mysqli_sql_exception $exception) {
    redirect_form_result('error', $fallback);
}
