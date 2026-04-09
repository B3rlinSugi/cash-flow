-- =============================================
-- CASH FLOW CLASS - DATABASE v2.0
-- Improved: bcrypt password, foreign keys, indexes
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nama     VARCHAR(150) NOT NULL,
    username VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- bcrypt hash
    foto     VARCHAR(180) NOT NULL DEFAULT 'admin.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- password = "password123" (bcrypt)
INSERT INTO admin (nama, username, password, foto) VALUES
('Admin Website', 'admin', '$2y$10$3zfxB9E16qMEC/CENEsiIeTi9/8x8p8vUti2CxnABl8U/LWoPIq16', 'admin.png');

-- Tabel Anggota
CREATE TABLE IF NOT EXISTS anggota (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nama      VARCHAR(120) NOT NULL,
    alamat    VARCHAR(200) NOT NULL,
    umur      TINYINT UNSIGNED NOT NULL,
    level_kas ENUM('Reguler','Silver','Gold','Platinum') NOT NULL DEFAULT 'Reguler',
    status    ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nama (nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Kas (Pemasukan)
CREATE TABLE IF NOT EXISTS kas (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    anggota_id INT NOT NULL,
    jumlah     DECIMAL(15,2) NOT NULL,
    keterangan VARCHAR(255),
    tanggal    DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE RESTRICT,
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Pengeluaran
CREATE TABLE IF NOT EXISTS pengeluaran (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(120) NOT NULL,
    jumlah     DECIMAL(15,2) NOT NULL,
    keterangan VARCHAR(255),
    tanggal    DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Kas Ditunda
CREATE TABLE IF NOT EXISTS kas_ditunda (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    anggota_id INT NOT NULL,
    jumlah     DECIMAL(15,2) NOT NULL,
    keterangan VARCHAR(255),
    tanggal    DATE NOT NULL,
    status     ENUM('pending','selesai') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE RESTRICT,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- Data dummy anggota
INSERT INTO anggota (nama, alamat, umur, level_kas) VALUES
('Budi Santoso',   'Jl. Merdeka No. 1, Jakarta',  22, 'Gold'),
('Siti Rahayu',    'Jl. Sudirman No. 5, Bandung',  21, 'Silver'),
('Andi Wijaya',    'Jl. Diponegoro No. 7, Jogja',  23, 'Reguler'),
('Dewi Anggraeni', 'Jl. Ahmad Yani No. 12, Bekasi', 20, 'Platinum'),
('Rizky Ramadhan', 'Jl. Gatot Subroto No. 3, Depok',24, 'Silver');

-- Data dummy kas
INSERT INTO kas (anggota_id, jumlah, keterangan, tanggal) VALUES
(1, 150000, 'Kas bulan Januari', '2025-01-05'),
(2, 100000, 'Kas bulan Januari', '2025-01-06'),
(3,  75000, 'Kas bulan Januari', '2025-01-07'),
(4, 200000, 'Kas bulan Februari', '2025-02-03'),
(5, 100000, 'Kas bulan Februari', '2025-02-04'),
(1, 150000, 'Kas bulan Maret',    '2025-03-05'),
(2, 100000, 'Kas bulan Maret',    '2025-03-06');

-- Data dummy pengeluaran
INSERT INTO pengeluaran (nama, jumlah, keterangan, tanggal) VALUES
('ATK & Alat Tulis',    50000,  'Pembelian buku & pena',       '2025-01-10'),
('Konsumsi Rapat',      120000, 'Snack rapat bulanan',         '2025-01-15'),
('Biaya Fotokopi',       30000, 'Fotokopi materi',             '2025-02-05'),
('Konsumsi Acara',      200000, 'Konsumsi acara gathering',    '2025-02-20'),
('Pembelian Banner',    150000, 'Banner kegiatan organisasi',  '2025-03-01');
