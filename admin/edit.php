<?php
session_start();

if($_SESSION["role"] != "admin"){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';

$genre = query("SELECT * FROM genre");

$id = $_GET["id"];

$data = query(
    "SELECT * FROM buku WHERE id_buku='$id'"
)[0];

if(isset($_POST["submit"])){

    if(ubahBuku($_POST) > 0){

        echo "
        <script>
            alert('Buku berhasil diubah');
            document.location.href='index.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Buku gagal diubah');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">

<form method="POST"
enctype="multipart/form-data"
class="form-card">

<input
type="hidden"
name="id_buku"
value="<?= $data["id_buku"]; ?>">

<input
type="hidden"
name="gambarLama"
value="<?= $data["gambar"]; ?>">

<h1>Edit Buku</h1>

<label>Gambar Saat Ini</label>

<br><br>

<img
src="../img/<?= $data["gambar"]; ?>"
class="preview-cover">

<br><br>

<label>Ganti Gambar</label>
<input type="file" name="gambar">

<label>Genre</label>

<?php
$genreTerpilih = explode(", ", $data["genre"]);
?>

<div class="genre-list">

<?php foreach($genre as $g): ?>

<label class="genre-item">

    <input
    type="checkbox"
    name="genre[]"
    value="<?= $g["nama_genre"]; ?>"
    <?= in_array($g["nama_genre"], $genreTerpilih) ? 'checked' : ''; ?>>

    <?= $g["nama_genre"]; ?>

</label>

<?php endforeach; ?>

</div>

<br>

<br><br>

<label>Judul Buku</label>
<input
type="text"
name="judul"
value="<?= $data["judul"]; ?>"
required>

<label>Penulis</label>
<input
type="text"
name="penulis"
value="<?= $data["penulis"]; ?>"
required>

<label>Sinopsis</label>
<input
type="text"
name="sinopsis"
value="<?= $data["sinopsis"]; ?>"
required>

<label>Tahun Terbit</label>
<input
type="number"
name="tahun_terbit"
value="<?= $data["tahun_terbit"]; ?>"
required>

<label>Stok</label>
<input
type="number"
name="stok"
value="<?= $data["stok"]; ?>"
required>

<br><br>

<button type="submit" name="submit">
Ubah Buku
</button>

</form>

</div>

</body>
</html>