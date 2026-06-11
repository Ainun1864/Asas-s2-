<?php
session_start();
require 'functions.php';

if(isset($_POST["register"])){

    if(register($_POST) > 0){

        echo "
        <script>
            alert('Register berhasil');
            document.location.href='login_user.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Register gagal');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>

    <link rel="stylesheet" href="style.css">
</head>

<div class="login-page">

<form method="POST" class="form-card" autocomplete="off">

    <h1>Register</h1>

    <label>NIK</label>
    <input type="text" name="nik" autocomplete="off" required>

     <label>NISN</label>
    <input type="text" name="nisn" autocomplete="off" required>

    <label>Nama </label>
    <input type="text" name="nama" autocomplete="off" required>

    <label>username</label>
    <input type="text" name="username" autocomplete="off" required>

    <label>Email</label>
    <input type="email" name="email" autocomplete="off" required>

    <label>nohp</label>
    <input type="nohp" name="nohp" autocomplete="off" required>

    <label>Password</label>
    <input type="password" name="password" autocomplete="new-password" required>

    <label>Konfirmasi Password</label>
    <input type="password" name="konfirmasi" autocomplete="new-password" required>

    <button type="submit" name="register">
        Register
    </button>

    <p>
        Sudah punya akun?
        <a href="login_user.php">Login</a>
    </p>

</form>

</div>

</body>
</html>