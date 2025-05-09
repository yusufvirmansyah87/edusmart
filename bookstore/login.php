<?php
include "koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['level_akses'] = $user['level_akses'];

        if ($user['level_akses'] == 'Customer') {
            header("Location: index.php");
        } elseif ($user['level_akses'] == 'Admin') {
            header("Location: admin/home.php");
        } else {
            echo "Level akses tidak dikenali.";
            exit;
        }

        exit;
    } else {
        echo "Login gagal! Username atau password salah.";
    }
}
?>
