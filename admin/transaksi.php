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
global $conn;
if(isset($_GET["setuju"])){
    $id = $_GET["setuju"];
    ubahStatus($id,"Disetujui");
    echo "<script>alert('Peminjaman disetujui'); document.location.href='transaksi.php';</script>";
}

if(isset($_GET["ambil"])){
    $id = $_GET["ambil"];
    ambilBuku($id);
    echo "<script>alert('Buku berhasil diterima kembali'); document.location.href='transaksi.php';</script>";
}

if(isset($_POST["update_denda"])){
    $id = $_POST["id_penyewaan"];
    $denda_baru = $_POST["denda_baru"];
    mysqli_query($conn, "UPDATE penyewaan SET denda='$denda_baru' WHERE id_penyewaan='$id'");
    echo "<script>alert('Denda diupdate'); document.location.href='transaksi.php';</script>";
}

if(isset($_GET["konfirmasi_bayar"])){
    $id = $_GET["konfirmasi_bayar"];
    mysqli_query($conn, "UPDATE penyewaan SET status_pembayaran='Lunas' WHERE id_penyewaan='$id'");
    echo "<script>alert('Pembayaran dikonfirmasi'); document.location.href='transaksi.php';</script>";
}

$data = query("SELECT penyewaan.*, users.nama FROM penyewaan JOIN users ON penyewaan.id_user = users.id ORDER BY id_penyewaan DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penyewaan</title>
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
<div class="transaksi-header">
    <h1>Data Penyewaan Buku</h1>
    <p>Kelola peminjaman dan pengembalian buku pengguna</p>
</div>

<table class="produk-table">
    <tr>
        <th>ID</th>
        <th>Nama User</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
        <th>Denda</th>
        <th>Status Bayar</th>
        <th>Aksi</th>
    </tr>

<?php foreach($data as $d): ?>
    <tr>
        <td><?= $d["id_penyewaan"]; ?></td>
        <td><?= $d["nama"]; ?></td>
        <td><?= $d["judul"]; ?></td>
        <td><?= $d["tgl_pinjam"]; ?></td>
        <td><?= $d["tgl_kembali"]; ?></td>
        <td>
            <?php if($d["status"] == "Menunggu"): ?>
                <span class="status-menunggu">Menunggu</span>
            <?php elseif($d["status"] == "Disetujui"): ?>
                <span class="status-disetujui">Disetujui</span>
            <?php elseif($d["status"] == "Dikembalikan"): ?>
                <span class="status-dikembalikan">Dikembalikan</span>
            <?php else: ?>
                <span class="status-terlambat">Terlambat</span>
            <?php endif; ?>
        </td>
        <td>
            Rp <?= number_format($d["denda"]); ?>
            <button onclick="editDenda(<?= $d['id_penyewaan']; ?>, <?= $d['denda']; ?>)" style="padding:2px 8px;">Edit</button>
        </td>
        <td>
            <?php 
            $sb = isset($d["status_pembayaran"]) ? $d["status_pembayaran"] : "Belum Bayar";
            if($sb == "Belum Bayar"){
                echo '<span class="status-menunggu">Belum Bayar</span>';
            }elseif($sb == "Menunggu Konfirmasi"){
                echo '<span class="status-disetujui">Menunggu Konfirmasi</span>';
            }else{
                echo '<span class="status-dikembalikan">Lunas</span>';
            }
            ?>
        </td>
        <td>
            <?php if($d["status"] == "Menunggu"): ?>
                <a href="?setuju=<?= $d["id_penyewaan"]; ?>" class="btn-setuju">Setujui</a>
            <?php elseif($d["status"] == "Disetujui"): ?>
                <a href="?ambil=<?= $d["id_penyewaan"]; ?>" class="btn-kembali">Ambil Buku</a>
            <?php elseif($sb == "Menunggu Konfirmasi" && $d["denda"] > 0): ?>
                <a href="?konfirmasi_bayar=<?= $d["id_penyewaan"]; ?>" class="btn-setuju">Konfirmasi Bayar</a>
            <?php else: ?>
                Selesai
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

</table>
</div>

<script>
function editDenda(id, denda){
    let baru = prompt("Masukkan nominal denda baru:", denda);
    if(baru !== null){
        let form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input name="id_penyewaan" value="'+id+'"><input name="denda_baru" value="'+baru+'"><input name="update_denda">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

</body>
</html>