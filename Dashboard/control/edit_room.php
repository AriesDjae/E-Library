<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editroom"])) {
    // Ambil dan bersihkan data input
    $id_room = trim($_POST["ID_Room"]);
    $nama_ruang = trim($_POST["Nama_Ruang"]);
    $kapasitas = filter_var($_POST["Kapasitas"], FILTER_VALIDATE_INT);
    $status = trim($_POST["Status"]);

    // Validasi input
    if (empty($id_room) || empty($nama_ruang) || !$kapasitas || $kapasitas < 1) {
        echo "<script>
            alert('Error: Data tidak valid. Periksa input Anda.');
            window.location.href = '../index.php?page=ruangan';
        </script>";
        exit;
    }

    // Cek apakah nama ruangan sudah ada (kecuali untuk ruangan yang sedang diedit)
    $check_query = "SELECT COUNT(*) as count FROM reading_room WHERE Nama_Ruang = ? AND ID_Room != ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("si", $nama_ruang, $id_room);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>
            alert('Error: Nama ruangan sudah ada. Gunakan nama lain.');
            window.location.href = '../index.php?page=ruangan';
        </script>";
        exit;
    }

    // Prepared Statement untuk update data
    $stmt = $conn->prepare("UPDATE reading_room SET Nama_Ruang = ?, Kapasitas = ?, Status = ? WHERE ID_Room = ?");
    $stmt->bind_param("sisi", $nama_ruang, $kapasitas, $status, $id_room);

    if ($stmt->execute()) {
        echo "<script>
            alert('Ruangan berhasil diperbarui!');
            window.location.href = '../index.php?page=ruangan';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui ruangan: " . $stmt->error . "');
            window.location.href = '../index.php?page=ruangan';
        </script>";
    }

    $stmt->close();
} else {
    // Jika bukan method POST, redirect ke halaman ruangan
    header("Location: ../index.php?page=ruangan");
}

$conn->close();
?>