-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `e-library`;

USE `e-library`;

-- Tabel kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `ID_Kategori` INT AUTO_INCREMENT NOT NULL,
  `Nama_Kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`ID_Kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel anggota
CREATE TABLE IF NOT EXISTS `anggota` (
  `ID_Anggota` INT AUTO_INCREMENT NOT NULL,
  `Nama` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(255) NOT NULL UNIQUE,
  `Alamat` VARCHAR(255) NOT NULL,
  `No_Telepon` VARCHAR(15) NOT NULL,
  `Tipe` ENUM('Mahasiswa', 'Dosen', 'Pengunjung') NOT NULL,
  `Status` ENUM('Aktif', 'Nonaktif') NOT NULL,
  `Username` VARCHAR(100) NOT NULL UNIQUE,
  `Password` VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Anggota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel petugas
CREATE TABLE IF NOT EXISTS `petugas` (
  `ID_Petugas` INT AUTO_INCREMENT NOT NULL,
  `Nama` VARCHAR(100) NOT NULL,
  `No_Telepon` VARCHAR(15) NOT NULL,
  `Status` ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
  `Username` VARCHAR(100) NOT NULL UNIQUE,
  `Password` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Petugas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel buku
CREATE TABLE IF NOT EXISTS `buku` (
  `ID_Buku` INT AUTO_INCREMENT NOT NULL,
  `ID_Kategori` INT NOT NULL,
  `Penulis` VARCHAR(255) NOT NULL,
  `Judul` VARCHAR(255) NOT NULL,
  `Penerbit` VARCHAR(100) NOT NULL,
  `Tahun_Terbit` YEAR NOT NULL,
  `Lokasi_Rak` VARCHAR(50),
  `Stok` INT NOT NULL DEFAULT 0,
  `Deskripsi` TEXT,
  `Cover_Image` VARCHAR(255),
  PRIMARY KEY (`ID_Buku`),
  KEY `FK_Kategori_Buku` (`ID_Kategori`),
  CONSTRAINT `FK_Kategori_Buku` FOREIGN KEY (`ID_Kategori`) REFERENCES `kategori` (`ID_Kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel peminjaman
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `ID_Peminjaman` INT AUTO_INCREMENT NOT NULL,
  `Username` VARCHAR(100) NOT NULL,
  `ID_Buku` INT NOT NULL,
  `ID_Petugas` INT,
  `Tanggal_Peminjaman` DATE NOT NULL,
  `Tanggal_Harus_Pengembalian` DATE NOT NULL,
  `Tanggal_Pengembalian` DATE DEFAULT NULL,
  `Status_Peminjaman` ENUM('Dipinjam', 'Dikembalikan') NOT NULL,
  `Denda` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`ID_Peminjaman`),
  KEY `FK_Buku_Peminjaman` (`ID_Buku`),
  KEY `FK_Petugas_Peminjaman` (`ID_Petugas`),
  KEY `FK_Username_Peminjaman` (`Username`),
  CONSTRAINT `FK_Buku_Peminjaman` FOREIGN KEY (`ID_Buku`) REFERENCES `buku` (`ID_Buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Petugas_Peminjaman` FOREIGN KEY (`ID_Petugas`) REFERENCES `petugas` (`ID_Petugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Username_Peminjaman` FOREIGN KEY (`Username`) REFERENCES `anggota` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel pengembalian
CREATE TABLE IF NOT EXISTS `pengembalian` (
  `ID_Pengembalian` INT AUTO_INCREMENT NOT NULL,
  `ID_Peminjaman` INT NOT NULL,
  `Tanggal_Pengembalian` DATE NOT NULL,
  `Status_Pengembalian` ENUM('Tepat Waktu', 'Terlambat') NOT NULL,
  `Jumlah_Denda` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`ID_Pengembalian`),
  KEY `FK_Peminjaman_Pengembalian` (`ID_Peminjaman`),
  CONSTRAINT `FK_Peminjaman_Pengembalian` FOREIGN KEY (`ID_Peminjaman`) REFERENCES `peminjaman` (`ID_Peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel ruang baca
CREATE TABLE IF NOT EXISTS `reading_room` (
  `ID_Room` INT AUTO_INCREMENT NOT NULL,
  `Nama_Ruang` VARCHAR(100) NOT NULL UNIQUE,
  `Kapasitas` INT NOT NULL DEFAULT 10,
  `Status` ENUM('Tersedia', 'Dipesan') NOT NULL DEFAULT 'Tersedia',
  PRIMARY KEY (`ID_Room`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel pemesanan ruang baca
CREATE TABLE IF NOT EXISTS `reading_room_booking` (
  `ID_Booking` INT AUTO_INCREMENT NOT NULL,
  `ID_Room` INT NOT NULL,
  `Username` VARCHAR(100) NOT NULL,
  `Tanggal_Booking` DATE NOT NULL,
  `Waktu_Mulai` TIME NOT NULL,
  `Waktu_Selesai` TIME NOT NULL,
  `Status_Booking` ENUM('Dipesan', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Dipesan',
  PRIMARY KEY (`ID_Booking`),
  KEY `FK_Room_Booking` (`ID_Room`),
  KEY `FK_User_Booking` (`Username`),
  CONSTRAINT `FK_Room_Booking` FOREIGN KEY (`ID_Room`) REFERENCES `reading_room` (`ID_Room`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_User_Booking` FOREIGN KEY (`Username`) REFERENCES `anggota` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Trigger untuk mengisi default CURRENT_DATE jika Tanggal_Booking tidak disediakan
DELIMITER $$
CREATE TRIGGER before_reading_room_booking_insert
BEFORE INSERT ON `reading_room_booking`
FOR EACH ROW
BEGIN
  IF NEW.`Tanggal_Booking` IS NULL THEN
    SET NEW.`Tanggal_Booking` = CURRENT_DATE;
  END IF;
END$$
DELIMITER ;

-- Tabel user_sessions
CREATE TABLE IF NOT EXISTS `user_sessions` (
    `session_id` VARCHAR(255) PRIMARY KEY,
    `user_type` ENUM('Anggota', 'Petugas') NOT NULL,
    `user_id` INT NOT NULL,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `session_data` MEDIUMTEXT,
    `last_activity` DATETIME NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel contact_messages
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
