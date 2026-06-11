<?php

$conn = mysqli_connect("localhost","root","","kim_company");

function query($query){

    global $conn;

    $result = mysqli_query($conn,$query);
    $rows = [];

    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }

    return $rows;
}

/* regis */

function register($data){

    global $conn;

    $nik = htmlspecialchars($data["nik"]);
    $nisn = htmlspecialchars($data["nisn"]);
    $nama = htmlspecialchars($data["nama"]);
    $username = strtolower(stripslashes($data["username"]));
    $email = htmlspecialchars($data["email"]);
    $nohp = htmlspecialchars($data["nohp"]);

    $password = mysqli_real_escape_string(
        $conn,
        $data["password"]
    );

    $konfirmasi = mysqli_real_escape_string(
        $conn,
        $data["konfirmasi"]
    );

    if($password != $konfirmasi){
        return false;
    }

    $cekUsername = mysqli_query(
        $conn,
        "SELECT username FROM users
        WHERE username='$username'"
    );

    if(mysqli_fetch_assoc($cekUsername)){
        return false;
    }

    $cekEmail = mysqli_query(
        $conn,
        "SELECT email FROM users
        WHERE email='$email'"
    );

    if(mysqli_fetch_assoc($cekEmail)){
        return false;
    }

    $password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    mysqli_query($conn,"
    INSERT INTO users
    (nik, nisn, nama, username, email, nohp, password, role)
    VALUES
    (
        '$nik',
        '$nisn',
        '$nama',
        '$username',
        '$email',
        '$nohp',
        '$password',
        'user'
    )
    ");

    return mysqli_affected_rows($conn);
}

/* genre */

function tambahGenre($data){

    global $conn;

    $nama_genre = htmlspecialchars(
        $data["nama_genre"]
    );

    mysqli_query($conn,"
    INSERT INTO genre(nama_genre)
    VALUES('$nama_genre')
    ");

    return mysqli_affected_rows($conn);
}

function ubahGenre($data){

    global $conn;

    $id_genre = $data["id_genre"];

    $nama_genre = htmlspecialchars(
        $data["nama_genre"]
    );

    mysqli_query($conn,"
    UPDATE genre SET
    nama_genre='$nama_genre'
    WHERE id_genre='$id_genre'
    ");

    return mysqli_affected_rows($conn);
}

function hapusGenre($id){

    global $conn;

    mysqli_query($conn,"
    DELETE FROM genre
    WHERE id_genre='$id'
    ");

    return mysqli_affected_rows($conn);
}
function upload(){

    $namaFile = $_FILES["gambar"]["name"];
    $tmpName = $_FILES["gambar"]["tmp_name"];
    $error = $_FILES["gambar"]["error"];

    if($error === 4){
        return false;
    }

    move_uploaded_file(
        $tmpName,
        "../img/" . $namaFile
    );

    return $namaFile;
}
/*buku */
function tambahBuku($data){

    global $conn;

    $gambar = upload();

    $genre = implode(", ", $data["genre"]);
    $judul = htmlspecialchars($data["judul"]);
    $penulis = htmlspecialchars($data["penulis"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    $tahun_terbit = $data["tahun_terbit"];
    $stok = intval($data["stok"]);

    mysqli_query($conn,"
    INSERT INTO buku
    (
        gambar,
        genre,
        judul,
        penulis,
        sinopsis,
        tahun_terbit,
        stok
    )
    VALUES
    (
        '$gambar',
        '$genre',
        '$judul',
        '$penulis',
        '$sinopsis',
        '$tahun_terbit',
        '$stok'
    )
    ");

    return mysqli_affected_rows($conn);
}
function ubahBuku($data){

    global $conn;

    $id_buku = $data["id_buku"];

    if($_FILES["gambar"]["error"] === 4){

        $gambar = $data["gambarLama"];

    } else {

        $gambar = upload();

    }

    $genre = implode(", ", $data["genre"]);
    $judul = htmlspecialchars($data["judul"]);
    $penulis = htmlspecialchars($data["penulis"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    $tahun_terbit = $data["tahun_terbit"];
    $stok = intval($data["stok"]);

    mysqli_query($conn,"
    UPDATE buku SET
        gambar='$gambar',
        genre='$genre',
        judul='$judul',
        penulis='$penulis',
        sinopsis='$sinopsis',
        tahun_terbit='$tahun_terbit',
        stok='$stok'
    WHERE id_buku='$id_buku'
    ");

    return mysqli_affected_rows($conn);
}

function hapus($id){

    global $conn;

    mysqli_query($conn,"
    DELETE FROM buku
    WHERE id_buku='$id'
    ");

    return mysqli_affected_rows($conn);
}

/* pinjaman */

function tambahPenyewaan($data){

    global $conn;

    $id_user = $data["id_user"];
    $judul = htmlspecialchars($data["judul"]);
    $tgl_pinjam = $data["tgl_pinjam"];
    $tgl_kembali = $data["tgl_kembali"];

    mysqli_query($conn,"
    INSERT INTO penyewaan
    (
        id_user,
        judul,
        tgl_pinjam,
        tgl_kembali
    )
    VALUES
    (
        '$id_user',
        '$judul',
        '$tgl_pinjam',
        '$tgl_kembali'
    )
    ");

    return mysqli_affected_rows($conn);
}

function ubahPenyewaan($data){

    global $conn;

    $id_penyewaan = $data["id_penyewaan"];

    $judul = htmlspecialchars($data["judul"]);
    $tgl_pinjam = $data["tgl_pinjam"];
    $tgl_kembali = $data["tgl_kembali"];
    $status = $data["status"];
    $denda = intval($data["denda"]);

    mysqli_query($conn,"
    UPDATE penyewaan SET
        judul='$judul',
        tgl_pinjam='$tgl_pinjam',
        tgl_kembali='$tgl_kembali',
        status='$status',
        denda='$denda'
    WHERE id_penyewaan='$id_penyewaan'
    ");

    return mysqli_affected_rows($conn);
}

function ubahStatus($id_penyewaan,$status){

    global $conn;

    mysqli_query($conn,"
    UPDATE penyewaan SET
    status='$status'
    WHERE id_penyewaan='$id_penyewaan'
    ");

    return mysqli_affected_rows($conn);
}
function ambilBuku($id_penyewaan){

    global $conn;

    $data = query("
    SELECT * FROM penyewaan
    WHERE id_penyewaan='$id_penyewaan'
    ")[0];

    $judul = $data["judul"];

    mysqli_query($conn,"
    UPDATE buku
    SET stok = stok + 1
    WHERE judul='$judul'
    ");
    mysqli_query($conn,"
    UPDATE penyewaan
    SET status='Dikembalikan'
    WHERE id_penyewaan='$id_penyewaan'
    ");
    return mysqli_affected_rows($conn);
}
function hapusPenyewaan($id){
    global $conn;
    mysqli_query($conn,"
    DELETE FROM penyewaan
    WHERE id_penyewaan='$id'
    ");
    return mysqli_affected_rows($conn);
}

?>
