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

// Query untuk mengambil data buku
$sql = "SELECT * FROM buku";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Katalog Buku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Katalog Buku</h1>

    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Cari buku...">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="book-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="book-item">
            <img src="<?php echo $row["sampul"]; ?>" alt="<?php echo $row["judul"]; ?>">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penulis: <?php echo $row["penulis"]; ?></p>
            <p>Penerbit: <?php echo $row["penerbit"]; ?></p>
            <a href="detail.php?id=<?php echo $row["id"]; ?>" class="btn">Lihat Detail</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>