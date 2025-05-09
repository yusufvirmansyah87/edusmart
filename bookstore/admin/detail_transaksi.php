
<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

$id_pesanan = (int)$_GET['id'];

// Ambil data pesanan + bukti transfer
$query_pesanan = mysqli_query($conn, "
    SELECT tb_pesanan.*, tb_user.username, tb_user.nama_user 
    FROM tb_pesanan 
    JOIN tb_user ON tb_pesanan.id_user = tb_user.id_user
    WHERE tb_pesanan.id_pesanan = $id_pesanan
");
$pesanan = mysqli_fetch_assoc($query_pesanan);

// Ambil item pesanan
$query_items = mysqli_query($conn, "
    SELECT tb_detail_pesanan.*, tb_buku.judul_buku, tb_buku.foto
    FROM tb_detail_pesanan
    JOIN tb_buku ON tb_detail_pesanan.id_buku = tb_buku.id_buku
    WHERE tb_detail_pesanan.id_pesanan = $id_pesanan
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "title.php"; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

</head>
<body class="d-flex flex-column h-100">
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>
            <!-- Content -->
            <div class="col-md-9">
                <h3>Detail Transaksi</h3>
                <hr style="border-top: 1px dashed #000;" />
                <!-- Informasi Utama Pesanan -->
                    <div class="info-section">
                        <h4>Informasi Pesanan</h4>
                        <p><strong>Pelanggan:</strong> <?= $pesanan['nama_user'] ?></p>
                        <p><strong>Tanggal Pesan:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
                        <p><strong>Status:</strong>
                            <span class="<?=
                                            ($pesanan['status'] == 'menunggu_verifikasi') ? 'status-pending' : 'status-verified'
                                            ?>">
                                <?= ucfirst(str_replace('_', ' ', $pesanan['status'])) ?>
                            </span>
                        </p>
                        <p><strong>Total:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></p>
                        <p><strong>Alamat Pengiriman:</strong><br><?= nl2br($pesanan['alamat']) ?></p>
                    </div>

                    <!-- Bukti Transfer -->
                    <div class="info-section">
                        <h4>Bukti Transfer</h4>
                        <?php if ($pesanan['bukti_transfer']): ?>
                            <img src="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" alt="Bukti Transfer" class="bukti-transfer">
                            <p>
                                <a href="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" download>Download Bukti</a>

                            </p>
                        <?php else: ?>
                            <p>Belum mengupload bukti transfer.</p>
                        <?php endif; ?>
                    </div>
                    <hr style="border-top: 1px dashed #000;" />

                    <!-- Daftar Item Pesanan -->
                    <div class="info-section">
                        <h4>Item Pesanan</h4>
                        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                            <thead style="background-color:rgb(121, 121, 121); color: white;">
                                <tr align="center">
                                    <th>Produk</th>
                                    <th>Gambar</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <?php while ($item = mysqli_fetch_assoc($query_items)): ?>
                            <tbody>
                                <tr>
                                    <td><?= $item['judul_buku'] ?></td>
                                    <td align="center"><img src="foto_produk/<?= $item['foto'] ?>" width="50"></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td align="center"><?= $item['jumlah'] ?> Eks</td>
                                    <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr style="border-top: 1px dashed #000;" />

                    <a href="transaksi.php">&laquo; Kembali ke Halaman Transaksi</a>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
    
    
    <!-- Js Bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
