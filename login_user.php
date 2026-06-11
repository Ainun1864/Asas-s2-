<?php
session_start();
require 'functions.php';

if(isset($_POST["login"])){

    $nik = htmlspecialchars($_POST["nik"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE nik='$nik'
        AND username='$username'"
    );

    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row["password"])){

            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["nik"] = $row["nik"];
            $_SESSION["nama"] = $row["nama"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];

            if($row["role"] == "admin"){
                header("Location: admin/index.php");
            } else {
                header("Location: user/index.php");
            }

            exit;
        }
    }

    $error = true;
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

<body class="login-page">

    <div class="form-container">

        <form method="POST" class="form-card" autocomplete="off">

            <h1>Login</h1>

            <?php if(isset($error)) : ?>
                <p style="color:red; text-align:center; margin-bottom:15px;">
                    NIK, Username atau Password salah!
                </p>
            <?php endif; ?>

            <label>NIK</label>
            <input
                type="text"
                name="nik"
                autocomplete="off"
                required
            >

            <label>Username</label>
            <input
                type="text"
                name="username"
                autocomplete="off"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >

            <button type="submit" name="login">
                Login
            </button>

            <p style="margin-top:15px; text-align:center;">
                Belum punya akun?
                <a href="register.php">Register</a>
            </p>

             <p style="margin-top:15px; text-align:center;">
                Login sebagai admin?
                <a href="login_admin.php">Login</a>
            </p>

        </form>

    </div>

</body>
</html>