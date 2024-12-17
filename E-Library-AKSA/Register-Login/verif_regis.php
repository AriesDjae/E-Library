<?php
// Mengimpor file koneksi database
require 'db.php';
require 'Signup.php';

// Menangkap data dari form
$nama_lengkap = $_POST['namalengkap'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$repeat_password = $_POST['repeat-password'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$tipe = $_POST['tipe'] ?? '';

// Validasi input
if ($password !== $repeat_password) {
    die("Password dan Konfirmasi Password tidak cocok!");
}

if (in_array($tipe, ['Mahasiswa', 'Dosen', 'Pengunjung', 'Petugas'])) {
        echo "Identitas Valid";
} else {
        echo "Identitas tidak valid";
}
    

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Menyimpan data ke database
$sql = "INSERT INTO users (nama_lengkap, username, email, password, telepon, alamat)
        VALUES ('$nama_lengkap', '$username', '$email', '$hashed_password', '$telepon', '$alamat')";

if ($conn->query($sql) === TRUE) {
    echo "Pendaftaran berhasil! <a href='Login.php'>Login sekarang</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Menutup koneksi
$conn->close();
?>
