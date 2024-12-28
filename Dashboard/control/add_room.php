<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addroom"])) {
    // Ambil dan bersihkan data input
    $nama_ruang = trim($_POST["Nama_Ruang"]);
    $kapasitas = filter_var($_POST["Kapasitas"], FILTER_VALIDATE_INT);

    // Validasi input
    if (empty($nama_ruang) || !$kapasitas || $kapasitas < 1) {
        echo "<script>
            alert('Error: Data tidak valid. Periksa input Anda.');
            window.location.href = '../ruangan.php';
        </script>";
        exit;
    }

    // Cek apakah nama ruangan sudah ada (karena UNIQUE constraint)
    $check_query = "SELECT COUNT(*) as count FROM reading_room WHERE Nama_Ruang = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $nama_ruang);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>
            alert('Error: Nama ruangan sudah ada. Gunakan nama lain.');
            window.location.href = 'ruangan.php';
        </script>";
        exit;
    }

    // Prepared Statement untuk insert data
    $stmt = $conn->prepare("INSERT INTO reading_room (Nama_Ruang, Kapasitas, Status) VALUES (?, ?, 'Tersedia')");
    $stmt->bind_param("si", $nama_ruang, $kapasitas);

    if ($stmt->execute()) {
        echo "<script>
            alert('Ruangan berhasil ditambahkan!');
            window.location.href = '../index.php?page=ruangan';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambahkan ruangan: " . $stmt->error . "');
            window.location.href = '../index.php?page=ruangan';
        </script>";
    }

    $stmt->close();
} else {
    // Jika bukan method POST, redirect ke halaman ruangan
    header("Location: ../index.php?page=ruangan");
}

$conn->close();
