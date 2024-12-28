<div class="container-fluid px-4">
    <h1 class="mt-4">Reservasi</h1>

    <!-- Statistik -->
    <?php
    $totalBookings = $conn->query("SELECT COUNT(*) AS total FROM reading_room_booking")->fetch_assoc()['total'];
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
        <h5 class="m-0 font-weight-bold text-dark">Daftar Reservasi</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered" id="datatable" width="100%" cellspacing="0">
                <thead class="table-primary">
                    <tr>
                        <th><i class="fas fa-id-card"></i> ID</th>
                        <th><i class="fas fa-user"></i> Nama</th>
                        <th><i class="fas fa-calendar"></i> Tanggal</th>
                        <th><i class="fas fa-clock"></i> Waktu</th>
                        <th><i class="fas fa-door-open"></i> Ruangan</th>
                        <th><i class="fas fa-tools"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($conn->connect_error) {
                        echo "<tr><td colspan='6' class='text-center text-danger'>Koneksi ke database gagal: " . $conn->connect_error . "</td></tr>";
                    } else {
                        $query = "SELECT b.*, r.Nama_Ruang as room_name 
                                  FROM reading_room_booking b 
                                  JOIN reading_room r ON b.ID_Room = r.ID_Room 
                                  ORDER BY b.ID_Booking ASC";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($data = $result->fetch_assoc()) {
                                $id = htmlspecialchars($data['ID_Booking'], ENT_QUOTES, 'UTF-8');
                                $name = htmlspecialchars($data['Username'], ENT_QUOTES, 'UTF-8');
                                $date = htmlspecialchars($data['Tanggal_Booking'], ENT_QUOTES, 'UTF-8');
                                $time = htmlspecialchars($data['Waktu_Mulai'] . ' - ' . $data['Waktu_Selesai'], ENT_QUOTES, 'UTF-8');
                                $room = htmlspecialchars($data['room_name'], ENT_QUOTES, 'UTF-8');
                    ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $name ?></td>
                                    <td><?= $date ?></td>
                                    <td><?= $time ?></td>
                                    <td><?= $room ?></td>
                                    <td>
                                        <form method='POST' action='delete_booking.php' style='display:inline;'>
                                            <input type='hidden' name='id' value='<?= $id ?>'>
                                            <button type='submit' class='btn btn-danger btn-sm' data-bs-toggle="tooltip" title="Hapus reservasi ini" onclick='return confirm("Apakah Anda yakin ingin menghapus reservasi ini?");'>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data reservasi</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>