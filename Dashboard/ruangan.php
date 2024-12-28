<?php
require 'db.php';
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Ruangan Baca</h1>

    <!-- Statistik -->
    <?php
    // Query untuk menghitung total ruangan yang sedang dipesan
    $totalBookings = $conn->query("SELECT COUNT(*) AS total FROM reading_room WHERE Status = 'Dipesan'")->fetch_assoc()['total'];
    ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-book-reader"></i> Total Reservasi: <?= $totalBookings ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <!-- <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Aktivitas User
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Chart Peminjaman Buku
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Tabel -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="m-0 font-weight-bold text-dark">Daftar Ruangan Baca</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">
                        <i class="fas fa-plus me-2"></i>Tambah Ruangan
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered" id="datatable" width="100%" cellspacing="0">
                <thead class="table-primary">
                    <tr>
                        <th><i class="fas fa-id-card"></i> ID Ruangan</th>
                        <th><i class="fas fa-door-open"></i> Nama Ruangan</th>
                        <th><i class="fas fa-users"></i> Kapasitas</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-tools"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($conn->connect_error) {
                        echo "<tr><td colspan='5' class='text-center text-danger'>Koneksi ke database gagal: " . $conn->connect_error . "</td></tr>";
                    } else {
                        $query = "SELECT * FROM reading_room ORDER BY ID_Room ASC";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($data = $result->fetch_assoc()) {
                                $id = htmlspecialchars($data['ID_Room'], ENT_QUOTES, 'UTF-8');
                                $nama = htmlspecialchars($data['Nama_Ruang'], ENT_QUOTES, 'UTF-8');
                                $kapasitas = htmlspecialchars($data['Kapasitas'], ENT_QUOTES, 'UTF-8');
                                $status = htmlspecialchars($data['Status'], ENT_QUOTES, 'UTF-8');

                                // Menentukan warna badge untuk status
                                $statusClass = ($status == 'Tersedia') ? 'badge bg-success' : 'badge bg-warning';
                    ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $nama ?></td>
                                    <td><?= $kapasitas ?> orang</td>
                                    <td><span class="<?= $statusClass ?>"><?= $status ?></span></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal<?= $id ?>"
                                            title="Edit ruangan ini">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method='POST' action='delete_room.php' style='display:inline;'>
                                            <input type='hidden' name='id_room' value='<?= $id ?>'>
                                            <button type='submit'
                                                class='btn btn-danger btn-sm'
                                                data-bs-toggle="tooltip"
                                                title="Hapus ruangan ini"
                                                onclick='return confirm("Apakah Anda yakin ingin menghapus ruangan ini?");'>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Tidak ada data ruangan</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Ruangan -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Ruangan Baca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <form method="post" action="../Dashboard/control/add_room.php">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="Nama_Ruang" class="form-label">Nama Ruangan</label>
                                <input type="text" class="form-control" id="Nama_Ruang" name="Nama_Ruang" required>
                            </div>
                            <div class="col-12">
                                <label for="Kapasitas" class="form-label">Kapasitas</label>
                                <input type="number" class="form-control" id="Kapasitas" name="Kapasitas"
                                    min="1" value="10" required>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="addroom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ruangan -->
    <?php
    if ($result && $result->num_rows > 0) {
        $result->data_seek(0); // Reset pointer hasil query
        while ($data = $result->fetch_assoc()) {
            $id = htmlspecialchars($data['ID_Room'], ENT_QUOTES, 'UTF-8');
            $nama = htmlspecialchars($data['Nama_Ruang'], ENT_QUOTES, 'UTF-8');
            $kapasitas = htmlspecialchars($data['Kapasitas'], ENT_QUOTES, 'UTF-8');
    ?>
            <div class="modal fade" id="editModal<?= $id ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $id ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?= $id ?>">Edit Ruangan Baca</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <form method="post" action="control/edit_room.php">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <input type="hidden" name="ID_Room" value="<?= $id ?>">
                                    <div class="col-12">
                                        <label for="Nama_Ruang<?= $id ?>" class="form-label">Nama Ruangan</label>
                                        <input type="text" class="form-control" id="Nama_Ruang<?= $id ?>"
                                            name="Nama_Ruang" value="<?= $nama ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="Kapasitas<?= $id ?>" class="form-label">Kapasitas</label>
                                        <input type="number" class="form-control" id="Kapasitas<?= $id ?>"
                                            name="Kapasitas" min="1" value="<?= $kapasitas ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="Status<?= $id ?>" class="form-label">Status</label>
                                        <select class="form-select" id="Status<?= $id ?>" name="Status" required>
                                            <option value="Tersedia" <?= ($data['Status'] == 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                                            <option value="Dipesan" <?= ($data['Status'] == 'Dipesan') ? 'selected' : '' ?>>Dipesan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="editroom">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>