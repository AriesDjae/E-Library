<?php
// Mengimpor file koneksi database
require 'db.php';

// Menangkap data dari form
$nama_lengkap = $_POST['namalengkap'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$repeat_password = $_POST['repeat-password'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$tipe = $_POST['tipe'] ?? ''; // Tipe user (anggota atau petugas)

// Validasi input
if ($password !== $repeat_password) {
    die("Password dan konfirmasi password tidak cocok!");
}

if (!in_array($tipe, ['Mahasiswa', 'Dosen', 'Pengunjung', 'Petugas'])) {
    die("Tipe pengguna tidak valid!");
}

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Menyimpan data ke database berdasarkan tipe
if ($tipe === 'Petugas') {
    $sql = "INSERT INTO petugas (Nama_Petugas, Username, Email, Password, No_Telepon, Status) 
            VALUES (?, ?, ?, ?, ?, 'Aktif')";
} else {
    $sql = "INSERT INTO anggota (Nama, Username, Email, Password, No_Telepon, Alamat, Tipe, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Aktif')";
}

$stmt = $conn->prepare($sql);

if ($tipe === 'Petugas') {
    $stmt->bind_param("sssss", $nama_lengkap, $username, $email, $hashed_password, $telepon);
} else {
    $stmt->bind_param("sssssss", $nama_lengkap, $username, $email, $hashed_password, $telepon, $alamat, $tipe);
}

if ($stmt->execute()) {
    header("Location: Login.php");
exit();
} else {
    echo "Error: " . $stmt->error;
}

// Menutup koneksi
$conn->close();
?>
