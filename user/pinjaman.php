<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';
global $conn;

$id = $_GET["id"];

$buku = query("
SELECT * FROM buku
WHERE id_buku='$id'
")[0];

if(isset($_POST["pinjam"])){

    $id_user = $_SESSION["id"];

    $judul = $buku["judul"];

    $tgl_pinjam = date("Y-m-d");

    $tgl_kembali = $_POST["tgl_kembali"];

    mysqli_query($conn,"
    INSERT INTO penyewaan
    (
        id_user,
        judul,
        tgl_pinjam,
        tgl_kembali,
        status,
        denda
    )
    VALUES
    (
        '$id_user',
        '$judul',
        '$tgl_pinjam',
        '$tgl_kembali',
        'Menunggu',
        0
    )
    ");

    echo "
    <script>
        alert('Permintaan peminjaman berhasil dikirim');
        document.location.href='riwayat.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pinjam Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">

<form method="post" class="form-card">

<h1>Pinjam Buku</h1>

<label>Judul Buku</label>

<input
type="text"
value="<?= $buku["judul"]; ?>"
readonly>

<label>Tanggal Pinjam</label>

<input
type="text"
value="<?= date("Y-m-d"); ?>"
readonly>

<label>Tanggal Kembali</label>

<input
type="date"
name="tgl_kembali"
required>

<br><br>

<button
type="submit"
name="pinjam">
Pinjam Buku
</button>

</form>

</div>

</body>
</html>