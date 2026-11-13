CREATE TABLE pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    no_hp VARCHAR(20),
    peran ENUM('admin', 'sales', 'teknisi') NOT NULL,
    status_aktif TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE paket (
    id_paket INT AUTO_INCREMENT PRIMARY KEY,
    nama_paket VARCHAR(100) NOT NULL,
    kecepatan_mbps INT NOT NULL,
    harga_bulanan DECIMAL(12,2) NOT NULL,
    deskripsi TEXT,
    status_aktif TINYINT(1) DEFAULT 1,
    id_admin INT NOT NULL,

    CONSTRAINT fk_paket_admin
        FOREIGN KEY (id_admin)
        REFERENCES pengguna(id_pengguna)
) ENGINE=InnoDB;


CREATE TABLE odp (
    id_odp INT AUTO_INCREMENT PRIMARY KEY,
    id_penginput INT NOT NULL,
    nama_odp VARCHAR(100) NOT NULL UNIQUE,
    wilayah VARCHAR(50),
    alamat VARCHAR(255),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    kapasitas_port INT,
    jumlah_tarikan INT,
    keterangan TEXT,
    foto_odp VARCHAR(255),
    tanggal_input DATE,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_odp_penginput
        FOREIGN KEY (id_penginput)
        REFERENCES pengguna(id_pengguna)
) ENGINE=InnoDB;


CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    id_sales INT NOT NULL,
    id_paket INT NOT NULL,
    id_odp INT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(20),
    no_hp_wa VARCHAR(20),
    email VARCHAR(100),
    alamat TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    foto_rumah VARCHAR(255),
    foto_ktp VARCHAR(255),
    status_pelanggan ENUM(
        'pengajuan',
        'disetujui',
        'penjadwalan',
        'aktif',
        'isolir',
        'nonaktif'
    ) DEFAULT 'pengajuan',
    tanggal_daftar DATE,
    tanggal_aktif DATE,

    CONSTRAINT fk_pelanggan_sales
        FOREIGN KEY (id_sales)
        REFERENCES pengguna(id_pengguna),

    CONSTRAINT fk_pelanggan_paket
        FOREIGN KEY (id_paket)
        REFERENCES paket(id_paket),

    CONSTRAINT fk_pelanggan_odp
        FOREIGN KEY (id_odp)
        REFERENCES odp(id_odp)
) ENGINE=InnoDB;


CREATE TABLE tiket (
    id_tiket INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_petugas INT NULL,
    id_penjadwal INT NULL,
    jenis_tiket ENUM(
        'survei',
        'instalasi',
        'perbaikan'
    ) NOT NULL,
    keluhan TEXT,
    jadwal DATETIME,
    catatan_admin TEXT,
    status_tiket ENUM(
        'terjadwal',
        'process',
        'pending',
        'completed'
    ) DEFAULT 'terjadwal',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tiket_pelanggan
        FOREIGN KEY (id_pelanggan)
        REFERENCES pelanggan(id_pelanggan),

    CONSTRAINT fk_tiket_petugas
        FOREIGN KEY (id_petugas)
        REFERENCES pengguna(id_pengguna),

    CONSTRAINT fk_tiket_penjadwal
        FOREIGN KEY (id_penjadwal)
        REFERENCES pengguna(id_pengguna)
) ENGINE=InnoDB;

CREATE TABLE laporan_teknis (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    id_tiket INT NOT NULL UNIQUE,
    redaman_dbm DECIMAL(5,2),
    port_odp VARCHAR(10),
    sn_onu VARCHAR(50),
    foto_modem VARCHAR(255),
    foto_speedtest VARCHAR(255),
    catatan TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_laporan_tiket
        FOREIGN KEY (id_tiket)
        REFERENCES tiket(id_tiket)
) ENGINE=InnoDB;

CREATE TABLE tagihan (
    id_tagihan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    no_invoice VARCHAR(30) NOT NULL UNIQUE,
    periode CHAR(7) NOT NULL,
    jumlah_tagihan DECIMAL(12,2) NOT NULL,
    jatuh_tempo DATE NOT NULL,
    status_tagihan ENUM(
        'belum_bayar',
        'lunas',
        'terlambat'
    ) DEFAULT 'belum_bayar',
    tgl_kirim_invoice DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tagihan_pelanggan
        FOREIGN KEY (id_pelanggan)
        REFERENCES pelanggan(id_pelanggan)
) ENGINE=InnoDB;


CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_tagihan INT NOT NULL,
    metode ENUM(
        'manual',
        'payment_gateway'
    ) NOT NULL,
    jumlah_bayar DECIMAL(12,2) NOT NULL,
    tanggal_bayar DATETIME,
    referensi_gateway VARCHAR(100),
    bukti_bayar VARCHAR(255),
    status_konfirmasi ENUM(
        'menunggu',
        'terkonfirmasi',
        'ditolak'
    ) DEFAULT 'menunggu',

    CONSTRAINT fk_pembayaran_tagihan
        FOREIGN KEY (id_tagihan)
        REFERENCES tagihan(id_tagihan)
) ENGINE=InnoDB;

CREATE TABLE target_sales (
    id_target INT AUTO_INCREMENT PRIMARY KEY,
    id_sales INT NOT NULL,
    periode CHAR(7) NOT NULL,
    target_pendaftaran INT NOT NULL,
    capaian INT DEFAULT 0,

    CONSTRAINT fk_target_sales
        FOREIGN KEY (id_sales)
        REFERENCES pengguna(id_pengguna)
) ENGINE=InnoDB;

CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    aksi VARCHAR(100) NOT NULL,
    tabel_terkait VARCHAR(50),
    id_data INT,
    detail TEXT,
    ip_address VARCHAR(45),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_log_pengguna
        FOREIGN KEY (id_pengguna)
        REFERENCES pengguna(id_pengguna)
) ENGINE=InnoDB;

