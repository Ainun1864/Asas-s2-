<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../login_user.php");
    exit;
}

if($_SESSION["role"] != "admin"){
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
    <title>Admin Perpustakaan</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .cover-img{
            width:60px;
            height:80px;
            object-fit:cover;
            border-radius:5px;
        }

        .produk-table td, .produk-table th{
            padding:10px;
            text-align:center;
        }
    </style>
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

<h1>Kelola Data Buku</h1>

<br>

<a href="tambah.php">Tambah Buku</a>
<a href=""></a>

<div class="buku-grid">

<?php foreach($data as $d): ?>

<div class="buku-card">

    <img src="../img/<?= $d['gambar']; ?>" class="buku-cover">

    <div class="buku-content">

        
        <h3><?= $d['judul']; ?></h3>
        
        <p class="penulis">
            <?= $d['penulis']; ?>
        </p>
        <p class="sinopsis">
            <?= substr($d['sinopsis'],0,80); ?>...
        </p>
        
        <span class="genre-tag">
            <?= $d['genre']; ?>
        </span>
        
        <div class="info-buku">
            <span><?= $d['tahun_terbit']; ?></span>
            <span>Stok <?= $d['stok']; ?></span>
        </div>

        <div class="aksi-card">
            <a href="edit.php?id=<?= $d['id_buku']; ?>" class="btn-ubah">
                Edit
            </a>

            <a href="hapus.php?id=<?= $d['id_buku']; ?>"
               class="btn-hapus"
               onclick="return confirm('Hapus buku ini?')">
                Hapus
            </a>
        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

</div>

</body>
</html>