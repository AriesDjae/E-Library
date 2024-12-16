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

// Query untuk mengambil data buku terbaru
$sql = "SELECT * FROM buku ORDER BY tanggal_rilis DESC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buku Terbaru</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Buku Terbaru</h1>

    <div class="book-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="book-item">
            <img src="<?php echo $row["sampul"]; ?>" alt="<?php echo $row["judul"]; ?>">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penulis: <?php echo $row["penulis"]; ?></p>
            <p>Tanggal Rilis: <?php echo $row["tanggal_rilis"]; ?></p>
            <a href="detail.php?id=<?php echo $row["id"]; ?>" class="btn">Lihat Detail</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>