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

// Mendapatkan ID Buku dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_buku = $_GET['id'];

    // Query untuk mengambil detail buku berdasarkan ID
    $sql = "SELECT buku.ID_Buku, buku.Judul, buku.Penulis, buku.Penerbit, buku.Tahun_Terbit, buku.Lokasi_Rak, buku.Stok, buku.Deskripsi, buku.Cover_Image, kategori.Nama_Kategori
            FROM buku
            JOIN kategori ON buku.ID_Kategori = kategori.ID_Kategori
            WHERE buku.ID_Buku = $id_buku";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Menampilkan data buku
        $row = $result->fetch_assoc();
    } else {
        echo "Buku tidak ditemukan.";
        exit;
    }
} else {
    echo "ID Buku tidak valid.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?= htmlspecialchars($row['Judul']); ?></title>
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

        .book h3 {
            font-size: 16px;
            margin-top: 10px;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .book-details {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .book-image img {
            width: 200px;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }

        .book-info {
            max-width: 600px;
        }

        .book-info h2 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .book-info p {
            margin: 10px 0;
            color: #555;
            font-size: 16px;
        }

        .back-btn {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #1a73e8;
            color: white;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #155d8c;
        }
    </style>
</head>
<body>

<header>
    <h1>Detail Buku: <?= htmlspecialchars($row['Judul']); ?></h1>
</header>

<div class="container">
    <div class="book-details">
        <div class="book-image">
            <img src="http://localhost/E-Library/E-Library-AKSA/src/img/<?= htmlspecialchars($row['Cover_Image']); ?>" alt="<?= htmlspecialchars($row['Judul']); ?>">
        </div>
        <div class="book-info">
            <h2><?= htmlspecialchars($row['Judul']); ?></h2>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($row['Penulis']); ?></p>
            <p><strong>Penerbit:</strong> <?= htmlspecialchars($row['Penerbit']); ?></p>
            <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($row['Tahun_Terbit']); ?></p>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($row['Nama_Kategori']); ?></p>
            <p><strong>Lokasi Rak:</strong> <?= htmlspecialchars($row['Lokasi_Rak']); ?></p>
            <p><strong>Stok:</strong> <?= htmlspecialchars($row['Stok']); ?> buku</p>
            <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($row['Deskripsi'])); ?></p>
        </div>
    </div>

    <a href="catalog.php" class="back-btn">Kembali ke Katalog</a>
</div>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
