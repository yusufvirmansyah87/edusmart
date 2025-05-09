<?php
session_start();
include "../koneksi.php";

$id_pesanan = (int)$_POST['id_pesanan'];
$status = mysqli_real_escape_string($conn, $_POST['status']);

mysqli_query($conn, "
    UPDATE tb_pesanan 
    SET status = '$status' 
    WHERE id_pesanan = $id_pesanan
");
header("Location: transaksi.php");  // Kembali ke halaman admin
