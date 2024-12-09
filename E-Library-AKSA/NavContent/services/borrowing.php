<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Books</title>
    <link rel="stylesheet" href="../../style.css"> <!-- Sesuaikan jalur CSS -->
    <style>
        /* Add this block to your CSS file or in <style> tag */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="date"], input[type="time"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .confirmation {
            background-color: #dff0d8;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Borrow a Book</h1>
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
            <button type="submit" name="borrow">Borrow</button>
        </form>

        <?php
        // Proses Peminjaman Buku
        if (isset($_POST['borrow'])) {
            $bookId = $_POST['bookId'];
            $userId = $_POST['userId'];
            $borrowDate = $_POST['borrowDate'];
            $returnDate = $_POST['returnDate'];

            echo "<div class='confirmation'>
                    <p>Book with ID <strong>$bookId</strong> has been borrowed by User <strong>$userId</strong> from <strong>$borrowDate</strong> to <strong>$returnDate</strong>.</p>
                  </div>";
        }
        ?>
    </div>
</body>
</html>
