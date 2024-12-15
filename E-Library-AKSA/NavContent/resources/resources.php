<?php

// Koneksi ke database
include "db_config.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data sumber daya
$sql = "SELECT * FROM sumber_daya";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sumber Daya</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sumber Daya</h1>

    <div class="resource-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="resource-item">
            <h3><?php echo $row["judul"]; ?></h3>
            <p><?php echo $row["deskripsi"]; ?></p>
            <a href="<?php echo $row["link"]; ?>" class="btn" target="_blank">Akses Sumber Daya</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>