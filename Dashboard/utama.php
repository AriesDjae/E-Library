<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Utama</h1>

    <!-- Statistik -->
    <?php
    $totalBooks = $conn->query("SELECT COUNT(*) AS total FROM buku")->fetch_assoc()['total'];
    $uniqueAuthors = $conn->query("SELECT COUNT(DISTINCT Penulis) AS unique_authors FROM buku")->fetch_assoc()['unique_authors'];
    ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-book"></i> Total Buku: <?= $totalBooks ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Aktivitas -->
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

    <!-- Tabel Buku -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="m-0 font-weight-bold text-dark">Daftar Buku</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">
                        <i class="fas fa-plus me-2"></i>Tambah Buku
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm table-bordered" id="datatable" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th style="min-width: 50px;"><i class="fas fa-id-card"></i> ID</th>
                            <th style="min-width: 200px;"><i class="fas fa-book-open"></i> Judul</th>
                            <th style="min-width: 150px;"><i class="fas fa-user-edit"></i> Penulis</th>
                            <th style="min-width: 150px;"><i class="fas fa-building"></i> Penerbit</th>
                            <th style="min-width: 100px;"><i class="fas fa-calendar-alt"></i> Tahun</th>
                            <th style="min-width: 80px;"><i class="fas fa-boxes"></i> Stok</th>
                            <th style="min-width: 200px;"><i class="fas fa-info-circle"></i> Deskripsi</th>
                            <th style="min-width: 100px;"><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Debug mode
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);

                        if (!isset($conn)) {
                            require 'db.php';
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
                                    $Deskripsi_Singkat = strlen($Deskripsi) > 50 ? substr($Deskripsi, 0, 50) . "..." : $Deskripsi;
                        ?>
                                    <tr>
                                        <td><?= $ID_Buku ?></td>
                                        <td><?= $Judul ?></td>
                                        <td><?= $Penulis ?></td>
                                        <td><?= $Penerbit ?></td>
                                        <td><?= $Tahun_Terbit ?></td>
                                        <td><?= $Stok ?></td>
                                        <td>
                                            <a href="#" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#deskripsiModal<?= $ID_Buku ?>">
                                                <?= $Deskripsi_Singkat ?>
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST" action="delete_buku.php" style="display:inline;">
                                                <input type="hidden" name="id_buku" value="<?= $ID_Buku ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- Modal Deskripsi untuk buku ini -->
                                    <div class="modal fade" id="deskripsiModal<?= $ID_Buku ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Deskripsi Buku: <?= $Judul ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p style="white-space: pre-wrap;"><?= $Deskripsi ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <form method="post" enctype="multipart/form-data" action="control/stok.php">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ID_Buku" class="form-label">ID Buku</label>
                            <input type="number" class="form-control" id="ID_Buku" name="ID_Buku" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ID_Kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="ID_Kategori" name="ID_Kategori" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <?php
                                $query_kategori = "SELECT * FROM kategori";
                                $result_kategori = $conn->query($query_kategori);
                                while ($row = $result_kategori->fetch_assoc()) {
                                    echo "<option value='" . $row['ID_Kategori'] . "'>" . $row['Nama_Kategori'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="Judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="Judul" name="Judul" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Penulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="Penulis" name="Penulis" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Penerbit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="Penerbit" name="Penerbit" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Tahun_Terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="Tahun_Terbit" name="Tahun_Terbit"
                                min="1900" max="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Lokasi_Rak" class="form-label">Lokasi Rak</label>
                            <input type="text" class="form-control" id="Lokasi_Rak" name="Lokasi_Rak">
                        </div>
                        <div class="col-md-6">
                            <label for="Stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="Stok" name="Stok" min="0" required>
                        </div>
                        <div class="col-12">
                            <label for="Deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="Deskripsi" name="Deskripsi" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="Cover_Image" class="form-label">Cover Buku</label>
                            <input type="file" class="form-control" id="Cover_Image" name="Cover_Image"
                                accept="image/*">
                            <div class="form-text">Format yang diperbolehkan: JPG, JPEG, PNG, SVG. Maksimal 2MB</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="addbuku">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>