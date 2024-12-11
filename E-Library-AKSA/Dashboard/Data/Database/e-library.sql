-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Host: 127.0.0.1

-- Hapus tabel jika sudah ada
DROP TABLE IF EXISTS `reading_room_bookings`;
DROP TABLE IF EXISTS `pengembalian`;
DROP TABLE IF EXISTS `peminjaman`;
DROP TABLE IF EXISTS `transaksi_ebook`;
DROP TABLE IF EXISTS `ebook`;
DROP TABLE IF EXISTS `buku`;
DROP TABLE IF EXISTS `anggota`;
DROP TABLE IF EXISTS `kategori`;
DROP TABLE IF EXISTS `petugas`;
DROP TABLE IF EXISTS `auth_tokens`;
DROP TABLE IF EXISTS `user_sessions`;

-- Tabel kategori
CREATE TABLE `kategori` (
  `ID_Kategori` INT AUTO_INCREMENT NOT NULL,
  `Nama_Kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`ID_Kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel anggota
CREATE TABLE `anggota` (
  `ID_Anggota` INT AUTO_INCREMENT NOT NULL,
  `Nama` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(255) NOT NULL UNIQUE,
  `Alamat` VARCHAR(255) NOT NULL,
  `No_Telepon` VARCHAR(15) NOT NULL,
  `Tipe` ENUM('Mahasiswa', 'Dosen', 'Pengunjung') NOT NULL,
  `Status` ENUM('Aktif', 'Nonaktif') NOT NULL,
  `Username` VARCHAR(100) NOT NULL UNIQUE,
  `Password` VARCHAR(255) NOT NULL,
  `Tanggal_Registrasi` DATE NOT NULL,
  PRIMARY KEY (`ID_Anggota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel petugas
CREATE TABLE `petugas` (
  `ID_Petugas` INT AUTO_INCREMENT NOT NULL,
  `Nama_Petugas` VARCHAR(100) NOT NULL,
  `Jabatan` VARCHAR(50) NOT NULL,
  `No_Telepon` VARCHAR(15) NOT NULL,
  `Status` ENUM('Aktif', 'Nonaktif') NOT NULL,
  `Username` VARCHAR(100) NOT NULL,
  `Password` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255),
  PRIMARY KEY (`ID_Petugas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel buku
CREATE TABLE `buku` (
  `ID_Buku` INT AUTO_INCREMENT NOT NULL,
  `ID_Kategori` INT NOT NULL,
  `Penulis` VARCHAR(255) NOT NULL,
  `Judul` VARCHAR(255) NOT NULL,
  `Penerbit` VARCHAR(100) NOT NULL,
  `Tahun_Terbit` YEAR NOT NULL,
  `Lokasi_Rak` VARCHAR(50),
  `Stok` INT NOT NULL DEFAULT 0,
  `ISBN` VARCHAR(20) NOT NULL,
  `Deskripsi` TEXT,
  `Cover_Image` VARCHAR(255),
  PRIMARY KEY (`ID_Buku`),
  KEY `FK_Kategori_Buku` (`ID_Kategori`),
  CONSTRAINT `FK_Kategori_Buku` FOREIGN KEY (`ID_Kategori`) REFERENCES `kategori` (`ID_Kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel ebook
CREATE TABLE `ebook` (
  `ID_Access` INT AUTO_INCREMENT NOT NULL,
  `ID_Buku` INT NOT NULL,
  `Tanggal_Akses` DATE NOT NULL,
  `Status` ENUM('Aktif', 'Nonaktif') NOT NULL,
  `Link_File` VARCHAR(255) NOT NULL,
  `Jumlah_Akses` INT DEFAULT 0,
  PRIMARY KEY (`ID_Access`),
  KEY `FK_Buku_Ebook` (`ID_Buku`),
  CONSTRAINT `FK_Buku_Ebook` FOREIGN KEY (`ID_Buku`) REFERENCES `buku` (`ID_Buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel transaksi ebook
CREATE TABLE `transaksi_ebook` (
  `ID_Transaksi` INT AUTO_INCREMENT NOT NULL,
  `ID_Anggota` INT NOT NULL,
  `ID_Buku` INT NOT NULL,
  `Tanggal_Akses` DATETIME NOT NULL,
  `Status_Akses` ENUM('Berhasil', 'Gagal') NOT NULL,
  `Durasi_Akses` INT DEFAULT 0,
  PRIMARY KEY (`ID_Transaksi`),
  KEY `FK_Anggota_TransaksiEbook` (`ID_Anggota`),
  KEY `FK_Buku_TransaksiEbook` (`ID_Buku`),
  CONSTRAINT `FK_Anggota_TransaksiEbook` FOREIGN KEY (`ID_Anggota`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Buku_TransaksiEbook` FOREIGN KEY (`ID_Buku`) REFERENCES `buku` (`ID_Buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel peminjaman
CREATE TABLE `peminjaman` (
  `ID_Peminjaman` INT AUTO_INCREMENT NOT NULL,
  `ID_Anggota` INT NOT NULL,
  `ID_Buku` INT NOT NULL,
  `ID_Petugas` INT NOT NULL,
  `Tanggal_Peminjaman` DATE NOT NULL,
  `Tanggal_Harus_Pengembalian` DATE NOT NULL,
  `Tanggal_Pengembalian` DATE DEFAULT NULL,
  `Status_Peminjaman` ENUM('Dipinjam', 'Dikembalikan') NOT NULL,
  `Denda` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`ID_Peminjaman`),
  KEY `FK_Anggota_Peminjaman` (`ID_Anggota`),
  KEY `FK_Buku_Peminjaman` (`ID_Buku`),
  KEY `FK_Petugas_Peminjaman` (`ID_Petugas`),
  CONSTRAINT `FK_Anggota_Peminjaman` FOREIGN KEY (`ID_Anggota`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Buku_Peminjaman` FOREIGN KEY (`ID_Buku`) REFERENCES `buku` (`ID_Buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Petugas_Peminjaman` FOREIGN KEY (`ID_Petugas`) REFERENCES `petugas` (`ID_Petugas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel pengembalian
CREATE TABLE `pengembalian` (
  `ID_Pengembalian` INT AUTO_INCREMENT NOT NULL,
  `ID_Peminjaman` INT NOT NULL,
  `Tanggal_Pengembalian` DATE NOT NULL,
  `Status_Pengembalian` ENUM('Tepat Waktu', 'Terlambat') NOT NULL,
  `Jumlah_Denda` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`ID_Pengembalian`),
  KEY `FK_Peminjaman_Pengembalian` (`ID_Peminjaman`),
  CONSTRAINT `FK_Peminjaman_Pengembalian` FOREIGN KEY (`ID_Peminjaman`) REFERENCES `peminjaman` (`ID_Peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel pemesanan ruangan membaca
CREATE TABLE `reading_room_bookings` (
  `ID_Booking` INT AUTO_INCREMENT NOT NULL,
  `ID_Room` VARCHAR(50) NOT NULL,
  `ID_Anggota` INT NOT NULL,
  `Booking_Date` DATE NOT NULL,
  `Start_Time` TIME NOT NULL,
  `End_Time` TIME NOT NULL,
  `Status` ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
  PRIMARY KEY (`ID_Booking`),
  KEY `FK_Anggota_Booking` (`ID_Anggota`),
  CONSTRAINT `FK_Anggota_Booking` FOREIGN KEY (`ID_Anggota`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk menyimpan token autentikasi
CREATE TABLE `auth_tokens` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_type` ENUM('anggota', 'petugas') NOT NULL,
  `user_id` INT NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL,
  `expiry` DATETIME NOT NULL,
  `is_valid` BOOLEAN DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_token` (`token_hash`, `user_type`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk menyimpan session
CREATE TABLE `user_sessions` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `user_type` ENUM('anggota', 'petugas') NOT NULL,
    `user_id` INT NOT NULL,
    `session_id` VARCHAR(255) NOT NULL,
    `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `session_id` (`session_id`),
    INDEX `idx_user` (`user_type`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;