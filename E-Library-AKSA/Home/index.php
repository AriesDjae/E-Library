<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library Kampus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <h1>E-Library</h1>
            </div>
            <nav class="navContent">
        <ul class="main-menu">
            <li><a href="Home/index.php">Beranda</a></li>
            <li class="dropdown">
                <a href="resources.php" class="dropbtn">Koleksi</a>
                <div class="dropdown-content">
                    <a href="catalog.php">Katalog Buku</a>
                    <a href="new-arrivals.php">Buku Terbaru</a>
                    <a href="popular-books.php">Buku Terpopuler</a>
                    <a href="recommended.php">Rekomendasi</a>
                    <a href="categories.php">Kategori</a>
                    <div class="sub-dropdown">
                        <a href="journals.php">Jurnal</a>
                        <a href="research-papers.php">Karya Ilmiah</a>
                        <a href="theses.php">Skripsi/Tesis</a>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="services.php" class="dropbtn">Layanan</a>
                <div class="dropdown-content">
                    <a href="borrowing.php">Peminjaman & Pengembalian</a>
                    <a href="reading-rooms.php">Ruang Baca</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="information.php" class="dropbtn">Informasi</a>
                <div class="dropdown-content">
                    <a href="about.php">Tentang Kami</a>
                    <a href="contact.php">Kontak</a>
                    <a href="help.php">Bantuan</a>
                </div>
            </li>
            <li><a href="account.php">Akun Saya</a></li>
        </ul>
    </nav>
        </div>

        <div class="search-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
            <input type="text" name="search" id="search" placeholder="Cari buku...">
        </div>

        <div class="content">
            <section class="berita">
                <h2>Berita Terbaru</h2>
            </section>
            <section class="rekobuku">
                <h2>Rekomendasi Buku</h2>
            </section>
            <section class="buku">
                <h2>Koleksi Buku</h2>
            </section>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>