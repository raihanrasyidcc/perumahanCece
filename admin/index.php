<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location:../');
}

include '../database/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard | Admin</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.png" />
    <link href="../css/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../">Perumahan Cece</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </div>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"">
            <li class=" nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= $_SESSION['nama'] ?></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="../database/logout.php">Logout</a></li>
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
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="tambah/">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Tambah Artikel
                        </a>

                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Seluruh artikel</li>
                    </ol>
                    <div class="row mb-3">
                        <?php
                        $artikelRaw = $koneksi->query("SELECT * FROM artikel ORDER BY waktu");

                        if ($artikelRaw->num_rows > 0) {
                            while ($artikel = $artikelRaw->fetch_assoc()) {
                                $tanggal = $artikel['waktu'];

                                date_default_timezone_set('Asia/Jakarta');
                                $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
                                $formatter->setPattern('EEEE, dd MMMM yyyy HH:mm');

                                $waktuTampil = $formatter->format(new DateTime($tanggal));

                                echo '
                            <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card" style="width: 18rem;">
                                <img src="../' . $artikel['foto'] . '" class="card-img-top" alt="...">
                                <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary text-secondary fs-6">' . $waktuTampil . '
                                                </h6>
                                    <h5 class="card-title">' . $artikel['judul'] . '</h5>
                                    <p class="card-text">' . $artikel['isi'] . '</p>
                                    <a href="edit.php?idArtikel=' . $artikel['id_artikel'] . '" class="btn btn-primary">Kelola</a>
                                </div>
                            </div>
                        </div>
                            ';
                            }
                        } else {
                            echo '
                    <h4 class="mt-4">Belum ada artikel</h4>
                            ';
                        }


                        ?>

                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
    <script src="../js/js/scripts.js"></script>
</body>

</html>