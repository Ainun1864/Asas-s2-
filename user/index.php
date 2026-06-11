<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';

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
    <title>KimReader</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">
        ☰
    </button>

    <!-- MENU ATAS -->
    <div class="menu-sidebar">

        <a href="index.php">
            <span>⌂</span>
            <span class="text">Daftar Buku</span>
        </a>

        <a href="genre.php">
            <span>✧</span>
            <span class="text">Genre</span>
        </a>

        <a href="riwayat.php">
            <span>🗋</span>
            <span class="text">Pinjaman</span>
        </a>

    </div>

    <!-- BAWAH -->
    <div class="sidebar-bottom">

        <a href="../logout.php"
           class="logout-btn"
           onclick="return confirm('Yakin ingin logout?')">
            <span>𖦏</span>
            <span class="text">Logout</span>
        </a>

    </div>
    
</div>


<!-- SEARCH -->
<div class="top-search">

    <form method="post" class="search-form">

        <input
        type="text"
        name="keyword"
        placeholder="Cari buku..."
        autocomplete="off">

        <button
        type="submit"
        name="cari">
        ⌕
        </button>

    </form>

</div>


<div class="hero">

    <div class="hero-text">

        <h1>KimReader</h1>

        <p>
            Temukan dan pinjam berbagai buku favoritmu dengan mudah.
        </p>

    </div>

</div>

<!-- CONTAINER -->

<div class="container">
    <h2>Daftar Buku</h2>

    <br>

    <div class="buku-grid">

    <?php foreach($data as $d): ?>

        <div class="buku-card">

            <img
            src="../img/<?= $d["gambar"]; ?>"
            class="buku-cover">

            <div class="buku-content">

                <h3><?= $d["judul"]; ?></h3>

                <p class="penulis">
                    <?= $d["penulis"]; ?>
                </p>

                <p class="sinopsis">
                    <?= substr($d["sinopsis"],0,80); ?>...
                </p>

                <span class="genre-tag">
                    <?= $d["genre"]; ?>
                </span>

                <div class="info-buku">

                    <span>
                        <?= $d["tahun_terbit"]; ?>
                    </span>

                    <span>
                        Stok <?= $d["stok"]; ?>
                    </span>

                </div>

                <div class="aksi-card">

                    <a
                    href="pinjaman.php?id=<?= $d["id_buku"]; ?>"
                    class="btn-beli"
                    onclick="return confirm('Pinjam buku ini?')">
                    Pinjam
                    </a>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

    </div>

</div>

<script>
function toggleMenu(){
    document
    .getElementById("sidebar")
    .classList
    .toggle("active");
}
</script>

</body>
</html>