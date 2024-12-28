<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edituser"])) {
    // Ambil dan bersihkan data input
    $id_anggota = trim($_POST["ID_Anggota"]);
    $nama = trim($_POST["Nama"]);
    $email = trim($_POST["Email"]);
    $alamat = trim($_POST["Alamat"]);
    $no_telepon = trim($_POST["No_Telepon"]);
    $tipe = trim($_POST["Tipe"]);
    $status = trim($_POST["Status"]);
    $username = trim($_POST["Username"]);

    // Validasi input
    if (empty($id_anggota) || empty($nama) || empty($email) || empty($username)) {
        echo "<script>
            alert('Error: Data tidak valid. Periksa input Anda.');
            window.location.href = '../index.php?page=user';
        </script>";
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Error: Format email tidak valid.');
            window.location.href = '../index.php?page=user';
        </script>";
        exit;
    }

    // Cek duplikasi email dan username (kecuali untuk user yang sedang diedit)
    $check_query = "SELECT COUNT(*) as count FROM anggota WHERE (Email = ? OR Username = ?) AND ID_Anggota != ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ssi", $email, $username, $id_anggota);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>
            alert('Error: Email atau Username sudah digunakan.');
            window.location.href = '../index.php?page=user';
        </script>";
        exit;
    }

    // Update password hanya jika field password diisi
    if (!empty($_POST["Password"])) {
        $password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE anggota SET Nama = ?, Email = ?, Alamat = ?, No_Telepon = ?, 
                              Tipe = ?, Status = ?, Username = ?, Password = ? WHERE ID_Anggota = ?");
        $stmt->bind_param(
            "ssssssssi",
            $nama,
            $email,
            $alamat,
            $no_telepon,
            $tipe,
            $status,
            $username,
            $password,
            $id_anggota
        );
    } else {
        // Update tanpa mengubah password
        $stmt = $conn->prepare("UPDATE anggota SET Nama = ?, Email = ?, Alamat = ?, No_Telepon = ?, 
                              Tipe = ?, Status = ?, Username = ? WHERE ID_Anggota = ?");
        $stmt->bind_param(
            "sssssssi",
            $nama,
            $email,
            $alamat,
            $no_telepon,
            $tipe,
            $status,
            $username,
            $id_anggota
        );
    }

    if ($stmt->execute()) {
        echo "<script>
            alert('Data user berhasil diperbarui!');
            window.location.href = '../index.php?page=user';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui data user: " . $stmt->error . "');
            window.location.href = '../index.php?page=user';
        </script>";
    }

    $stmt->close();
} else {
    // Jika bukan method POST, redirect ke halaman user
    header("Location: ../index.php?page=user");
}

$conn->close();
