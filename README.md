# InsanNet Management System

Aplikasi web manajemen operasional layanan internet untuk **CV Insan Agung**: pelanggan, paket, ODP, pendaftaran oleh sales, penjadwalan teknisi, penagihan bulanan, dan laporan keuangan.

Proyek mata kuliah **Software Development** — Teknik Informatika, Universitas Nusantara PGRI Kediri (2026/2027).

## Tim

| Nama | NPM | Peran |
| --- | --- | --- |
| Rahmat Satriawan | 2413020212 | Project Manager |
| Guntur Pangmukas | 2413020097 | Frontend |
| Rakhmat Dwi Saputo | 2413020093 | Backend |

## Teknologi

HTML, CSS, Bootstrap, JavaScript · PHP · MySQL

## Peran pengguna

- **Admin**: akun pengguna, pelanggan, paket, ODP, jadwal pekerjaan, persetujuan, tagihan, pembayaran, laporan.
- **Sales**: registrasi pelanggan, cek ODP, status pengajuan, target, rekap penagihan.
- **Teknisi**: daftar tugas, status pekerjaan, data teknis (redaman, port ODP, SN ONU), foto bukti.

Hak akses diperiksa **di sisi server** pada setiap halaman dan proses, bukan hanya dengan menyembunyikan menu.

## Struktur repo

```
app/            logika PHP (config, includes, admin, sales, teknisi)
public/         halaman publik dan aset (css, js, img)
database/       schema.sql
uploads/        foto rumah, bukti kerja (tidak di-commit)
docs/           proposal dan dokumentasi
```

## Menjalankan lokal

1. Pasang XAMPP (PHP 8+ dan MySQL).
2. Salin repo ke `htdocs/insannet`.
3. Buat database dan impor skema:
   ```bash
   mysql -u root -e "CREATE DATABASE insannet CHARACTER SET utf8mb4"
   mysql -u root insannet < database/schema.sql
   ```
4. Salin `.env.example` menjadi `.env`, lalu isi kredensial database.
5. Buka `http://localhost/insannet/public`.

## Jadwal proyek (8 minggu, estimasi)

| Mg | Tanggal | Fokus | PJ |
| --- | --- | --- | --- |
| 1 | 26–30 Okt 2026 | Finalisasi proposal, repo GitHub, kick-off | PM |
| 2 | 2–6 Nov | Desain database dan UI | FE + BE |
| 3 | 9–13 Nov | Login, RBAC, data master (pengguna, paket, ODP) | FE + BE |
| 4 | 16–20 Nov | Pelanggan, registrasi sales, cek ODP | FE + BE |
| 5 | 23–27 Nov | Persetujuan, jadwal, tugas teknisi | FE + BE |
| 6 | 30 Nov–4 Des | Penagihan, pembayaran, laporan, dashboard | FE + BE |
| 7 | 7–11 Des | Integrasi, black box, UAT/SUS | Semua |
| 8 | 14–18 Des | Perbaikan, dokumentasi, deployment, presentasi | Semua |

Detail per anggota: [`docs/JADWAL.md`](docs/JADWAL.md), tugas [Frontend](docs/tugas/FRONTEND.md), tugas [Backend](docs/tugas/BACKEND.md). Setiap tugas dicatat sebagai issue dan dikerjakan lewat Pull Request.

## Alur kerja Git

Lihat [CONTRIBUTING.md](CONTRIBUTING.md).
