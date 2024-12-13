<?php
// Koneksi ke database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "e_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data kategori
$sql = "SELECT * FROM kategori";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori Buku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Kategori Buku</h1>

    <div class="category-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="category-item">
            <h3><?php echo $row["nama"]; ?></h3>
            <p>Jumlah buku: <?php echo $row["jumlah_buku"]; ?></p>
            <a href="katalog.php?kategori=<?php echo $row["id"]; ?>" class="btn">Lihat Buku</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>