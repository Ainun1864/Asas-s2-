<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';
global $conn;
$id_user = $_SESSION["id"];

// PROSES KEMBALIKAN
if(isset($_GET["kembalikan"])){
    $id = $_GET["kembalikan"];
    
    $data = query("SELECT * FROM penyewaan WHERE id_penyewaan='$id'")[0];
    $judul = $data["judul"];
    
    mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE judul='$judul'");
    mysqli_query($conn, "UPDATE penyewaan SET status='Dikembalikan' WHERE id_penyewaan='$id'");
    
    echo "<script>alert('Buku berhasil dikembalikan'); document.location.href='riwayat.php';</script>";
}

// PROSES BAYAR DENDA (TAMBAH KOLOM DULU KALO BELUM ADA)
if(isset($_GET["bayar"])){
    $id = $_GET["bayar"];
    
    // Cek dan tambah kolom jika belum ada
    $cek = mysqli_query($conn, "SHOW COLUMNS FROM penyewaan LIKE 'status_pembayaran'");
    if(mysqli_num_rows($cek) == 0){
        mysqli_query($conn, "ALTER TABLE penyewaan ADD COLUMN status_pembayaran VARCHAR(50) DEFAULT 'Belum Bayar'");
    }
    
    mysqli_query($conn, "UPDATE penyewaan SET status_pembayaran='Lunas' WHERE id_penyewaan='$id'");
    echo "<script>alert('Pembayaran lunas! Terima kasih.'); document.location.href='riwayat.php';</script>";
}

$data = query("SELECT * FROM penyewaan WHERE id_user='$id_user' ORDER BY id_penyewaan DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pinjaman</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="sidebar" id="sidebar">
    <button class="toggle-btn" onclick="toggleMenu()">☰</button>
    <div class="menu-sidebar">
        <a href="index.php"><span>⌂</span><span class="text">Daftar Buku</span></a>
        <a href="genre.php"><span>✧</span><span class="text">Genre</span></a>
        <a href="riwayat.php"><span>🗎</span><span class="text">Pinjaman</span></a>
    </div>
    <div class="sidebar-bottom">
        <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')"><span>𖦏</span><span class="text">Logout</span></a>
    </div>
</div>

<div class="hero">
    <div class="hero-text">
        <h1>Riwayat Pinjaman</h1>
        <p>Lihat status peminjaman dan pengembalian buku.</p>
    </div>
</div>

<div class="container">

<table class="produk-table">
    <tr>
        <th>ID</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
        <th>Denda</th>
        <th>Aksi</th>
    </tr>

<?php foreach($data as $d): 
$status_bayar = isset($d["status_pembayaran"]) ? $d["status_pembayaran"] : "Belum Bayar";
?>

    <tr>
        <td><?= $d["id_penyewaan"]; ?></td>
        <td><?= $d["judul"]; ?></td>
        <td><?= $d["tgl_pinjam"]; ?></td>
        <td><?= $d["tgl_kembali"]; ?></td>
        <td>
            <?php if($d["status"] == "Menunggu") : ?>
                <span class="status-menunggu">Menunggu</span>
            <?php elseif($d["status"] == "Disetujui") : ?>
                <span class="status-disetujui">Disetujui</span>
            <?php elseif($d["status"] == "Dikembalikan") : ?>
                <span class="status-dikembalikan">Dikembalikan</span>
            <?php else : ?>
                <span class="status-terlambat">Terlambat</span>
            <?php endif; ?>
        </td>
        <td>Rp <?= number_format($d["denda"]); ?></td>
        <td>
            <?php if($d["status"] == "Disetujui") : ?>
                <a href="?kembalikan=<?= $d["id_penyewaan"]; ?>" class="btn-kembali" onclick="return confirm('Kembalikan buku?')">Kembalikan</a>

            <?php elseif($d["status"] == "Menunggu") : ?>
                <span>Menunggu Persetujuan</span>

            <?php elseif($d["status"] == "Dikembalikan" && $d["denda"] > 0 && $status_bayar == "Belum Bayar") : ?>
                <a href="?bayar=<?= $d["id_penyewaan"]; ?>" class="btn-beli" onclick="return confirm('Bayar denda Rp <?= number_format($d["denda"]); ?>?')">Bayar Denda</a>

            <?php elseif($d["status"] == "Dikembalikan" && $d["denda"] > 0 && $status_bayar == "Lunas") : ?>
                <span style="color:green;">Lunas ✓</span>

            <?php else : ?>
                <span>Selesai</span>
            <?php endif; ?>
        </td>
    </tr>

<?php endforeach; ?>

</table>

</div>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>

</body>
</html>