<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi Pelanggan - InsanNet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/sales.css" rel="stylesheet">
</head>
<body>
  <div class="card-sales">
    <div class="head">Sales: Registrasi Pelanggan</div>
    <div class="body">

      <div id="pesan-sukses" class="alert alert-success d-none">Pelanggan berhasil didaftarkan.</div>
      <div id="pesan-gagal" class="alert alert-danger d-none">Pendaftaran gagal. Periksa data lalu coba lagi.</div>

      <form method="POST" action="../../app/pelanggan/simpan.php" enctype="multipart/form-data">

        <label for="nama" class="lbl">Nama pelanggan</label>
        <input type="text" id="nama" name="nama" class="form-control" required>

        <label for="no_hp" class="lbl">Nomor HP</label>
        <input type="tel" id="no_hp" name="no_hp" class="form-control" required>

        <label for="alamat" class="lbl">Alamat / Sharelock</label>
        <textarea id="alamat" name="alamat" class="form-control" style="height:100px"
        placeholder="Tulis alamat, atau tempel tautan share lokasi dari Google Maps" required></textarea>
        
        <div class="d-flex gap-2 flex-wrap mb-2">
          <button type="button" id="btn-lokasi" class="btn-lokasi">Sharelock</button>
          <a id="lihat-peta" href="#" target="_blank" rel="noopener" class="lihat-peta d-none">Lihat di Google Maps</a>
        </div>

        <label for="id_paket" class="lbl">Paket</label>
        <select id="id_paket" name="id_paket" class="form-select mb-3" required>
          <option value="">Pilih paket</option>
          <option value="1">Paket 15 Mbps</option>
          <option value="2">Paket 25 Mbps</option>
          <option value="3">Paket 50 Mbps</option>
        </select>

        <label for="id_odp" class="lbl">ODP</label>
        <select id="id_odp" name="id_odp" class="form-select mb-3" required>
          <option value="">Pilih ODP</option>
          <option value="1">ODP Klanderan</option>
          <option value="2">ODP Darungan</option>
          <option value="3">ODP Tiru</option>
          <option value="4">ODP Bangkok</option>
          <option value="5">ODP Dawuhan</option>
        </select>

        <label class="upload-box">
          Unggah foto depan rumah
          <input type="file" name="foto_rumah" accept="image/*" hidden>
        </label>

        <button type="submit" class="btn-kirim">Kirim pengajuan</button>
      </form>
    </div>
  </div>

  <script>
    const p = new URLSearchParams(location.search);
    if (p.has('sukses')) document.getElementById('pesan-sukses').classList.remove('d-none');
    if (p.has('gagal')) document.getElementById('pesan-gagal').classList.remove('d-none');
    const alamat = document.getElementById('alamat');
    const linkPeta = document.getElementById('lihat-peta');
// Ambil lokasi dari GPS lalu isi tautan Google Maps
    document.getElementById('btn-lokasi').addEventListener('click', function () {
      if (!navigator.geolocation) {
        alert('Perangkat tidak mendukung lokasi.');
        return;
      }
      navigator.geolocation.getCurrentPosition(function (pos) {
        const url = 'https://www.google.com/maps?q=' + pos.coords.latitude + ',' + pos.coords.longitude;
        alamat.value = (alamat.value.trim() ? alamat.value.trim() + '\n' : '') + url;
        tampilkanLink();
      }, function () {
        alert('Lokasi tidak bisa diambil. Izinkan akses lokasi di browser, atau tempel tautan Google Maps manual.');
      });
    });

    function tampilkanLink() {
      const m = alamat.value.match(/https?:\/\/\S+/);
      if (m) {
        linkPeta.href = m[0];
        linkPeta.classList.remove('d-none');
      } else {
        linkPeta.classList.add('d-none');
      }
    }
    alamat.addEventListener('input', tampilkanLink);
  </script>
</body>
</html>