INSERT INTO pengguna
(nama_lengkap, username, password_hash, email, no_hp, peran, status_aktif)
VALUES
(
    'Administrator',
    'admin',
    'Admin123',
    'admin@insannet.com',
    '081234567890',
    'admin',
    1
),
(
    'Budi Santoso',
    'sales01',
    'Sales123',
    'sales@insannet.com',
    '081234567891',
    'sales',
    1
),
(
    'Andi Pratama',
    'teknisi01',
    'Teknisi123',
    'teknisi@insannet.com',
    '081234567892',
    'teknisi',
    1
);

INSERT INTO paket
(nama_paket, kecepatan_mbps, harga_bulanan, deskripsi, status_aktif, id_admin)
VALUES
(
    'Basic',
    10,
    150000,
    'Paket internet dengan kecepatan 10 Mbps',
    1,
    1
),
(
    'Standard',
    20,
    200000,
    'Paket internet dengan kecepatan 20 Mbps',
    1,
    1
),
(
    'Premium',
    50,
    350000,
    'Paket internet dengan kecepatan 50 Mbps',
    1,
    1
);

INSERT INTO odp
(
    id_penginput,
    nama_odp,
    wilayah,
    alamat,
    latitude,
    longitude,
    kapasitas_port,
    jumlah_tarikan,
    keterangan,
    tanggal_input
)
VALUES
(
    1,
    'ODP-KDR-001',
    'Kediri Kota',
    'Jl. Diponegoro, Kediri',
    -7.8160000,
    112.0110000,
    8,
    5,
    'ODP wilayah Kediri Kota',
    '2026-11-06'
),
(
    1,
    'ODP-KDR-002',
    'Ngronggo',
    'Jl. Perintis Kemerdekaan, Kediri',
    -7.8200000,
    112.0200000,
    16,
    10,
    'ODP wilayah Ngronggo',
    '2026-11-06'
),
(
    1,
    'ODP-KDR-003',
    'Mojoroto',
    'Jl. Penanggungan, Kediri',
    -7.8030000,
    111.9900000,
    8,
    4,
    'ODP wilayah Mojoroto',
    '2026-11-06'
);
