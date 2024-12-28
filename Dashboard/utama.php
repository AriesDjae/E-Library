
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard Utama</h1>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Aktivitas User
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Chart Peminjaman Buku
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Tambah buku
                                    </button>
                            </div>
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


                        </div>
                    </div>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Aksa</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>

        </div>

    <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Tambah Buku</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="number" name="ID_Buku" placeholder="ID_Buku" class="form-control">
          <br>
          <input type="number" name="ID_Kategori" placeholder="ID_Kategori" class="form-control">
          <br>
          <input type="text" name="Penulis" placeholder="Penulis" class="form-control">
          <br>
          <input type="text" name="Judul" placeholder="Judul" class="form-control">
          <br>
          <input type="text" name="Penerbit" placeholder="Penerbit" class="form-control">
          <br>
          <input type="number" name="Tahun_Terbit" placeholder="Tahun_Terbit" class="form-control">
          <br>
          <input type="text" name="Lokasi_Rak" placeholder="Lokasi_Rak" class="form-control">
          <br>
          <input type="number" name="Stok" placeholder="Stok" class="form-control">
          <br>
          <input type="text" name="Deskripsi" placeholder="Deskripsi" class="form-control">
          <br>
          <input type="file" id="img" name="Cover_Image" placeholder="Cover_Image" accept=".svg, image/svg+xml" class="form-control">
          <br>
          <button type="submit" class="btn btn-primary" name="addbuku">Submit</button>
        </div>
        </form>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>