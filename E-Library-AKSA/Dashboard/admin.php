<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - E-Library</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <i class='bx bx-book-reader'></i>
            <span>E-Library</span>
        </div>
        <ul class="menu">
            <li class="active">
                <a href="admin.php">
                    <i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/catalog.php">
                    <i class='bx bxs-book'></i>
                    <span>Katalog</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/journals.php">
                    <i class='bx bxs-book-bookmark'></i>
                    <span>Jurnal</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/services/borrowing.php">
                    <i class='bx bxs-bookmark'></i>
                    <span>Peminjaman</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/services/returning.php">
                    <i class='bx bxs-book-add'></i>
                    <span>Pengembalian</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/resources/research-papers.php">
                    <i class='bx bxs-file'></i>
                    <span>Paper Penelitian</span>
                </a>
            </li>
            <li>
                <a href="../NavContent/services/reading-room.php">
                    <i class='bx bxs-chair'></i>
                    <span>Ruang Baca</span>
                </a>
            </li>
            <li>
                <a href="../Login.php">
                    <i class='bx bxs-log-out'></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>Dashboard Admin</h2>
            <div class="user-info">
                <span>Selamat datang, <?php echo $_SESSION['admin_name']; ?></span>
            </div>
        </header>

        <div class="cards">
            <div class="card">
                <div class="card-content">
                    <i class='bx bxs-book'></i>
                    <div class="number">
                        <?php
                        include "../config/connection.php";
                        $query = "SELECT COUNT(*) as total FROM buku";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </div>
                    <div class="card-name">Total Buku</div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <i class='bx bxs-file'></i>
                    <div class="number">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM journals";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </div>
                    <div class="card-name">Total Jurnal</div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <i class='bx bxs-bookmark'></i>
                    <div class="number">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM peminjaman WHERE Status_Peminjaman='Dipinjam'";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </div>
                    <div class="card-name">Peminjaman Aktif</div>
                </div>
            </div>
        </div>

        <div class="recent-loans">
            <h3>Peminjaman Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Peminjaman</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT b.*, c.title 
                             FROM borrowing b
                             JOIN catalog c ON b.book_id = c.id
                             ORDER BY b.borrow_date DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['borrower_name']."</td>";
                        echo "<td>".$row['title']."</td>";
                        echo "<td>".$row['borrow_date']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>