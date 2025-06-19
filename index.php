<?php
session_start();
include 'database/koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$query = "SELECT * FROM artikel";
if (!empty($keyword)) {
    $query .= " WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%'";
}
$query .= " ORDER BY waktu DESC";
$artikelRaw = $koneksi->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Perumahan Cece</title>
    <link rel="icon" type="image/x-icon" href="assets/icon.png" />
    <link rel="stylesheet" href="css/styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="home">
        <div class="container px-5">
            <a class="navbar-brand" href="../">Perumahan Cece</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="">Beranda</a></li>
                    <?php

                    if (isset($_SESSION['nama'])) {
                        echo '
                                    <li class=" nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                        echo $_SESSION['nama'];
                        echo '</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">';
                        if ($_SESSION['role'] == 'admin') {
                            echo '<li><a class="dropdown-item" href="admin/">Admin</a></li>
                            <li>
                            <hr class="dropdown-divider" />
                        </li>
                            ';
                        }
                        echo '
                        <li><a class="dropdown-item" href="database/logout.php">Logout</a></li>
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
    

    <header class=" bg-dark py-5">
                    <div class="container px-5">
                        <div class="row gx-5 justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center my-5">
                                    <h1 class="display-5 fw-bolder text-white mb-2">Selamat Datang di Website Perumahan Kami!</h1>
                                    <p class="lead text-white-50 mb-4">Disini Anda bisa memiliki Rumah Impian yang Anda Inginkan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </header>

                    <section class="py-5 px-5">
                        <div class="row">
                            <div class="col-md-9">
                                <?php
                                if ($artikelRaw->num_rows > 0) {
                                    $artikel1 = $artikelRaw->fetch_assoc();
                                    $tanggal = new DateTime($artikel1['waktu']);
                                    $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
                                    $formatter->setPattern('EEEE, dd MMMM yyyy HH:mm');
                                    $waktuTampil = $formatter->format($tanggal);

                                    echo '<div class="card mb-4" style="width: 100%;">
                        <img src="' . $artikel1['foto'] . '" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="text-muted">' . $waktuTampil . '</h6>
                            <h5 class="card-title">' . $artikel1['judul'] . '</h5>
                            <p class="card-text">' . $artikel1['isi'] . '</p>
                            <a href="artikel/index.php?artikel=' . $artikel1['id_artikel'] . '" class="btn btn-primary">Selengkapnya →</a>
                        </div>
                    </div>';

                                    echo '<div class="row">';
                                    while ($artikel = $artikelRaw->fetch_assoc()) {
                                        $waktuTampil = $formatter->format(new DateTime($artikel['waktu']));
                                        echo '<div class="col-md-6 col-xl-3 mb-4">
                            <div class="card h-100">
                                <img src="' . $artikel['foto'] . '" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h6 class="text-muted">' . $waktuTampil . '</h6>
                                    <h5 class="card-title">' . $artikel['judul'] . '</h5>
                                    <p class="card-text">' . $artikel['isi'] . '</p>
                                    <a href="artikel/index.php?artikel=' . $artikel['id_artikel'] . '" class="btn btn-primary">Selengkapnya →</a>
                                </div>
                            </div>
                        </div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<p class="text-muted">Tidak ada artikel ditemukan.</p>';
                                }
                                ?>
                            </div>
                            <div class="col-md-3">
                                <div class="card mb-3">
                                    <div class="card-header">Pencarian</div>
                                    <form class="p-3" method="GET" action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="keyword" placeholder="Cari artikel..." value="<?= htmlspecialchars($keyword) ?>">
                                            <button class="btn btn-primary" type="submit">Cari</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">Kategori</div>
                                    <ul class="list-group list-group-flush">
                                        <?php
                                        $kategoriRaw = $koneksi->query("SELECT kategori FROM artikel GROUP BY kategori");
                                        while ($kategori = $kategoriRaw->fetch_assoc()) {
                                            $kategoriUrl = urlencode($kategori['kategori']);
                                            echo '<li class="list-group-item"><a href="kategori/index.php?kategori=' . $kategoriUrl . '" style="text-decoration:none;color:inherit;">' . $kategori['kategori'] . '</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">Tentang</div>
                                    <div class="p-3">
                                        <p class="card-text">Website informasi perumahan terpercaya untuk keluarga Indonesia.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Footer-->
                    <footer class="py-5 bg-dark">
                        <div class="container px-5">
                            <p class="m-0 text-center text-white">Copyright &copy; Perumahan Malang</p>
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

                    <footer class="py-4 bg-dark mt-auto">
                        <div class="container text-center">
                            <p class="m-0 text-white">&copy; 2025 Perumahan Cece</p>
                        </div>
                    </footer>
</body>

</html>