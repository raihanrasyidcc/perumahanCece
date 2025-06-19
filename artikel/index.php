<?php
include '../database/koneksi.php';
session_start();

$idArtikel = $_GET['artikel'];

$artikelRaw = $koneksi->query("SELECT * FROM artikel WHERE id_artikel='$idArtikel'");
$artikel = $artikelRaw->fetch_assoc();

$tanggal = $artikel['waktu'];

date_default_timezone_set('Asia/Jakarta');
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
$formatter->setPattern('EEEE, dd MMMM yyyy HH:mm');

$waktuTampil = $formatter->format(new DateTime($tanggal));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Perumahan Cece | Artikel</title>
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
            <a class="navbar-brand" href="../">Perumahan Cece</a>
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
                            <hr class="dropdown-divider"/>
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
    </nav>


    <!-- Features section-->
    <section class=" py-5 px-5 border-bottom" id="features">

                    <div class="row">
                        <div class="col-9 mb-3 mb-sm-0">
                            <h1><?= $artikel['judul'] ?></h1>
                            <p><em>Ditulis pada <?= $waktuTampil ?> oleh <?= $artikel['penulis'] ?></em></p>
                            <span class="badge text-bg-secondary"><?= $artikel['kategori'] ?></span>
                            <div class="text-center mt-3">
                                <img src="../<?= $artikel['foto'] ?>" class="rounded w-100" alt="...">
                            </div>
                            <div class="mt-3">
                                <p><?= $artikel['isi'] ?></p>
                            </div>


                        </div>
                        <div class="col-3">
                            <div class="card mb-3" style="width: 23rem;">
                                <div class="card-header">
                                    Pencarian
                                </div>
                                <ul class="list-group list-group-flush">
                                    <div class="input-group mb-3 mt-3 px-3">
                                        <input type="text" class="form-control" placeholder="Masukkan kata kunci..."
                                            aria-label="Masukkan kata kunci..." aria-describedby="button-addon2">
                                        <button class="btn btn-primary" type="button" id="button-addon2">Go!</button>
                                    </div>
                                </ul>
                            </div>
                            <div class="card mb-3" style="width: 23rem;">
                                <div class="card-header">
                                    Artikel Terkait
                                </div>
                                <ul class="list-group list-group-flush">

                                    <?php
                                    $suggestRaw = $koneksi->query("SELECT * FROM artikel WHERE kategori='" . $artikel['kategori'] . "' AND id_artikel != '" . $artikel['id_artikel'] . "'");
                                    while ($suggest = $suggestRaw->fetch_assoc()) {
                                        echo '
                                            <div class="input-group mb-3 mt-3 px-3">
                                        <p class="card-text" style="text-align: justify;">' . $suggest['judul'] . '</p>
                                    </div>
                                        ';
                                    }
                                    ?>

                                </ul>
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
</body>

</html>