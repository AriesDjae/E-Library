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
  PRIMARY KEY (`ID_Anggota`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel petugas
CREATE TABLE IF NOT EXISTS `petugas` (
  `ID_Petugas` INT AUTO_INCREMENT NOT NULL,
  `Nama_Petugas` VARCHAR(100) NOT NULL,
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
  `ID_Anggota` INT NOT NULL,
  `ID_Buku` INT NOT NULL,
  `ID_Petugas` INT,
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
  `ID_Anggota` INT NOT NULL,
  `Tanggal_Booking` DATE NOT NULL DEFAULT CURRENT_DATE,
  `Waktu_Mulai` TIME NOT NULL,
  `Waktu_Selesai` TIME NOT NULL,
  `Status_Booking` ENUM('Dipesan', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Dipesan',
  PRIMARY KEY (`ID_Booking`),
  KEY `FK_Room_Booking` (`ID_Room`),
  KEY `FK_User_Booking` (`ID_Anggota`),
  CONSTRAINT `FK_Room_Booking` FOREIGN KEY (`ID_Room`) REFERENCES `reading_room` (`ID_Room`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_User_Booking` FOREIGN KEY (`ID_Anggota`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_sessions` (
    `session_id` VARCHAR(255) PRIMARY KEY,
    `user_type` ENUM('Anggota', 'Petugas') NOT NULL,  -- Menambahkan tipe pengguna yang hanya bisa 'Anggota' atau 'Petugas'
    `user_id` INT NOT NULL,  -- ID pengguna yang bisa merujuk ke anggota atau petugas
    `ip_address` VARCHAR(45),  -- Menyimpan alamat IP
    `user_agent` TEXT,  -- Menyimpan informasi user-agent
    `session_data` MEDIUMTEXT,  -- Menyimpan data sesi dalam bentuk teks
    `last_activity` DATETIME NOT NULL,  -- Menyimpan waktu aktivitas terakhir
    FOREIGN KEY (`user_id`) REFERENCES `anggota` (`ID_Anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `petugas` (`ID_Petugas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Tambahkan ruangan secara otomatis
INSERT IGNORE INTO `reading_room` (`Nama_Ruang`, `Kapasitas`, `Status`)
VALUES
('Ruang Baca 1', 10, 'Tersedia'),
('Ruang Baca 2', 10, 'Tersedia'),
('Ruang Baca 3', 10, 'Tersedia'),
('Ruang Baca 4', 10, 'Tersedia'),
('Ruang Baca 5', 10, 'Tersedia'),
('Ruang Baca 6', 10, 'Tersedia'),
('Ruang Baca 7', 10, 'Tersedia'),
('Ruang Baca 8', 10, 'Tersedia'),
('Ruang Baca 9', 10, 'Tersedia'),
('Ruang Baca 10', 10, 'Tersedia');

-- Cek apakah kategori dengan ID_Kategori = 1 sudah ada
SELECT COUNT(*) AS existing_category FROM kategori WHERE ID_Kategori = 1;

-- Jika hasilnya 0, maka kategori belum ada, dan kita bisa menambahkannya
-- Menambahkan kategori "Fiksi" jika belum ada
INSERT INTO kategori (ID_Kategori, Nama_Kategori)
SELECT 1, 'Fiksi'
WHERE NOT EXISTS (SELECT 1 FROM kategori WHERE ID_Kategori = 1);

-- Menambahkan data buku Bulan Tere Liye ke tabel buku jika belum ada
INSERT INTO buku 
(ID_Kategori, Penulis, Judul, Penerbit, Tahun_Terbit, Lokasi_Rak, Stok, Deskripsi, Cover_Image)
SELECT 1, 'Tere Liye', 'Bulan', 'PENERBIT SABAK GRIP', 2022, 'Rak A1', 10, 
'Petualangan Raib, Seli, dan Ali berlanjut. Beberapa bulan setelah peristiwa klan bulan, Miss Selena akhirnya muncul di sekolah. Ia membawa kabar menggembirakan untuk anak-anak yang berjiwa petualang seperti Raib, Seli, dan Ali. Miss Selena bersama dengan Av akan mengajak mereka untuk mengunjungi klan matahari selama dua minggu. Av berencana akan bertemu dengan ketua konsil klan matahari, yang menguasai klan matahari sepenuhnya untuk mencari sekutu dalam menghadapi Tamus yang diperkirakan akan bebas dan juga membebaskan raja tanpa mahkota. Sesampainya mereka di Klan matahari, mereka disambut oleh festival bunga matahari...',
'bulan.jpg'
WHERE NOT EXISTS (SELECT 1 FROM buku WHERE Judul = 'Bulan');