# InsanNet Management System

Aplikasi web manajemen operasional layanan internet untuk **CV Insan Agung**: data pelanggan, paket, ODP, pendaftaran oleh sales, penjadwalan teknisi, penagihan bulanan, dan laporan keuangan.

Proyek mata kuliah **Software Development** — Teknik Informatika, Universitas Nusantara PGRI Kediri (2026/2027).

## Tim

| Nama | NPM | Peran |
| --- | --- | --- |
| Rahmat Satriawan | 2413020212 | Project Manager |
| Guntur Pangmukas | 2413020097 | Frontend |
| Rakhmat Dwi Saputo | 2413020093 | Backend |

## Teknologi

- HTML, CSS, Bootstrap, JavaScript
- PHP 8+
- MySQL
- Laragon (lingkungan pengembangan lokal)

## Peran pengguna aplikasi

- **Admin**: akun pengguna, pelanggan, paket, ODP, jadwal pekerjaan, persetujuan, tagihan, pembayaran, laporan.
- **Sales**: registrasi pelanggan, cek ODP, status pengajuan, target, rekap penagihan.
- **Teknisi**: daftar tugas, status pekerjaan, data teknis (redaman, port ODP, SN ONU), foto bukti.

Hak akses diperiksa **di sisi server** pada setiap halaman dan proses.

## Struktur repo

```
docs/           jadwal dan tugas tim
  tugas/        FRONTEND.md dan BACKEND.md
app/            kode backend (Rakhmat)
database/       skema database (Rakhmat)
public/         tampilan web (Guntur)
```

Folder `app`, `database`, dan `public` ditambahkan oleh anggota tim lewat Pull Request.

## Menjalankan di komputer lokal (Laragon)

1. Pasang [Laragon](https://laragon.org) dan Git.
2. Clone repo ke folder `www` milik Laragon:
   ```bash
   cd C:\laragon\www
   git clone https://github.com/Rahmatsatriawan-coding/InsanNet-Management-System-.git insannet
   cd insannet
   git checkout develop
   ```
3. Buka Laragon, lalu klik **Start All** (Apache dan MySQL menyala).
4. Pastikan PHP versi 8 atau lebih baru (menu Laragon → PHP).
5. Buat database `insannet` lewat tombol **Database** di Laragon, lalu impor skemanya:
   ```bash
   mysql -u root -e "CREATE DATABASE insannet CHARACTER SET utf8mb4"
   mysql -u root insannet < database/schema.sql
   ```
6. Salin `.env.example` menjadi `.env`. Isi bawaan Laragon:
   ```
   DB_HOST=localhost
   DB_NAME=insannet
   DB_USER=root
   DB_PASS=
   ```
   File `.env` jangan di-commit.
7. Buka aplikasi di `http://insannet.test` atau `http://localhost/insannet/public/`.

## Jadwal proyek (8 minggu, estimasi)

| Mg | Tanggal | Fokus | PJ |
| --- | --- | --- | --- |
| 1 | 26–30 Okt 2026 | Finalisasi proposal, repo GitHub, kick-off | PM |
| 2 | 2–6 Nov | Desain database dan UI | FE + BE |
| 3 | 9–13 Nov | Login, hak akses, data master | FE + BE |
| 4 | 16–20 Nov | Pelanggan, registrasi sales, cek ODP | FE + BE |
| 5 | 23–27 Nov | Persetujuan, jadwal, tugas teknisi | FE + BE |
| 6 | 30 Nov–4 Des | Penagihan, pembayaran, laporan, dashboard | FE + BE |
| 7 | 7–11 Des | Integrasi, black box, UAT/SUS | Semua |
| 8 | 14–18 Des | Perbaikan, dokumentasi, deployment, presentasi | Semua |

Detail: [Jadwal mingguan](docs/JADWAL.md) · [Tugas Frontend](docs/tugas/FRONTEND.md) · [Tugas Backend](docs/tugas/BACKEND.md)

## Alur kerja

Setiap tugas dicatat sebagai **Issue** dan dikerjakan lewat branch sendiri, lalu dikirim sebagai **Pull Request** ke `develop`. Aturan lengkap ada di [CONTRIBUTING.md](CONTRIBUTING.md).

- `main`: stabil, siap didemokan. Tidak ada push langsung.
- `develop`: integrasi mingguan.
- Branch tugas: `feat/fe-...` untuk frontend, `feat/be-...` untuk backend.

Jangan commit `.env`, isi folder `uploads/`, atau data pelanggan asli.