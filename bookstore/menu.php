<?php
include "koneksi.php";

// Pastikan user sudah login dan hitung jumlah item di keranjang
$jumlah_item_keranjang = 0;
if (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];
    // Query untuk menghitung jumlah item di keranjang
    $result_keranjang = mysqli_query($conn, "SELECT SUM(jumlah) AS total_item FROM tb_keranjang WHERE id_user = $id_user");
    $keranjang = mysqli_fetch_assoc($result_keranjang);
    $jumlah_item_keranjang = $keranjang['total_item'] ?: 0; // Jika tidak ada item, set 0
}

$where_clause = "";
$buku_list = []; // Ini baris yang ditambahkan

if (isset($_GET['kategori'])) {
    $kategori_id = mysqli_real_escape_string($conn, $_GET['kategori']);
    $where_clause = "WHERE tb_buku.id_kategori = $kategori_id";
}

// Ambil semua produk buku dari database dengan filter kategori (jika ada)
$query_buku = "SELECT * FROM tb_buku $where_clause";
$result_buku = mysqli_query($conn, $query_buku);

while ($row_buku = mysqli_fetch_assoc($result_buku)) {
    $buku_list[] = $row_buku;
}

// Ambil semua kategori dari database
$query_kategori = "SELECT * FROM tb_kategori";
$result_kategori = mysqli_query($conn, $query_kategori);

$kategori_list = [];
while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
    $kategori_list[] = $row_kategori;
}

?>

<!-- menu.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Menu untuk mobile -->
        <ul class="navbar-nav ml-auto d-lg-none">
            <li class="nav-item">
                <a class="nav-link" href="index.php">All Kategori</a>
            </li>
            <li class="nav-item">
            <?php foreach($kategori_list as $kategori): ?>
                <a class="nav-link" href="index.php?kategori=<?= $kategori['id_kategori']; ?>"><?= $kategori['nama_kategori']; ?></a>
                <?php endforeach; ?>
            </li>
            <?php if ($_SESSION): ?>
            <li class="nav-item">
                <a href="keranjang.php" class="nav-link">Keranjang Belanja (<?= $jumlah_item_keranjang; ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <?php else: ?>
                <li class="nav-item">
                    <a href="" class="nav-link" data-toggle="modal" data-target="#loginModal">Login</a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Menu untuk desktop -->
        <ul class="navbar-nav ml-auto d-none d-lg-flex">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <?php if ($_SESSION): ?>
                <li class="nav-item">
                    <a href="keranjang.php" class="nav-link">Keranjang Belanja (<?= $jumlah_item_keranjang; ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a href="" class="nav-link" data-toggle="modal" data-target="#loginModal">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Modal Form Login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <div class="text-center mt-3">
                    <p>Belum punya akun? 
                        <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Register -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="sername">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Wajib Huruf Kecil tanpa spasi" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" name="password" placeholder="Kombinasi Huruf dan Angka Minimal 8 Karakter" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_user">Nama User</label>
                        <input type="text" class="form-control" name="nama_user" placeholder="Nama Sesuai KTP" required>
                    </div>
                        <input type="hidden" class="form-control" name="level_akses" value="Customer">
                    
                    <div class="form-group">
                        <label for="no_telpon">Nomor Telpon/WA</label>
                        <input type="text" class="form-control" name="no_telpon" placeholder="6285210786574" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" name="alamat" placeholder="Isi Alamat Lengkap RT/RW/Kelurahan/Kode POS" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>