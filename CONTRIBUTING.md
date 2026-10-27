# Alur kerja Git

- `main`: selalu stabil, siap didemokan. Tidak ada push langsung.
- `develop`: integrasi mingguan.
- Branch fitur: `feat/<modul>-<nama>` (contoh `feat/fe-login`), perbaikan: `fix/<deskripsi>`.

1. Ambil satu issue, buat branch dari `develop`.
2. Commit kecil dengan pesan jelas (`feat: tambah form registrasi sales`).
3. Buka Pull Request ke `develop`; PM me-review semua PR.
4. Setelah merge, hapus branch. Setiap akhir minggu PM merge `develop` ke `main`.

Jangan commit `.env`, isi `uploads/`, atau data pelanggan asli.