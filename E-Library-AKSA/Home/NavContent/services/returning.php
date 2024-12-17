<?php
// Koneksi ke database
$host = 'localhost';  // Ganti dengan host database Anda
$username = 'root';  // Ganti dengan username MySQL Anda
$password = '';  // Ganti dengan password MySQL Anda
$dbname = 'e-library';  // Nama database yang sudah ada

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses Pengembalian Buku
if (isset($_POST['return'])) {
    $bookId = $_POST['bookId'];
    $userId = $_POST['userId'];
    $returnDate = $_POST['returnDate'];

    // Memeriksa status peminjaman buku
    $sql_check = "SELECT p.ID_Buku, p.ID_Anggota, p.Tanggal_Harus_Pengembalian, b.Judul, b.Penulis
                  FROM peminjaman p
                  JOIN buku b ON p.ID_Buku = b.ID_Buku
                  WHERE p.ID_Buku = ? AND p.ID_Anggota = ? AND p.Status_Peminjaman = 'Dipinjam'";

    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param('ii', $bookId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mendapatkan data buku yang dipinjam
        $row = $result->fetch_assoc();
        $judulBuku = $row['Judul'];
        $penulisBuku = $row['Penulis'];

        // Pembaruan status peminjaman dan pengembalian buku
        $sql_update = "UPDATE peminjaman SET Status_Peminjaman = 'Dikembalikan', Tanggal_Pengembalian = ? WHERE ID_Buku = ? AND ID_Anggota = ?";
        $stmt_update = $conn->prepare($sql_update);

        // Menyiapkan parameter dan mengeksekusi query update
        $stmt_update->bind_param('sii', $returnDate, $bookId, $userId);
        if ($stmt_update->execute()) {
            echo "<p>Buku dengan ID <strong>$bookId</strong> ('<em>$judulBuku</em>' oleh $penulisBuku) telah dikembalikan oleh Anggota <strong>$userId</strong> pada <strong>$returnDate</strong>.</p>";
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt_update->error . "</p>";
        }
    } else {
        echo "<p>Data peminjaman buku dengan ID: <strong>$bookId</strong> oleh Anggota ID: <strong>$userId</strong> tidak ditemukan atau buku telah dikembalikan.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku</title>
    <link rel="stylesheet" href="NavContent/services/styles.css"> 
</head>
<body>
    <div class="container">
        <h1>Pengembalian Buku</h1>
        <form action="return_book.php" method="post">
            <label for="bookId">ID Buku:</label>
            <input type="text" id="bookId" name="bookId" required>
            <br>
            <label for="userId">ID Anggota:</label>
            <input type="text" id="userId" name="userId" required>
            <br>
            <label for="returnDate">Tanggal Pengembalian:</label>
            <input type="date" id="returnDate" name="returnDate" required>
            <br>
            <button type="submit" name="return">Kembalikan Buku</button>
        </form>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
