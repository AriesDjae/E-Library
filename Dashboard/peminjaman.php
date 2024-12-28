<div class="container-fluid px-4">
    <h1 class="mt-4">Data Peminjaman</h1>

    <!-- Statistik -->
    <?php
    $totalPeminjaman = $conn->query("SELECT COUNT(*) AS total FROM peminjaman")->fetch_assoc()['total'];
    $aktivePeminjaman = $conn->query("SELECT COUNT(*) AS total FROM peminjaman WHERE Status_Peminjaman = 'Dipinjam'")->fetch_assoc()['total'];
    ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-book-reader"></i> Total Peminjaman: <?= $totalPeminjaman ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-clock"></i> Peminjaman Aktif: <?= $aktivePeminjaman ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="m-0 font-weight-bold text-dark">Daftar Peminjaman</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">
                        <i class="fas fa-plus me-2"></i>Tambah Peminjaman
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered" id="datatable" width="100%" cellspacing="0">
                <thead class="table-primary">
                    <tr>
                        <th><i class="fas fa-id-card"></i> ID</th>
                        <th><i class="fas fa-user"></i> Username</th>
                        <th><i class="fas fa-book"></i> Buku</th>
                        <th><i class="fas fa-calendar"></i> Tgl Pinjam</th>
                        <th><i class="fas fa-calendar-check"></i> Tgl Harus Kembali</th>
                        <th><i class="fas fa-calendar-times"></i> Tgl Pengembalian</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-money-bill"></i> Denda</th>
                        <th><i class="fas fa-tools"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($conn->connect_error) {
                        echo "<tr><td colspan='9' class='text-center text-danger'>Koneksi ke database gagal: " . $conn->connect_error . "</td></tr>";
                    } else {
                        $query = "SELECT p.*, b.Judul as nama_buku 
                                FROM peminjaman p 
                                JOIN buku b ON p.ID_Buku = b.ID_Buku 
                                ORDER BY p.ID_Peminjaman DESC";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($data = $result->fetch_assoc()) {
                                $id = htmlspecialchars($data['ID_Peminjaman'], ENT_QUOTES, 'UTF-8');
                                $username = htmlspecialchars($data['Username'], ENT_QUOTES, 'UTF-8');
                                $buku = htmlspecialchars($data['nama_buku'], ENT_QUOTES, 'UTF-8');
                                $tgl_pinjam = htmlspecialchars($data['Tanggal_Peminjaman'], ENT_QUOTES, 'UTF-8');
                                $tgl_harus_kembali = htmlspecialchars($data['Tanggal_Harus_Pengembalian'], ENT_QUOTES, 'UTF-8');
                                $tgl_kembali = $data['Tanggal_Pengembalian'] ? htmlspecialchars($data['Tanggal_Pengembalian'], ENT_QUOTES, 'UTF-8') : '-';
                                $status = htmlspecialchars($data['Status_Peminjaman'], ENT_QUOTES, 'UTF-8');
                                $denda = htmlspecialchars($data['Denda'], ENT_QUOTES, 'UTF-8');

                                // Menentukan warna badge untuk status
                                $statusClass = ($status == 'Dipinjam') ? 'badge bg-warning' : 'badge bg-success';
                    ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $username ?></td>
                                    <td><?= $buku ?></td>
                                    <td><?= date('d/m/Y', strtotime($tgl_pinjam)) ?></td>
                                    <td><?= date('d/m/Y', strtotime($tgl_harus_kembali)) ?></td>
                                    <td><?= $tgl_kembali != '-' ? date('d/m/Y', strtotime($tgl_kembali)) : '-' ?></td>
                                    <td><span class="<?= $statusClass ?>"><?= $status ?></span></td>
                                    <td>Rp <?= number_format($denda, 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($status == 'Dipinjam'): ?>
                                            <button class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#returnModal<?= $id ?>"
                                                title="Kembalikan buku">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>
                                        <?php endif; ?>
                                        <form method='POST' action='delete_peminjaman.php' style='display:inline;'>
                                            <input type='hidden' name='id_peminjaman' value='<?= $id ?>'>
                                            <button type='submit'
                                                class='btn btn-danger btn-sm'
                                                data-bs-toggle="tooltip"
                                                title="Hapus peminjaman ini"
                                                onclick='return confirm("Apakah Anda yakin ingin menghapus data peminjaman ini?");'>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>Tidak ada data peminjaman</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>