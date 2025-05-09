<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['level_akses'] !== 'Customer'; // Misal user_id adalah session login user


$id_buku = isset($_GET['id_buku']) ? (int)$_GET['id_buku'] : 0;
$id_user = (int)$_SESSION['user_id'];

// Validasi ID Buku
if ($id_buku <= 0) {
    echo "<script>alert('ID buku tidak valid!'); window.location.href = 'index.php';</script>";
    exit;
}

// Cek apakah produk sudah ada di keranjang
$check = mysqli_query($conn, "SELECT * FROM tb_keranjang WHERE id_user = $id_user AND id_buku = $id_buku");

if (mysqli_num_rows($check) > 0) {
    $update = mysqli_query($conn, "UPDATE tb_keranjang SET jumlah = jumlah + 1 WHERE id_user = $id_user AND id_buku = $id_buku");
} else {
    $insert = mysqli_query($conn, "INSERT INTO tb_keranjang (id_user, id_buku, jumlah) VALUES ($id_user, $id_buku, 1)");
}

// Redirect ke index.php dengan pesan sukses
echo "<script>alert('Produk ditambahkan ke keranjang!'); window.location.href = 'index.php';</script>";
