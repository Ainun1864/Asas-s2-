<?php
require 'functions.php';

if(isset($_POST["cari"])){

    $keyword = $_POST["keyword"];

    $data = query("
        SELECT * FROM buku
        WHERE
        judul LIKE '%$keyword%'
        OR penulis LIKE '%$keyword%'
        OR sinopsis LIKE '%$keyword%'
        OR genre LIKE '%$keyword%'
    ");

} else {

    $data = query("SELECT * FROM buku");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>KimReaderS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
        }
        .btn-daftar, .btn-login {
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-daftar {
            background: transparent;
            border: 2px solid #C7F21D;
            color: #C7F21D;
        }
        .btn-daftar:hover {
            background: #C7F21D;
            color: #111;
        }
        .btn-login {
            background: #C7F21D;
            color: #111;
        }
        .btn-login:hover {
            background: #7C3AED;
            color: white;
        }
        .container {
            margin-left: 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="menu">
        <a href="kimcompany.php" class="active">Buku</a>
        <a href="#" onclick="harusLogin()">Genre</a>
        <a href="#" onclick="harusLogin()">Pinjaman</a>
    </div>

    <div class="topbar-right">
        <form method="post" class="search-form">
            <input type="text" name="keyword" placeholder="Cari buku..." autocomplete="off">
            <button type="submit" name="cari">⌕</button>
        </form>

        <a href="register.php" class="btn-daftar">Sign In</a>
        <a href="login_user.php" class="btn-login">Login</a>
    </div>
</div>

<div class="hero">
    <div class="hero-text">
        <h1>KimReaderS</h1>
        <p>Temukan dan pinjam berbagai buku favoritmu dengan mudah.</p>
    </div>
</div>

<div class="container">
    <h2>Daftar Buku</h2>
    <br>

    <div class="buku-grid">
        <?php foreach($data as $d): ?>
        <div class="buku-card">
            <img src="img/<?= $d["gambar"]; ?>" class="buku-cover">
            <div class="buku-content">
                <h3><?= $d["judul"]; ?></h3>
                <p class="penulis"><?= $d["penulis"]; ?></p>
                <p class="sinopsis"><?= substr($d["sinopsis"],0,80); ?>...</p>
                <span class="genre-tag"><?= $d["genre"]; ?></span>
                <div class="info-buku">
                    <span><?= $d["tahun_terbit"]; ?></span>
                    <span>Stok <?= $d["stok"]; ?></span>
                </div>
                <div class="aksi-card">
                    <a href="#" class="btn-beli" onclick="harusLogin()">Pinjam</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function harusLogin(){
    alert("Silakan login terlebih dahulu!");
    window.location.href = "login_user.php";
}
</script>

</body>
</html>