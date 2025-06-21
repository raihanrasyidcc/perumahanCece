<?php
include '../database/koneksi.php';
session_start();

$kategori = $_GET['kategori'];
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$query = "SELECT * FROM artikel WHERE kategori='$kategori'";

if (!empty($keyword)) {
    $escapedKeyword = $koneksi->real_escape_string($keyword);
    $query .= " AND (judul LIKE '%$escapedKeyword%' AND kategori LIKE '%$kategori%')";
}

$query .= " ORDER BY waktu DESC";

$artikelRaw = $koneksi->query($query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Aset Cece | Kategori</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.png" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="home">
        <div class="container px-5">
            <a class="navbar-brand" href="../">Aset Cece</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="../">Beranda</a></li>
                    <?php

                    if (isset($_SESSION['nama'])) {
                        echo '
                                    <li class=" nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                        echo $_SESSION['nama'];
                        echo '</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">';
                        if ($_SESSION['role'] == 'admin') {
                            echo '<li><a class="dropdown-item" href="../admin/">Admin</a></li>
                            <li>
                            <hr class="dropdown-divider" />
                        </li>
                            ';
                        }
                        echo '
                        <li><a class="dropdown-item" href="../database/logout.php">Logout</a></li>
                    </ul>
                    </li>
                        ';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="login/">Login</a></li>';
                    }
                    ?>
                </ul>
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"">

                </ul>
            </div>
        </div>
    </nav>

    <!-- Header-->
    <header class=" bg-dark py-5">
                    <div class="container px-5">
                        <div class="row gx-5 justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center my-5">
                                    <h1 class="display-5 fw-bolder text-white mb-2">Selamat Datang di Website Aset Kami!</h1>
                                    <p class="lead text-white-50 mb-4">Website informasi Aset yang terpercaya di Malang.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </header>

                    <!-- Features section-->
                    <section class="py-5 px-5 border-bottom" id="features">

                        <div class="row">
                            <div class="col-9 mb-3 mb-sm-0">
                                <?php
                                if ($artikelRaw->num_rows > 0) {
                                    while ($artikel = $artikelRaw->fetch_assoc()) {

                                        $tanggal = $artikel['waktu'];

                                        date_default_timezone_set('Asia/Jakarta');
                                        $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
                                        $formatter->setPattern('EEEE, dd MMMM yyyy HH:mm');

                                        $waktuTampil = $formatter->format(new DateTime($tanggal));

                                        echo '
                                    <div class="card mb-3" style="width: 74rem;">
                                    <img src="../' . $artikel['foto'] . '" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-body-secondary text-secondary fs-6">' . $waktuTampil . '
                                                    </h6>
                                        <h5 class="card-title">' . $artikel['judul'] . '</h5>
                                        <p class="card-text">' . $artikel['isi'] . '</p>
                                        <a href="../artikel/index.php?artikel=' . $artikel['id_artikel'] . '" class="btn btn-primary">Selengkapnya →</a>
                                        </div>
                                    </div>
                                    ';
                                    }
                                } else {
                                    echo '<p class="text-muted">Tidak ada artikel ditemukan.</p>';
                                }
                                ?>
                            </div>
                            <div class="col-3">
                                <div class="card mb-3">
                                    <div class="card-header">Pencarian</div>
                                    <form class="p-3" method="GET" action="">
                                        <div class="input-group">
                                            <input type="hidden" name="kategori" value="<?= $kategori ?>">
                                            <input type="text" class="form-control" name="keyword" placeholder="Cari artikel..." value="<?= htmlspecialchars($keyword) ?>">
                                            <button class="btn btn-primary" type="submit">Cari</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card mb-3" style="width: 23rem;">
                                    <div class="card-header">
                                        Kategori
                                    </div>
                                    <ul class="list-group list-group-flush justify-content-center align-items-center">
                                        <div class="card my-3" style="width: 20rem;">
                                            <ul class="list-group list-group-flush">
                                                <?php
                                                $kategoriRaw = $koneksi->query("SELECT kategori FROM artikel GROUP BY kategori");
                                                while ($kategori = $kategoriRaw->fetch_assoc()) {
                                                    $kategoriUrl = urlencode($kategori['kategori']);
                                                    $kategoriNama = htmlspecialchars($kategori['kategori']);
                                                    echo '
        <li class="list-group-item">
            <a href="?kategori=' . $kategoriUrl . '" style="text-decoration: none; color: inherit;">' . $kategoriNama . '</a>
        </li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </ul>
                                </div>
                                <div class="card mb-3" style="width: 23rem;">
                                    <div class="card-header">
                                        Tentang
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <div class="input-group mb-3 mt-3 px-3">
                                            <p class="card-text" style="text-align: justify;">Some quick example text to build on the
                                                card title and make up the bulk
                                                of the card’s content.</p>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Footer-->
                    <footer class="py-5 bg-dark">
                        <div class="container px-5">
                            <p class="m-0 text-center text-white">Copyright &copy; Aset Cece 2025</p>
                        </div>
                    </footer>
                    <!-- Bootstrap core JS-->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
                    <!-- Core theme JS-->
                    <script src="../js/scripts.js"></script>
                    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
                    <!-- * *                               SB Forms JS                               * *-->
                    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
                    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
                    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>