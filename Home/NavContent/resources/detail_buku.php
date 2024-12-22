<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-library";

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #0A3697;
            color: white;
            text-align: center;
            padding: 30px 0;
            font-size: 1.5em;
            font-weight: 600;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .book-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-image img {
            width: 300px;
            height: 450px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .book-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-info h2 {
            font-size: 28px;
            color: #444;
            margin-bottom: 10px;
        }

        .book-info p {
            margin: 8px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .book-info strong {
            color: #0A3697;
        }

        .back-btn {
            text-decoration: none;
            background-color: #0A3697;
            color: white;
            padding: 12px 25px;
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
            align-self: flex-start;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .back-btn:hover {
            background-color: #082C72;
            transform: translateY(-2px);
            transition: 0.3s;
        }

        .full-description {
            margin-top: 20px;
            display: none; /* Hidden by default */
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .toggle-description {
            cursor: pointer;
            color: #0A3697;
            font-weight: 600;
            margin-top: 10px;
            text-decoration: underline;
            background: none;
            border: none;
            font-size: 16px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .book-details {
                flex-direction: column;
                align-items: center;
            }

            .book-image img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>

<header>
    Detail Buku: <?= htmlspecialchars($row['Judul']); ?>
</header>

<div class="container">
    <div class="book-details">
        <div class="book-image">
            <img src="http://localhost/E-Library/home/img/<?= htmlspecialchars($row['Cover_Image']); ?>" alt="<?= htmlspecialchars($row['Judul']); ?>">
        </div>
        <div class="book-info">
            <h2><?= htmlspecialchars($row['Judul']); ?></h2>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($row['Penulis']); ?></p>
            <p><strong>Penerbit:</strong> <?= htmlspecialchars($row['Penerbit']); ?></p>
            <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($row['Tahun_Terbit']); ?></p>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($row['Nama_Kategori']); ?></p>
            <p><strong>Lokasi Rak:</strong> <?= htmlspecialchars($row['Lokasi_Rak']); ?></p>
            <p><strong>Stok:</strong> <?= htmlspecialchars($row['Stok']); ?> buku</p>
            <button class="toggle-description" onclick="toggleDescription()">Full Description</button>
            <div id="full-description" class="full-description">
                <?= nl2br(htmlspecialchars($row['Deskripsi'])); ?>
            </div>
            <a href="javascript:history.go(-1)" class="back-btn">Return to catalog</a>
        </div>
    </div>
</div>

<script>
    function toggleDescription() {
        const desc = document.getElementById('full-description');
        const toggleButton = document.querySelector('.toggle-description');

        // Check the current display property and toggle it
        if (desc.style.display === 'block') {
            desc.style.display = 'none'; // Hide description
            toggleButton.textContent = 'Full Description'; // Change button text
        } else {
            desc.style.display = 'block'; // Show description
            toggleButton.textContent = 'Hide Description'; // Change button text
        }
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
