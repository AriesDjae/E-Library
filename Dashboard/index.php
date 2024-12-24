<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">E-Library</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard Utama
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Books & Rooms
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Rooms Booking 
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Books Booking 
                            </a>
                            
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard Utama</h1>
                        <!-- <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Login User
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
                </main>
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
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
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
</html>
