<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-library";

$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ''; // Notifikasi pesan
$bookDetails = []; // Data buku untuk notifikasi dengan cover dan deskripsi

if (isset($_POST['borrow'])) {
    $bookId = htmlspecialchars($_POST['bookId']);
    $userId = htmlspecialchars($_POST['userId']);
    $borrowDate = htmlspecialchars($_POST['borrowDate']);
    $returnDate = htmlspecialchars($_POST['returnDate']);
    
    // Validasi apakah buku ada di database
    $sqlBook = "SELECT * FROM buku WHERE ID_Buku = ?";
    $stmtBook = $conn->prepare($sqlBook);
    $stmtBook->bind_param("i", $bookId);
    $stmtBook->execute();
    $resultBook = $stmtBook->get_result();

    if ($resultBook->num_rows > 0) {
        $bookDetails = $resultBook->fetch_assoc();

        // Insert data peminjaman ke database
        $insertSql = "INSERT INTO peminjaman (ID_Anggota, ID_Buku, Tanggal_Peminjaman, Tanggal_Harus_Pengembalian, Status_Peminjaman) 
                      VALUES (?, ?, ?, ?, 'Dipinjam')";
        $stmtInsert = $conn->prepare($insertSql);
        $stmtInsert->bind_param("iiss", $userId, $bookId, $borrowDate, $returnDate);
        
        if ($stmtInsert->execute()) {
            $message = "Book successfully borrowed!";
        } else {
            $message = "Failed to borrow the book. Please try again.";
        }
    } else {
        $message = "Book ID not found in the system.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book - E-Library</title>
    <link rel="stylesheet" href="NavContent/services/styles.css">
</head>
<body>
    <div class="content">
        <section class="book-borrowing">
            <h2>Borrow a Book</h2>
            <p>Please fill out the form below to borrow a book:</p>
            <form action="" method="post">
                <div class="form-group">
                    <label for="bookId">Book ID:</label>
                    <input type="text" id="bookId" name="bookId" required>
                </div>
                <div class="form-group">
                    <label for="userId">User ID:</label>
                    <input type="text" id="userId" name="userId" required>
                </div>
                <div class="form-group">
                    <label for="borrowDate">Borrow Date:</label>
                    <input type="date" id="borrowDate" name="borrowDate" required>
                </div>
                <div class="form-group">
                    <label for="returnDate">Return Date:</label>
                    <input type="date" id="returnDate" name="returnDate" required>
                </div>
                <button type="submit" name="borrow">Borrow Book</button>
            </form>

            <!-- Notifikasi Peminjaman -->
            <?php if (!empty($message)): ?>
                <div class="confirmation">
                    <?php if (!empty($bookDetails)): ?>
                        <img src="http://localhost/E-Library/E-Library-AKSA/Home/img/<?= htmlspecialchars($bookDetails['Cover_Image']); ?>" alt="Book Cover">
                        <div>
                            <p><strong><?= $message; ?></strong></p>
                            <p>Book: <strong><?= htmlspecialchars($bookDetails['Judul']); ?></strong></p>
                            <p>Author: <?= htmlspecialchars($bookDetails['Penulis']); ?></p>
                            <p>Description: <?= nl2br(htmlspecialchars($bookDetails['Deskripsi'])); ?></p>
                        </div>
                    <?php else: ?>
                        <p><?= $message; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
<?php
$conn->close();
?>
