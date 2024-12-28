<div class="card-body">
    <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Debug mode
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if (!isset($conn)) {
            require_once 'db.php';
        }

        if ($conn->connect_error) {
            die("<tr><td colspan='8' class='text-center text-danger'>Koneksi ke database gagal: " . $conn->connect_error . "</td></tr>");
        }

        try {
            $query = "SELECT * FROM buku ORDER BY ID_Buku ASC";
            $result = $conn->query($query);

            if (!$result) {
                throw new Exception("Query error: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $ID_Buku = htmlspecialchars($data['ID_Buku'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Judul = htmlspecialchars($data['Judul'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Penulis = htmlspecialchars($data['Penulis'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Penerbit = htmlspecialchars($data['Penerbit'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Tahun_Terbit = htmlspecialchars($data['Tahun_Terbit'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Stok = htmlspecialchars($data['Stok'] ?? '', ENT_QUOTES, 'UTF-8');
                    $Deskripsi = htmlspecialchars($data['Deskripsi'] ?? '', ENT_QUOTES, 'UTF-8');
        ?>
                    <tr>
                        <td><?= $ID_Buku ?></td>
                        <td><?= $Judul ?></td>
                        <td><?= $Penulis ?></td>
                        <td><?= $Penerbit ?></td>
                        <td><?= $Tahun_Terbit ?></td>
                        <td><?= $Stok ?></td>
                        <td><?= $Deskripsi ?></td>
                        <td>
                            <form method="POST" action="delete_buku.php" style="display:inline;">
                                <input type="hidden" name="id_buku" value="<?= $ID_Buku ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                            </form>
                        </td>
                    </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data buku yang tersedia</td></tr>";
            }
        } catch (Exception $e) {
            echo "<tr><td colspan='8' class='text-center text-danger'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
            error_log("Database Error: " . $e->getMessage());
        }

        if (isset($result) && $result instanceof mysqli_result) {
            $result->free();
        }
        ?>
        </tbody>
    </table>
</div>