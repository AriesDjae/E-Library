<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-library";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menambah stok buku
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addbuku"])) {
    $ID_Buku = trim($_POST["ID_Buku"]);
    $ID_Kategori = trim($_POST["ID_Kategori"]);
    $Penulis = trim($_POST["Penulis"]);
    $Judul = trim($_POST["Judul"]);
    $Penerbit = trim($_POST["Penerbit"]);
    $Tahun_Terbit = filter_var($_POST["Tahun_Terbit"], FILTER_VALIDATE_INT);
    $Lokasi_Rak = trim($_POST["Lokasi_Rak"]);
    $Stok = filter_var($_POST["Stok"], FILTER_VALIDATE_INT);
    $Deskripsi = trim($_POST["Deskripsi"]);
    $Cover_Image = trim($_POST["Cover_Image"]); // Ubah dengan mekanisme upload file jika perlu

    // Validasi input
    if (!$Tahun_Terbit || !$Stok || empty($ID_Buku) || empty($ID_Kategori) || empty($Judul)) {
        die("Error: Data tidak valid. Periksa input Anda.");
    }

    // Validasi ID_Kategori
    $query = "SELECT COUNT(*) AS count FROM kategori WHERE ID_Kategori = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ID_Kategori);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        die("Error: ID_Kategori tidak ditemukan di tabel kategori.");
    }

    // Prepared Statement untuk memasukkan data ke tabel buku
    $stmt = $conn->prepare("INSERT INTO buku (ID_Buku, ID_Kategori, Penulis, Judul, Penerbit, Tahun_Terbit, Lokasi_Rak, Stok, Deskripsi, Cover_Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisiss", $ID_Buku, $ID_Kategori, $Penulis, $Judul, $Penerbit, $Tahun_Terbit, $Lokasi_Rak, $Stok, $Deskripsi, $Cover_Image);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "Gagal menambahkan data: " . $stmt->error;
        header('Location: index.php');
    }
}
?>
