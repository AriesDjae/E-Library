<?php  
// Koneksi ke database
$servername = "localhost";  // Ganti dengan host Anda
$username = "root";         // Ganti dengan username MySQL Anda
$password = "";             // Ganti dengan password MySQL Anda
$dbname = "e-library";      // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan halaman saat ini untuk pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 8;  // Jumlah buku per halaman
$offset = ($page - 1) * $items_per_page;

// Query untuk mengambil data buku dengan pagination
$sql = "SELECT ID_Buku, Judul, Cover_Image FROM buku LIMIT $offset, $items_per_page";
$result = $conn->query($sql);

// Query untuk mendapatkan total jumlah buku
$total_books_result = $conn->query("SELECT COUNT(*) AS total FROM buku");
$total_books = $total_books_result->fetch_assoc()['total'];
$total_pages = ceil($total_books / $items_per_page);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog Buku E-Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1a73e8;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        .catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px;
        }

        .book {
            width: 220px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }

        .book:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .book img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            object-fit: cover;
        }

        .book a {
            text-decoration: none;
            color: inherit; /* Warna teks tetap sama */
        }

        .book h3 {
            font-size: 16px;
            margin-top: 10px;
            color: #333;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #1a73e8;
        }

        .pagination a.active {
            background-color: #1a73e8;
            color: white;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .search-box {
            text-align: center;
            margin: 20px 0;
        }

        .search-box input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<header>
    <h1>Catalog Buku E-Library</h1>
</header>

<div class="search-box">
    <input type="text" id="searchInput" placeholder="Cari Buku..." onkeyup="searchBooks()">
</div>

<div class="catalog" id="bookCatalog">
    <?php
    // Mengecek apakah ada hasil dari query
    if ($result->num_rows > 0) {
        // Menampilkan data setiap buku
        while($row = $result->fetch_assoc()) {
            echo '<div class="book">';
            echo '<a href="NavContent/resources/detail_buku.php?id=' . $row['ID_Buku'] . '">';
            echo '<img src="img/' . $row['Cover_Image'] . '" alt="' . $row['Judul'] . '">';
            echo '<h3>' . $row['Judul'] . '</h3>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo "<p>No books available.</p>";
    }
    ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <?php if ($i != 1) : // Sembunyikan tombol halaman 1 ?>
            <a href="?page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<script>
    // Fungsi untuk mencari buku secara langsung tanpa reload
    function searchBooks() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const books = document.querySelectorAll(".book");
        books.forEach(book => {
            const title = book.querySelector("h3").innerText.toLowerCase();
            if (title.includes(input)) {
                book.style.display = "block";
            } else {
                book.style.display = "none";
            }
        });
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
