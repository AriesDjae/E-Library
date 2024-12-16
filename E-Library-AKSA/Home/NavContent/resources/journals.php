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

// Query untuk mengambil data jurnal
$sql = "SELECT * FROM jurnal";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jurnal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Jurnal</h1>

    <div class="journal-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="journal-item">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penerbit: <?php echo $row["penerbit"]; ?></p>
            <p>Tanggal Publikasi: <?php echo $row["tanggal_publikasi"]; ?></p>
            <a href="<?php echo $row["link"]; ?>" class="btn" target="_blank">Buka Jurnal</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>