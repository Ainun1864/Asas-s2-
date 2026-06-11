<?php
session_start();

if($_SESSION["role"] != "admin"){
    header("Location: ../login_user.php");
    exit;
}

require '../functions.php';

if(hapus($_GET["id"]) > 0){

    echo "
    <script>
        alert('Data berhasil dihapus');
        document.location.href='index.php';
    </script>
    ";
}
?>