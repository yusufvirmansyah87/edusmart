<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_user = $_POST['nama_user'];
    $level_akses = $_POST['level_akses'];
    $no_telpon = $_POST['no_telpon'];
    $alamat = $_POST['alamat'];

    $cek = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO tb_user (username, password, nama_user, level_akses, no_telpon, alamat) 
                  VALUES ('$username', '$password', '$nama_user', '$level_akses', '$no_telepon', '$alamat')";
        if (mysqli_query($conn, $query)) {
            echo "Registrasi berhasil!";
            header("Location: index.php"); // Redirect kembali ke halaman index setelah register
        } else {
            echo "Registrasi gagal!";
        }
    }
}