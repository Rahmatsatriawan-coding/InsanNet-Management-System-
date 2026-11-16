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
    $id = filter_var($_POST['id_pengguna'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1 || $id === $currentUserId) {
        redirect_form_result('invalid', $fallback);
    }

    try {
        form_transaction($koneksi, function () use ($koneksi, $currentUserId, $id): void {
            $stmt = $koneksi->prepare('UPDATE pengguna SET status_aktif = 0 WHERE id_pengguna = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $changed = $stmt->affected_rows;
            $stmt->close();
            if ($changed === 0) {
                throw new DomainException('Pengguna tidak ditemukan atau sudah nonaktif.');
            }

            catat_log($koneksi, $currentUserId, 'nonaktifkan', 'pengguna', $id, 'Pengguna dinonaktifkan');
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

$nama = trim((string) ($_POST['nama_lengkap'] ?? ''));
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$email = trim((string) ($_POST['email'] ?? ''));
$noHp = trim((string) ($_POST['no_hp'] ?? ''));
$peran = (string) ($_POST['peran'] ?? '');
$statusAktif = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);
$editId = filter_var($_POST['id_pengguna'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);

$valid = $nama !== ''
    && form_text_length_valid($nama, 100)
    && $username !== ''
    && form_text_length_valid($username, 50)
    && in_array($peran, ['admin', 'sales', 'teknisi'], true)
    && ($email === '' || (form_text_length_valid($email, 100) && filter_var($email, FILTER_VALIDATE_EMAIL)))
    && form_text_length_valid($noHp, 20)
    && ($statusAktif === 0 || $statusAktif === 1);

if ($action === 'create') {
    $valid = $valid && strlen($password) >= 8;
} else {
    $valid = $valid && $editId !== false && $editId !== null && $editId > 0;
    $valid = $valid && ($password === '' || strlen($password) >= 8);
    $valid = $valid && !($editId === $currentUserId && $statusAktif === 0);
}

if (!$valid) {
    redirect_form_result('invalid', $fallback);
}

if ($action === 'update' && ambil_pengguna($koneksi, $editId) === null) {
    redirect_form_result('not_found', $fallback);
}

try {
    form_transaction($koneksi, function () use (
        $koneksi,
        $currentUserId,
        $action,
        $editId,
        $nama,
        $username,
        $password,
        $email,
        $noHp,
        $peran,
        $statusAktif
    ): void {
        if ($action === 'create') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare(
                'INSERT INTO pengguna
                 (nama_lengkap, username, password_hash, email, no_hp, peran, status_aktif)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('ssssssi', $nama, $username, $passwordHash, $email, $noHp, $peran, $statusAktif);
            $stmt->execute();
            $id = (int) $koneksi->insert_id;
            $stmt->close();
            catat_log($koneksi, $currentUserId, 'tambah', 'pengguna', $id, 'Pengguna ditambahkan');
            return;
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare(
                'UPDATE pengguna SET nama_lengkap = ?, username = ?, password_hash = ?, email = ?,
                 no_hp = ?, peran = ?, status_aktif = ? WHERE id_pengguna = ?'
            );
            $stmt->bind_param(
                'ssssssii',
                $nama,
                $username,
                $passwordHash,
                $email,
                $noHp,
                $peran,
                $statusAktif,
                $editId
            );
        } else {
            $stmt = $koneksi->prepare(
                'UPDATE pengguna SET nama_lengkap = ?, username = ?, email = ?, no_hp = ?,
                 peran = ?, status_aktif = ? WHERE id_pengguna = ?'
            );
            $stmt->bind_param('sssssii', $nama, $username, $email, $noHp, $peran, $statusAktif, $editId);
        }
        $stmt->execute();
        $stmt->close();
        catat_log($koneksi, $currentUserId, 'ubah', 'pengguna', $editId, 'Data pengguna diubah');
    });
    redirect_form_result('success', $fallback);
} catch (mysqli_sql_exception $exception) {
    redirect_form_result('error', $fallback);
}
