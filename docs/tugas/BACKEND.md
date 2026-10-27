# Tugas Backend: Rakhmat Dwi Saputo

Penilaian dari GitHub: tiap tugas = 1 issue, 1 branch `feat/be-...`, minimal 1 Pull Request ke `develop`, dengan commit kecil yang rutin.

## Minggu 1 (26–30 Okt): Persiapan
- [ ] Pasang XAMPP (PHP 8+, MySQL), jalankan proyek di lokal
- [ ] Buat `.env` dan uji koneksi `app/config/database.php`
- [ ] Kirim PR pertama

## Minggu 2 (2–6 Nov): Database
- [ ] Finalisasi `database/schema.sql` (10 tabel, FK, ENUM) sesuai ERD
- [ ] Gambar ERD ke `docs/desain/erd.png`
- [ ] `database/seed.sql`: 1 admin, 1 sales, 1 teknisi (password hash), paket dan ODP contoh

## Minggu 3 (9–13 Nov): Auth dan data master
- [ ] Login/logout dengan `password_hash`/`password_verify` dan session
- [ ] `require_role()` dipasang di setiap halaman terproteksi
- [ ] CRUD pengguna, paket, ODP (nonaktifkan, jangan hapus)
- [ ] Helper `catat_log()` ke tabel `log_aktivitas`

## Minggu 4 (16–20 Nov): Pelanggan dan sales
- [ ] CRUD pelanggan dengan status Pengajuan → Disetujui → Penjadwalan → Aktif
- [ ] Unggah foto aman (cek tipe dan ukuran, nama acak) ke `uploads/`
- [ ] Cek ODP terdekat dari koordinat (rumus Haversine) dan sisa port
- [ ] Target dan capaian sales per bulan

## Minggu 5 (23–27 Nov): Persetujuan dan tiket
- [ ] Proses persetujuan/penolakan pengajuan oleh admin
- [ ] CRUD tiket (survei, instalasi, perbaikan) dan penugasan petugas
- [ ] Update status tiket (process, pending, completed); pending kembali ke admin
- [ ] Simpan `laporan_teknis` (redaman, port, SN, foto) dan catat ke log

## Minggu 6 (30 Nov–4 Des): Penagihan
- [ ] Skrip pembuat tagihan bulanan otomatis (hanya pelanggan Aktif, tanpa duplikat per periode)
- [ ] Pembayaran manual: konfirmasi admin, ubah status tagihan menjadi lunas
- [ ] Penandaan menunggak setelah jatuh tempo dan rekap tunggakan
- [ ] Query laporan keuangan dan dashboard
- [ ] Kirim invoice via WhatsApp/email (tautan atau API sesuai kemampuan)

## Minggu 7 (7–11 Des): Pengujian dan keamanan
- [ ] Prepared statement di semua query
- [ ] Validasi input server dan proteksi CSRF pada form
- [ ] Uji akses lintas peran (buka URL langsung harus ditolak)
- [ ] Perbaiki bug dari black box

## Minggu 8 (14–18 Des): Final
- [ ] Deployment demo (hosting/lokal) dan catat langkahnya di README
- [ ] Skrip backup database
- [ ] Dokumentasi database dan alur di `docs/`