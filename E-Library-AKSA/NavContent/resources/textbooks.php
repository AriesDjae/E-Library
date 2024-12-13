<?php
// Koneksi ke database
$conn = new mysqli("localhost", "username", "password", "database_name");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data buku teks
$sql = "SELECT * FROM buku_teks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buku Teks</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Buku Teks</h1>

    <div class="textbook-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="textbook-item">
            <img src="<?php echo $row["sampul"]; ?>" alt="<?php echo $row["judul"]; ?>">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penulis: <?php echo $row["penulis"]; ?></p>
            <p>Mata Kuliah: <?php echo $row["mata_kuliah"]; ?></p>
            <a href="detail.php?id=<?php echo $row["id"]; ?>" class="btn">Lihat Detail</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>