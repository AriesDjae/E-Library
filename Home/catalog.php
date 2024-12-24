<?php 
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangkap parameter kategori
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] === '1';

// Query untuk kategori
$categories_result = $conn->query("SELECT ID_Kategori, Nama_Kategori FROM kategori");
if (!$categories_result) {
    die("Query kategori error: " . $conn->error);
}

// Query untuk buku
if ($category === 'all') {
    $books_query = "SELECT ID_Buku, Judul, Cover_Image FROM buku";
} else {
    $books_query = "SELECT ID_Buku, Judul, Cover_Image FROM buku WHERE ID_Kategori = ?";
}
$stmt = $conn->prepare($books_query);
if ($category !== 'all') {
    $stmt->bind_param("i", $category);
}
$stmt->execute();
$books_result = $stmt->get_result();

// Jika permintaan AJAX, kirim data JSON
if ($is_ajax) {
    header('Content-Type: application/json');
    $books = [];
    while ($book = $books_result->fetch_assoc()) {
        $cover_image = !empty($book['Cover_Image']) && file_exists("img/" . $book['Cover_Image']) 
            ? "img/" . $book['Cover_Image'] 
            : "img/default_cover.jpg";
        $books[] = [
            'ID_Buku' => $book['ID_Buku'],
            'Judul' => $book['Judul'],
            'Cover_Image' => $cover_image
        ];
    }
    echo json_encode($books);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog Buku E-Library</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            padding: 20px;
            justify-content: flex-start;
            padding-bottom: 100px; /* Menambahkan padding bawah agar halaman lebih turun */
            margin-top: 80px; /* Menambahkan margin-top untuk menghindari navbar yang menutupi */
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            width: 100%;
            margin-bottom: 40px; /* Menambahkan jarak lebih besar di bawah container */
        }

        .sidebar {
            width: 230px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
            margin-right: 20px;
            max-height: 80vh; /* Membatasi tinggi sidebar agar tidak terlalu panjang */
            overflow-y: auto; /* Menambahkan scroll jika konten terlalu panjang */
        }

        .sidebar h3 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            padding: 8px;
            display: block;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #e1e1e1;
            color: #1a73e8;
        }

        .catalog {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px;
            width: 100%;
            margin-top: 30px; /* Menambahkan jarak lebih besar di atas katalog */
        }

        .book {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 400px; /* Sesuaikan ukuran agar judul dan gambar lebih terlihat */
        }

        .book:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .book img {
            width: 100%;
            height: 300px; /* Sesuaikan dengan ukuran gambar */
            object-fit: cover; /* Menjamin gambar mengisi elemen tanpa terpotong */
            border-bottom: 1px solid #eee;
        }

        .book h3 {
            font-size: 16px; /* Menyesuaikan ukuran font agar judul lebih terlihat */
            color: #333;
            margin: 10px;
            padding: 0 10px;
            text-align: center;
            flex-grow: 1; /* Agar judul dapat mengambil ruang yang ada */
            word-wrap: break-word; /* Agar teks panjang dibungkus dengan benar */
            text-overflow: ellipsis;
            overflow: hidden;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .sidebar {
                width: 100%;
                margin-bottom: 15px;
            }

            .catalog {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h3>Kategori</h3>
        <ul>
            <li><a data-category="all" class="category-link">Semua Buku</a></li>
            <?php while ($category_row = $categories_result->fetch_assoc()): ?>
                <li><a data-category="<?php echo $category_row['ID_Kategori']; ?>" class="category-link">
                    <?php echo $category_row['Nama_Kategori']; ?>
                </a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <div class="catalog" id="catalog">
        <?php if ($books_result->num_rows > 0): ?>
            <?php while ($book = $books_result->fetch_assoc()): ?>
                <div class="book">
                    <a href="NavContent/resources/detail_buku.php?id=<?php echo $book['ID_Buku']; ?>">
                        <img src="<?php echo !empty($book['Cover_Image']) && file_exists("img/" . $book['Cover_Image']) 
                            ? "img/" . $book['Cover_Image'] 
                            : "img/default_cover.jpg"; ?>" alt="<?php echo htmlspecialchars($book['Judul']); ?>">
                        <h3><?php echo htmlspecialchars($book['Judul']); ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada buku yang tersedia.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const categoryLinks = document.querySelectorAll(".category-link");
        const catalogContainer = document.getElementById("catalog");

        categoryLinks.forEach(link => {
            link.addEventListener("click", async (event) => {
                event.preventDefault();
                const category = event.target.getAttribute("data-category");
                try {
                    const response = await fetch(`catalog.php?category=${category}&ajax=1`);
                    const books = await response.json();

                    catalogContainer.innerHTML = books.length
                        ? books.map(book => ` 
                            <div class="book">
                                <a href="detail_buku.php?id=${book.ID_Buku}">
                                    <img src="${book.Cover_Image}" alt="${book.Judul}">
                                    <h3>${book.Judul}</h3>
                                </a>
                            </div>
                        `).join('')
                        : '<p>Tidak ada buku yang tersedia.</p>';
                } catch (error) {
                    console.error("Error fetching books:", error);
                }
            });
        });
    });
</script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
