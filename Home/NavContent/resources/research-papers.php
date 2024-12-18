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

// Query untuk mengambil data makalah penelitian
$sql = "SELECT * FROM makalah_penelitian";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Makalah Penelitian</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Makalah Penelitian</h1>

    <div class="research-paper-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="research-paper-item">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penulis: <?php echo $row["penulis"]; ?></p>
            <p>Publikasi: <?php echo $row["publikasi"]; ?></p>
            <a href="<?php echo $row["link"]; ?>" class="btn" target="_blank">Buka Makalah</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>