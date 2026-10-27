-- Jalankan file ini di phpMyAdmin (tab Import) atau lewat terminal:
-- mysql -u root -p < database.sql

CREATE DATABASE IF NOT EXISTS crud_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    harga DECIMAL(12,0) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun default: username = admin | password = admin123
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$VH5sebI.ZT.rn/Jc4icTteFc.kxQ82nd/Ie/uBwMv3aZmBFAkPJ1C', 'Administrator');

INSERT INTO produk (nama_produk, harga, stok) VALUES
('Kopi Arabika 250g', 65000, 40),
('Teh Hijau Premium', 35000, 75),
('Gula Aren Cair 500ml', 28000, 120);
