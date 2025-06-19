<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../database/koneksi.php';

    session_start();

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $passwordRaw = $_POST['password'];
    $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

    $q = $koneksi->query("INSERT INTO akun (nama, email, password, role) VALUES ('" . $nama . "','" . $email . "','" . $password . "', 'user')");

    if ($q) {
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user';
        header('Location: ../');
    } else {
        echo '
        <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 end-0 m-3" role="alert">
  <strong>Register Gagal</strong> Coba ulangi lagi dengan data yang benar.
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
    <title>Perumahan Cece | Register</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Wrapper untuk center -->
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h4 class="text-center mb-4">Register</h4>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Nama</label>
                    <input required type="text" class="form-control" name="nama" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input required type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input required type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <p class="text-secondary">Sudah punya akun? <span><a href="../login/">Login</a></span></p>
                <input type="submit" class="btn btn-primary w-100" value="Register"></input>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>