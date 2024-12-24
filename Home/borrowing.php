<?php
// Configuration for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request for borrowing books
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'borrow') {
    header('Content-Type: application/json');
    $response = [];
    
    $bookId = htmlspecialchars($_POST['bookId']);
    $username = htmlspecialchars($_POST['username']);
    $borrowDate = htmlspecialchars($_POST['borrowDate']);
    $returnDate = htmlspecialchars($_POST['returnDate']);

    // Validate book data
    $sqlBook = "SELECT * FROM buku WHERE ID_Buku = ?";
    $stmtBook = $conn->prepare($sqlBook);
    $stmtBook->bind_param("i", $bookId);
    $stmtBook->execute();
    $resultBook = $stmtBook->get_result();

    if ($resultBook->num_rows > 0) {
        $bookDetails = $resultBook->fetch_assoc();
        if ($bookDetails['Stok'] > 0) {
            // Insert borrowing data
            $insertSql = "INSERT INTO peminjaman (ID_Buku, Username, Tanggal_Peminjaman, Tanggal_Harus_Pengembalian, Status_Peminjaman) 
                          VALUES (?, ?, ?, ?, 'Dipinjam')";
            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bind_param("isss", $bookId, $username, $borrowDate, $returnDate);

            if ($stmtInsert->execute()) {
                // Decrease book stock
                $updateStockSql = "UPDATE buku SET Stok = Stok - 1 WHERE ID_Buku = ?";
                $stmtUpdateStock = $conn->prepare($updateStockSql);
                $stmtUpdateStock->bind_param("i", $bookId);
                $stmtUpdateStock->execute();

                $response['success'] = true;
                $response['message'] = "Book successfully borrowed!";
            } else {
                $response['success'] = false;
                $response['message'] = "Failed to borrow the book. Please try again.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "The book is out of stock.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Book ID not found.";
    }

    echo json_encode($response);
    $conn->close();
    exit;
}

// Fetch list of books
$books = [];
$sqlBooks = "SELECT ID_Buku, Judul FROM buku WHERE Stok > 0";
$resultBooks = $conn->query($sqlBooks);
if ($resultBooks) {
    while ($row = $resultBooks->fetch_assoc()) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 100px;
            height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            min-height: 700px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .container {
            min-height: auto !important;
            width: auto !important;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .notification {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            display: none;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .card {
                padding: 20px;
            }

            h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; background-color: #3498db; color: white; padding: 15px; text-align: center;">
        <h1>Library Navbar</h1>
    </div>

    <div class="card">
        <h2>Borrow a Book</h2>
        <form id="borrowForm">
            <div class="form-group">
                <label for="bookId">Select Book:</label>
                <select id="bookId" name="bookId" required>
                    <option value="" disabled selected>Select a book</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?= htmlspecialchars($book['ID_Buku']); ?>">
                            <?= htmlspecialchars($book['Judul']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="borrowDate">Borrow Date:</label>
                <input type="date" id="borrowDate" name="borrowDate" required>
            </div>
            <div class="form-group">
                <label for="returnDate">Return Date:</label>
                <input type="date" id="returnDate" name="returnDate" required>
            </div>
            <button type="submit">Borrow Book</button>
        </form>

        <div id="notification" class="notification"></div>
    </div>

    <script>
        document.getElementById('borrowForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'borrow');

            try {
                const response = await fetch('borrowing.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                const notification = document.getElementById('notification');
                if (result.success) {
                    notification.textContent = result.message;
                    notification.classList.remove('error');
                    notification.classList.add('success');
                } else {
                    notification.textContent = result.message;
                    notification.classList.remove('success');
                    notification.classList.add('error');
                }

                notification.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                const notification = document.getElementById('notification');
                notification.textContent = 'An error occurred. Please try again later.';
                notification.classList.remove('success');
                notification.classList.add('error');
                notification.style.display = 'block';
            }
        });
    </script>
</body>
</html>
