<div class="container-fluid px-4">
    <h1 class="mt-4">Data User</h1>

    <!-- Statistik -->
    <?php
    $totalUsers = $conn->query("SELECT COUNT(*) AS total FROM anggota")->fetch_assoc()['total'];
    $uniqueEmails = $conn->query("SELECT COUNT(DISTINCT Email) AS unique_emails FROM anggota")->fetch_assoc()['unique_emails'];
    ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-users"></i> Total Users: <?= $totalUsers ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Aktivitas User -->
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Aktivitas User
            </div>
            <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card-body">
        <table class="table table-striped table-hover table-bordered" id="datatable" width="100%" cellspacing="0">
            <thead class="table-primary">
                <tr>
                    <th><i class="fas fa-id-card"></i> ID</th>
                    <th><i class="fas fa-user"></i> Nama</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-tools"></i> Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($conn->connect_error) {
                    echo "<tr><td colspan='4' class='text-center text-danger'>Koneksi ke database gagal: " . $conn->connect_error . "</td></tr>";
                } else {
                    $query = "SELECT * FROM anggota ORDER BY ID_Anggota ASC";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($data = $result->fetch_assoc()) {
                            $ID_Anggota = htmlspecialchars($data['ID_Anggota'], ENT_QUOTES, 'UTF-8');
                            $Nama = htmlspecialchars($data['Nama'], ENT_QUOTES, 'UTF-8');
                            $Email = htmlspecialchars($data['Email'], ENT_QUOTES, 'UTF-8');
                ?>
                            <tr>
                                <td><?= $ID_Anggota ?></td>
                                <td><?= $Nama ?></td>
                                <td><?= $Email ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $ID_Anggota ?>"
                                        title="Edit user ini">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="delete_anggota.php" style="display:inline;">
                                        <input type="hidden" name="id_anggota" value="<?= $ID_Anggota ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data anggota yang tersedia</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit User -->
<?php
if ($result && $result->num_rows > 0) {
    $result->data_seek(0); // Reset pointer hasil query
    while ($data = $result->fetch_assoc()) {
        $ID_Anggota = htmlspecialchars($data['ID_Anggota'], ENT_QUOTES, 'UTF-8');
?>
        <div class="modal fade" id="editModal<?= $ID_Anggota ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $ID_Anggota ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?= $ID_Anggota ?>">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="control/edit_user.php">
                        <div class="modal-body">
                            <div class="row g-3">
                                <input type="hidden" name="ID_Anggota" value="<?= $ID_Anggota ?>">
                                <div class="col-12">
                                    <label for="Nama<?= $ID_Anggota ?>" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="Nama<?= $ID_Anggota ?>"
                                        name="Nama" value="<?= htmlspecialchars($data['Nama']) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="Email<?= $ID_Anggota ?>" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="Email<?= $ID_Anggota ?>"
                                        name="Email" value="<?= htmlspecialchars($data['Email']) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="Username<?= $ID_Anggota ?>" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="Username<?= $ID_Anggota ?>"
                                        name="Username" value="<?= htmlspecialchars($data['Username']) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="Password<?= $ID_Anggota ?>" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" id="Password<?= $ID_Anggota ?>" name="Password">
                                </div>
                                <div class="col-12">
                                    <label for="Alamat<?= $ID_Anggota ?>" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="Alamat<?= $ID_Anggota ?>"
                                        name="Alamat" required><?= htmlspecialchars($data['Alamat']) ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="No_Telepon<?= $id ?>" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" id="No_Telepon<?= $id ?>"
                                        name="No_Telepon" value="<?= htmlspecialchars($data['No_Telepon']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="Tipe<?= $id ?>" class="form-label">Tipe</label>
                                    <select class="form-select" id="Tipe<?= $id ?>" name="Tipe" required>
                                        <option value="Mahasiswa" <?= ($data['Tipe'] == 'Mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                        <option value="Dosen" <?= ($data['Tipe'] == 'Dosen') ? 'selected' : '' ?>>Dosen</option>
                                        <option value="Pengunjung" <?= ($data['Tipe'] == 'Pengunjung') ? 'selected' : '' ?>>Pengunjung</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="Status<?= $id ?>" class="form-label">Status</label>
                                    <select class="form-select" id="Status<?= $id ?>" name="Status" required>
                                        <option value="Aktif" <?= ($data['Status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Nonaktif" <?= ($data['Status'] == 'Nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="edituser">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    }
}
?>