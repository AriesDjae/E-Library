<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returning Books</title>
    <link rel="stylesheet" href="../../style.css"> <!-- Sesuaikan jalur CSS -->
</head>
<body>
    <div class="container">
        <h1>Return a Book</h1>
        <form action="" method="post">
            <label for="bookId">Book ID:</label>
            <input type="text" id="bookId" name="bookId" required>
            <br>
            <label for="userId">User ID:</label>
            <input type="text" id="userId" name="userId" required>
            <br>
            <label for="returnDate">Return Date:</label>
            <input type="date" id="returnDate" name="returnDate" required>
            <br>
            <button type="submit" name="return">Return</button>
        </form>

        <?php
        // Proses Pengembalian Buku
        if (isset($_POST['return'])) {
            $bookId = $_POST['bookId'];
            $userId = $_POST['userId'];
            $returnDate = $_POST['returnDate'];

            // Logika Pengembalian Buku
            echo "<p>Book with ID <strong>$bookId</strong> has been returned by User <strong>$userId</strong> on <strong>$returnDate</strong>.</p>";

            // Contoh pembaruan data di database (sesuaikan dengan kebutuhan Anda)
            // $connection = new mysqli('hostname', 'username', 'password', 'database');
            // if ($connection->connect_error) {
            //     die("Connection failed: " . $connection->connect_error);
            // }
            // $sql = "UPDATE borrowed_books SET status='returned', return_date='$returnDate' WHERE book_id='$bookId' AND user_id='$userId'";
            // if ($connection->query($sql) === TRUE) {
            //     echo "<p>Return record successfully updated!</p>";
            // } else {
            //     echo "<p>Error: " . $sql . "<br>" . $connection->error . "</p>";
            // }
            // $connection->close();
        }
        ?>
    </div>
</body>
</html>
