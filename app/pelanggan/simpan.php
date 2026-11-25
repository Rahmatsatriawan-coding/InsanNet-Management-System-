<?php

require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/form_response.php';
require_once dirname(__DIR__) . '/includes/catat_log.php';
require_once dirname(__DIR__) . '/config/database.php';

// Validasi session dan role
require_role('sales');
require_form_post();


// id_sales selalu dari session, tidak dari POST/GET
$idSales  = (int) $_SESSION['user']['id_pengguna'];
$fallback = public_base_path() . '/sales/registrasi.php';

// Validasi data pelanggan
$nama   = trim((string) ($_POST['nama']   ?? ''));
$noHp   = trim((string) ($_POST['no_hp'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));

$idPaket = filter_var($_POST['id_paket'] ?? null, FILTER_VALIDATE_INT);
$idOdp   = filter_var($_POST['id_odp']   ?? null, FILTER_VALIDATE_INT);

$errors = [];

if ($nama === '') {
    $errors[] = 'Nama pelanggan wajib diisi.';
} elseif (!form_text_length_valid($nama, 100)) {
    $errors[] = 'Nama pelanggan terlalu panjang (maks 100 karakter).';
}

// Format nomor HP: 8–20 digit, boleh diawali +
if ($noHp === '') {
    $errors[] = 'Nomor HP wajib diisi.';
} elseif (!preg_match('/^\+?[\d\s\-]{8,20}$/', $noHp)) {
    $errors[] = 'Nomor HP tidak valid (8–20 digit, boleh diawali +).';
}

if ($alamat === '') {
    $errors[] = 'Alamat wajib diisi.';
}

if ($idPaket === false || $idPaket === null || $idPaket < 1) {
    $errors[] = 'Paket internet harus dipilih.';
}

if ($idOdp === false || $idOdp === null || $idOdp < 1) {
    $errors[] = 'ODP harus dipilih.';
}

if (!empty($errors)) {
    redirect_form_result('invalid', $fallback);
}

// Validasi paket aktif di database
$stmtPaket = $koneksi->prepare(
    'SELECT id_paket, status_aktif FROM paket WHERE id_paket = ? LIMIT 1'
);
if (!$stmtPaket) {
    redirect_form_result('error', $fallback);
}
$stmtPaket->bind_param('i', $idPaket);
$stmtPaket->execute();
$resPaket = $stmtPaket->get_result()->fetch_assoc();
$stmtPaket->close();

if (!$resPaket || (int) $resPaket['status_aktif'] !== 1) {
    redirect_form_result('invalid', $fallback);
}

// Validasi ODP aktif di database
$stmtOdp = $koneksi->prepare(
    'SELECT id_odp, status_aktif FROM odp WHERE id_odp = ? LIMIT 1'
);
if (!$stmtOdp) {
    redirect_form_result('error', $fallback);
}
$stmtOdp->bind_param('i', $idOdp);
$stmtOdp->execute();
$resOdp = $stmtOdp->get_result()->fetch_assoc();
$stmtOdp->close();

if (!$resOdp || (int) $resOdp['status_aktif'] !== 1) {
    redirect_form_result('invalid', $fallback);
}

// Proses upload foto rumah
$fotoRumahNamaDb = null;
$fotoRumahPathFs = null;

$fotoUpload = $_FILES['foto_rumah'] ?? null;
$adaUpload  = $fotoUpload !== null
    && isset($fotoUpload['error'])
    && $fotoUpload['error'] !== UPLOAD_ERR_NO_FILE;

if ($adaUpload) {
    if ($fotoUpload['error'] !== UPLOAD_ERR_OK) {
        redirect_form_result('upload_error', $fallback);
    }

    $maxBytes = 2 * 1024 * 1024;
    if ($fotoUpload['size'] > $maxBytes) {
        redirect_form_result('upload_too_large', $fallback);
    }

    $ekstensiAsli      = strtolower(pathinfo((string) $fotoUpload['name'], PATHINFO_EXTENSION));
    $ekstensiDiizinkan = ['jpg', 'jpeg', 'png'];
    if (!in_array($ekstensiAsli, $ekstensiDiizinkan, true)) {
        redirect_form_result('upload_invalid_type', $fallback);
    }

    // Validasi MIME type dari konten file, bukan dari nama yang dikirim browser
    $tmpPath       = (string) $fotoUpload['tmp_name'];
    $mimeType      = mime_content_type($tmpPath);
    $mimeDiizinkan = ['image/jpeg', 'image/png'];
    if (!in_array($mimeType, $mimeDiizinkan, true)) {
        redirect_form_result('upload_invalid_type', $fallback);
    }

    // Pastikan ekstensi konsisten dengan MIME type
    $pemetaanMime = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png'  => ['png'],
    ];
    if (!in_array($ekstensiAsli, $pemetaanMime[$mimeType] ?? [], true)) {
        redirect_form_result('upload_invalid_type', $fallback);
    }

    if (!is_uploaded_file($tmpPath)) {
        redirect_form_result('upload_error', $fallback);
    }

    $uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
    if (!is_dir($uploadDir)) {
        redirect_form_result('upload_error', $fallback);
    }

    // Nama file acak agar tidak bentrok dan tidak menggunakan nama asli pengguna
    $namaAcak   = bin2hex(random_bytes(16)) . '.' . $ekstensiAsli;
    $pathTujuan = $uploadDir . $namaAcak;

    if (!move_uploaded_file($tmpPath, $pathTujuan)) {
        redirect_form_result('upload_error', $fallback);
    }

    $fotoRumahNamaDb = $namaAcak;
    $fotoRumahPathFs = $pathTujuan;
}

// Simpan data pelanggan
$tanggalDaftar = date('Y-m-d');
$statusAwal    = 'pengajuan';

try {
    form_transaction($koneksi, function () use (
        $koneksi,
        $idSales,
        $idPaket,
        $idOdp,
        $nama,
        $noHp,
        $alamat,
        $fotoRumahNamaDb,
        $tanggalDaftar,
        $statusAwal
    ): void {
        $stmt = $koneksi->prepare(
            'INSERT INTO pelanggan
             (id_sales, id_paket, id_odp, nama_lengkap, no_hp_wa, alamat, foto_rumah,
              status_pelanggan, tanggal_daftar)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan query simpan pelanggan.');
        }

        $stmt->bind_param(
            'iiissssss',
            $idSales,
            $idPaket,
            $idOdp,
            $nama,
            $noHp,
            $alamat,
            $fotoRumahNamaDb,
            $statusAwal,
            $tanggalDaftar
        );
        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Gagal menyimpan data pelanggan ke database.');
        }

        $idPelangganBaru = (int) $koneksi->insert_id;
        $stmt->close();

        // Catat aktivitas
        catat_log(
            $koneksi,
            $idSales,
            'daftar_pelanggan',
            'pelanggan',
            $idPelangganBaru,
            'Sales mendaftarkan pelanggan baru: ' . $nama
        );
    });

    redirect_form_result('sukses', $fallback);

} catch (Throwable $e) {
    // Hapus file yang sudah terupload jika transaksi database gagal
    if ($fotoRumahPathFs !== null && file_exists($fotoRumahPathFs)) {
        @unlink($fotoRumahPathFs);
    }

    redirect_form_result('error', $fallback);
}
