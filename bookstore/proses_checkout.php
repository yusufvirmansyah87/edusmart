<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['level_akses'] !== 'Customer'; // Misal user_id adalah session login user


// Proses Upload Bukti Transfer
$upload_dir = "bukti_transfer/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$bukti_transfer = $_FILES['bukti_transfer'];
$ext = pathinfo($bukti_transfer['name'], PATHINFO_EXTENSION);
$filename = "TRF-" . $_SESSION['user_id'] . "-" . time() . "." . $ext;
$target_file = $upload_dir . $filename;

// Validasi File
$allowed_ext = ['jpg', 'jpeg', 'png'];
if (!in_array(strtolower($ext), $allowed_ext)) {
    die("Hanya file JPG/PNG yang diizinkan!");
}

if (move_uploaded_file($bukti_transfer['tmp_name'], $target_file)) {
    // Hitung total dari keranjang
    $total = 0;
    $id_user = $_SESSION['user_id'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query_keranjang = mysqli_query($conn, "
        SELECT tb_buku.harga, tb_keranjang.jumlah 
        FROM tb_keranjang 
        JOIN tb_buku ON tb_keranjang.id_buku = tb_buku.id_buku 
        WHERE tb_keranjang.id_user = $id_user
    ");

    while ($row = mysqli_fetch_assoc($query_keranjang)) {
        $total += $row['harga'] * $row['jumlah'];
    }

    // 1. Simpan ke tabel pesanan (DENGAN bukti transfer)
    $query = "INSERT INTO tb_pesanan (
        id_user, alamat, metode_pembayaran, bukti_transfer, total, status
    ) VALUES (
        $id_user, 
        '$alamat', 
        'transfer_bank', 
        '$filename', 
        $total, 
        'pending' 
    )";

    mysqli_query($conn, $query);
    $id_pesanan = mysqli_insert_id($conn);

    // 2. Pindahkan item keranjang ke detail_pesanan
    mysqli_query($conn, "
        INSERT INTO tb_detail_pesanan (id_pesanan, id_buku, jumlah, harga)
        SELECT $id_pesanan, id_buku, jumlah, (SELECT harga FROM tb_buku WHERE id_buku = tb_keranjang.id_buku)
        FROM tb_keranjang 
        WHERE id_user = $id_user
    ");

    // 3. Kosongkan keranjang
    mysqli_query($conn, "DELETE FROM tb_keranjang WHERE id_user = $id_user");

    // 4. Redirect ke halaman konfirmasi
    header("Location: konfirmasi.php?id_pesanan=$id_pesanan");
    exit;
} else {
    die("Gagal upload bukti transfer!");
}
