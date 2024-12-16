<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book - E-Library</title>
    <link rel="stylesheet" href="style.css">
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
                <button type="submit" name="borrow" class="btn">Borrow Book</button>
            </form>
            <?php
            // Proses Peminjaman Buku
            if (isset($_POST['borrow'])) {
                $bookId = htmlspecialchars($_POST['bookId']);
                $userId = htmlspecialchars($_POST['userId']);
                $borrowDate = htmlspecialchars($_POST['borrowDate']);
                $returnDate = htmlspecialchars($_POST['returnDate']);

                // Tampilkan pesan konfirmasi
                echo "<div class='confirmation'>
                        <p>Book <strong>$bookId</strong> has been successfully borrowed by User <strong>$userId</strong> on <strong>$borrowDate</strong>. The return date is <strong>$returnDate</strong>.</p>
                      </div>";
            }
            ?>
        </section>
    </div>
</body>
</html>
