<?php
// Koneksi ke database
$conn = new mysqli("localhost", "username", "password", "database_name");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data tesis
$sql = "SELECT * FROM tesis";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tesis</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Tesis</h1>

    <div class="thesis-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="thesis-item">
            <h3><?php echo $row["judul"]; ?></h3>
            <p>Penulis: <?php echo $row["penulis"]; ?></p>
            <p>Program Studi: <?php echo $row["program_studi"]; ?></p>
            <a href="<?php echo $row["link"]; ?>" class="btn" target="_blank">Baca Tesis</a>
        </div>
        <?php } ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>