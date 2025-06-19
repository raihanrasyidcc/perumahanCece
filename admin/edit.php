<?php
include '../database/koneksi.php';

session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location:../');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function prosesFoto($judul)
    {
        $namaFoto = $_FILES['foto']['name'];
        $tmpFoto = $_FILES['foto']['tmp_name'];
        $fotoSize = $_FILES['foto']['size'];
        $fotoExt = strtolower(pathinfo($namaFoto, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'png', 'jpeg'];


        if (!in_array($fotoExt, $allowed)) {
            echo '
            <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
      <strong>Format foto tidak didukung</strong> Format yang didukung: JPG, JPEG, PNG.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
            ';
            exit;
        }

        if ($fotoSize > 2 * 1024 * 1024) {
            echo '
            <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
      <strong>Ukuran foto terlalu besar</strong> Maksimal 2MB.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
            ';
            exit;
        }

        $newFileName = $judul . '.' . $fotoExt;
        $targetPath = '../../artikel/foto/' . $newFileName;

        if (move_uploaded_file($tmpFoto, $targetPath)) {
            $newTargetPath = 'artikel/foto/' . $newFileName;
            return $newTargetPath;
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
      <strong>Gagal upload foto/strong> Harap ulangi lagi.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
            ';
            exit;
        }
    }

    $idArtikel = $_POST['idArtikel'];
    $judul = $_POST['judul'];
    $dataLamaRaw = $koneksi->query("SELECT * FROM artikel WHERE id_artikel='" . $idArtikel . "'");
    $dataLama = $dataLamaRaw->fetch_assoc();
    if (!empty($dataLama['foto'])) {
        $pathFotoLama = '../../' . $dataLama['foto'];
        if (file_exists($pathFotoLama) && is_file($pathFotoLama)) {
            unlink($pathFotoLama);
        }
    }
    $kategori = $_POST['kategori'];
    $isi = $_POST['isi'];
    date_default_timezone_set('Asia/Jakarta');

    // 1. Format untuk tampilan (contoh: Senin, 06 Juni 2025 23:59)
    $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
    $formatter->setPattern('EEEE, dd MMMM yyyy HH:mm');
    $tanggal_display = $formatter->format(new DateTime());

    // 2. Format untuk data (contoh: 2025-06-06 23:59)
    $tanggal_data = date('Y-m-d H:i');

    if (!empty($_FILES['foto']['name'])) {
        if (file_exists($pathFotoLama)) {
            unlink($pathFotoLama);
        }
        $pathFoto = prosesFoto($judul);
        $updateArtikel = $koneksi->query("UPDATE artikel SET judul='$judul', foto='$pathFoto', kategori='$kategori', isi='$isi', waktu='$tanggal_data', penulis='" . $_SESSION['nama'] . "' WHERE id_artikel='$idArtikel'");
        if ($updateArtikel) {
            echo '
            <div class="alert alert-success alert-dismissible fade show position-absolute top-0 end-0 m-3 mt-xxl-5 z-3" role="alert">
            <strong>Berhasil update artikel</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
      <strong>Gagal update artikel/strong> Harap ulangi lagi.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        }
    } else {
        $updateArtikel = $koneksi->query("UPDATE artikel SET judul='$judul', foto='" . $dataLama['foto'] . "', kategori='$kategori', isi='$isi', waktu='$tanggal_data', penulis='" . $_SESSION['nama'] . "' WHERE id_artikel='$idArtikel'");
        if ($updateArtikel) {
            echo '
            <div class="alert alert-success alert-dismissible fade show position-absolute top-0 end-0 m-3 mt-xxl-5 z-3" role="alert">
            <strong>Berhasil update artikel</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
      <strong>Gagal update artikel/strong> Harap ulangi lagi.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edit Artikel | Admin</title>
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
                        <a class="nav-link" href="./">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Tambah Artikel
                        </a>

                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Artikel</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Tambah artikel</li>
                    </ol>
                    <div class="row mb-3 px-2">
                        <div class="card p-3">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Judul Artikel</label>
                                    <input type="hidden" name="idArtikel" id="idArtikel">
                                    <input type="text" class="form-control" id="judul" name="judul" placeholder="" required>
                                </div>
                                <label for="exampleFormControlInput1" class="form-label">Foto Cover Artikel</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="inputGroupFile01" name="foto" accept="image/*">
                                </div>
                                <label for="" class=""><small>*) Kosongi jika tidak ingin ubah foto</small></label>
                                <div class="mb-3"></div>
                                <label for="select" class="form-label">Kategori</label>
                                <select class="form-select mb-3" aria-label="Default select example" id="kategori" name="kategori" required>
                                    <option selected disabled>Pilihan</option>
                                    <option value="Tips & Trick">Tips & Trick</option>
                                    <option value="Edukasi">??</option>
                                    <option value="3">??</option>
                                    <option value="4">??</option>
                                </select>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Isi</label>
                                    <textarea name="isi" id="editor"></textarea>
                                </div>
                                <input type="submit" class="btn btn-primary" name="submit" value="Update">
                                <button type="button" class="btn btn-danger" onclick="hapusArtikel()" value="Update">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Perumahan Malang 2025</div>
                        <div>
                            <!-- <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a> -->
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/js/scripts.js"></script>
    <!-- Tambahkan script CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editorInstance = editor;

                // Setelah editor siap, ambil data artikel
                const params = new URLSearchParams(window.location.search);
                const idArtikel = params.get('idArtikel');

                fetch('getDataArtikel.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            idArtikel: idArtikel
                        }).toString()
                    })
                    .then(response => response.json())
                    .then(result => {
                        document.querySelector("input[name='idArtikel']").value = result.id_artikel;
                        document.getElementById('judul').value = result.judul;
                        document.getElementById('kategori').value = result.kategori;
                        editorInstance.setData(result.isi);
                    })
                    .catch(error => {
                        console.error('Gagal ambil data artikel:', error);
                    });
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const isi = editorInstance.getData().trim();

            if (isi === '') {
                alert("Isi artikel tidak boleh kosong.");
                e.preventDefault();
            }
        });
    </script>

    <script>
        function hapusArtikel() {
            const idArtikel = document.getElementById('idArtikel').value;
            if (confirm("Yakin ingin menghapus artikel ini?")) {
                fetch('hapusArtikel.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            idArtikel: idArtikel
                        }).toString()
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            alert(result.message);
                            window.location.href = "./";
                        } else {
                            alert("Gagal menghapus: " + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("Terjadi kesalahan saat menghapus artikel.");
                    });
            }
        }
    </script>

</body>