<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tugas Hari Ini - InsanNet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/sales.css" rel="stylesheet">
  <link href="../assets/css/teknisi.css" rel="stylesheet">
</head>
<body>
  <div class="card-sales">
    <div class="head">Teknisi: Tugas Hari Ini</div>
    <div class="body">

      <div class="tugas">
        <div><strong>Survei</strong><span class="nama">Nama pelanggan</span></div>
        <span class="badge-status badge-process">Process</span>
      </div>

      <div class="tugas">
        <div><strong>Instalasi baru</strong><span class="nama">Nama pelanggan</span></div>
        <span class="badge-status badge-pending">Pending</span>
      </div>

      <div class="tugas">
        <div><strong>Perbaikan gangguan</strong><span class="nama">Nama pelanggan</span></div>
        <span class="badge-status badge-completed">Completed</span>
      </div>

      <form method="POST" action="#" enctype="multipart/form-data" class="mt-3">

        <label for="redaman" class="lbl">Redaman kabel (dBm)</label>
        <input type="text" id="redaman" name="redaman" class="form-control">

        <label for="port" class="lbl">Nomor port ODP</label>
        <input type="text" id="port" name="port_odp" class="form-control">

        <label for="sn" class="lbl">Serial number ONU</label>
        <input type="text" id="sn" name="sn_onu" class="form-control">

        <label class="upload-box">
          Unggah foto modem dan speedtest
          <input type="file" name="foto_modem" accept="image/*" hidden>
        </label>

      </form>
    </div>
  </div>
</body>
</html>