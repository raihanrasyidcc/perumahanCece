<?php
include '../database/koneksi.php';

session_start();

if (isset($_SESSION['nama']) && $_SESSION['role']) {
    header('Location:../');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $akunRaw = $koneksi->query("SELECT * FROM akun WHERE email='" . $email . "'");
    if ($akunRaw->num_rows > 0) {
        $akun = $akunRaw->fetch_assoc();
        if (password_verify($password, $akun['password'])) {
            $_SESSION['nama'] = $akun['nama'];
            $_SESSION['email'] = $akun['email'];
            $_SESSION['role'] = $akun['role'];
            if ($_SESSION['role'] == 'admin') {
                header('Location: ../admin/');
            } else {
                header('Location:../');
            }
        } else {
            echo '
        <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
  <strong>Password salah</strong> Password yang Anda masukkan salah.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        ';
        }
    } else {
        echo '
        <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
  <strong>Email tidak ditemukan</strong> Email yang Anda masukkan salah.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        ';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perumahan Cece | Login</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Wrapper untuk center -->
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h4 class="text-center mb-4">Login</h4>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <p class="text-secondary">Belum punya akun? <span><a href="../register/">Daftar</a></span></p>
                <input type="submit" class="btn btn-primary w-100" value="Login"></input>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>