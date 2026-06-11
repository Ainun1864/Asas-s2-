<?php
session_start();

if($_SESSION["role"] != "admin"){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';

$genre = query("SELECT * FROM genre");

if(isset($_POST["submit"])){

    if(
        empty($_POST["judul"]) ||
        empty($_POST["penulis"]) ||
        empty($_POST["sinopsis"]) ||
        empty($_POST["tahun_terbit"]) ||
        empty($_POST["stok"]) ||
        !isset($_POST["genre"])
    ){

        echo "
        <script>
            alert('Semua data wajib diisi dan minimal pilih 1 genre!');
        </script>
        ";

    } else {

        if(tambahBuku($_POST) > 0){

            echo "
            <script>
                alert('Buku berhasil ditambahkan');
                document.location.href='index.php';
            </script>
            ";

        } else {

            echo "
            <script>
                alert('Buku gagal ditambahkan');
            </script>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">

<form
method="POST"
enctype="multipart/form-data"
class="form-card"
onsubmit="return validasiForm()"
>

<h1>Tambah Buku</h1>

<label>Gambar Buku</label>
<input
type="file"
name="gambar"
required>

<label>Genre</label>

<div class="genre-list">

<?php foreach($genre as $g): ?>

<label class="genre-item">
    <input
    type="checkbox"
    name="genre[]"
    value="<?= $g["nama_genre"]; ?>">

    <?= $g["nama_genre"]; ?>
</label>

<?php endforeach; ?>

</div>

<br><br>

<label>Judul Buku</label>
<input
type="text"
name="judul"
required>

<label>Penulis</label>
<input
type="text"
name="penulis"
required>

<label>Sinopsis</label>
<input
type="text"
name="sinopsis"
required>

<label>Tahun Terbit</label>
<input
type="number"
name="tahun_terbit"
required>

<label>Stok</label>
<input
type="number"
name="stok"
required>

<br><br>

<button type="submit" name="submit">
Tambah Buku
</button>

</form>

</div>

<script>
function validasiForm(){

    let genre = document.querySelectorAll(
        'input[name="genre[]"]:checked'
    );

    if(genre.length === 0){
        alert("Pilih minimal 1 genre!");
        return false;
    }

    return true;
}
</script>

</body>
</html>