<?php
session_start();

require '../functions.php';

if(isset($_POST["tambahGenre"])){

    if(tambahGenre($_POST) > 0){

        echo "
        <script>
            alert('Genre berhasil ditambahkan');
            document.location.href='genre.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Genre gagal ditambahkan');
        </script>
        ";
    }
}

$genreDipilih = isset($_GET["genre"])
    ? $_GET["genre"]
    : "";

$genre = query("SELECT * FROM genre");

if($genreDipilih != ""){

    $buku = query("
        SELECT * FROM buku
        WHERE genre LIKE '%$genreDipilih%'
    ");

} else {

    $buku = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Genre Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">
        ☰
    </button>

    <div class="menu-sidebar">

        <a href="index.php">
            <span>⌂</span>
            <span class="text">Daftar Buku</span>
        </a>

        <a href="genre.php">
            <span>✧</span>
            <span class="text">Genre</span>
        </a>

        <a href="transaksi.php">
            <span>🗎</span>
            <span class="text">Bookmark</span>
        </a>

    </div>

    <div class="sidebar-bottom">

        <a href="../logout.php"
           onclick="return confirm('Yakin ingin logout?')">

            <span>⦿</span>
            <span class="text">Logout</span>

        </a>

    </div>
</div>

<div class="container">

<h1 class="judul-kategori">
    Genre Buku
</h1>

<form method="post" class="form-tambah-genre">

    <input
    type="text"
    name="nama_genre"
    placeholder="Tambah genre baru..."
    required>

    <button type="submit" name="tambahGenre">
        Tambah Genre
    </button>

</form>

<div class="kategori-menu">

<?php foreach($genre as $g) : ?>

<a href="?genre=<?= $g["nama_genre"]; ?>">
    <?= ucfirst($g["nama_genre"]); ?>
</a>

<?php endforeach; ?>

</div>

<?php if($genreDipilih != "") : ?>

<h2 style="margin-bottom:20px;">
    Genre : <?= ucfirst($genreDipilih); ?>
</h2>

<div class="buku-grid">

<?php foreach($buku as $d): ?>

<div class="buku-card">

    <img
    src="../img/<?= $d['gambar']; ?>"
    class="buku-cover">

    <div class="buku-content">

        <span class="genre-tag">
            <?= $d['genre']; ?>
        </span>

        <h3>
            <?= $d['judul']; ?>
        </h3>

        <p class="penulis">
            <?= $d['penulis']; ?>
        </p>

        <p class="sinopsis">
            <?= substr($d['sinopsis'],0,100); ?>...
        </p>

        <div class="info-buku">
            <span><?= $d['tahun_terbit']; ?></span>
            <span>Stok <?= $d['stok']; ?></span>
        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

</body>
</html>