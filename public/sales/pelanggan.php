<?php
require_once __DIR__ . '/../../app/includes/auth.php';
require_role('sales');

$namaUser = htmlspecialchars($_SESSION['user']['nama_lengkap'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pelanggan Saya - InsanNet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/sales.css" rel="stylesheet">
</head>
<body>
  <div class="card-sales wrap-lebar">
    <div class="head">Sales: Pelanggan Saya</div>
    <div class="body">

      <nav class="menu-sales">
        <a href="dashboard.php">Dashboard</a>
        <a href="registrasi.php">Registrasi</a>
        <a href="pelanggan.php" class="active">Pelanggan Saya</a>
      </nav>

      <div class="tabel-box">
        <table class="tabel-pelanggan">
          <thead>
            <tr>
              <th>Nama</th>
              <th>No HP</th>
              <th>Paket</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Nama pelanggan</td>
              <td>08xxxxxxxxxx</td>
              <td>Paket</td>
              <td><span class="badge-s s-pengajuan">Pengajuan</span></td>
            </tr>
            <tr>
              <td>Nama pelanggan</td>
              <td>08xxxxxxxxxx</td>
              <td>Paket</td>
              <td><span class="badge-s s-disetujui">Disetujui</span></td>
            </tr>
            <tr>
              <td>Nama pelanggan</td>
              <td>08xxxxxxxxxx</td>
              <td>Paket</td>
              <td><span class="badge-s s-penjadwalan">Penjadwalan</span></td>
            </tr>
            <tr>
              <td>Nama pelanggan</td>
              <td>08xxxxxxxxxx</td>
              <td>Paket</td>
              <td><span class="badge-s s-aktif">Aktif</span></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</body>
</html>