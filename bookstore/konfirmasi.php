<?php
session_start(); // Pastikan session dimulai

include "koneksi.php";
// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['level_akses'] !== 'Customer'; // Misal user_id adalah session login user


$id_pesanan = (int)$_GET['id_pesanan'];
$query = mysqli_query($conn, "
    SELECT * FROM tb_pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = {$_SESSION['user_id']}
");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "title.php"; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .content {
            max-width: 600px;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
        }

        hr {
            border-top: 1px dashed #000;
        }
        
        /* CSS untuk tampilan mobile-only */
        @media (max-width: 767px) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }

        /* CSS untuk tampilan desktop-only */
        @media (min-width: 768px) {
            .mobile-only {
                display: none;
            }

            .desktop-only {
                display: block;
            }
        }
        
    </style>
</head>
<body  class="d-flex flex-column h-100">
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div class="container">
    <div class="content">
        <h3>Pesanan Berhasil Dibuat!</h3>
        <table>
            <tr><td>Order ID</td><td>:</td><td><?php echo $pesanan['id_pesanan']; ?></td></tr>
            <tr><td>Total</td><td>:</td><td>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td></tr>
            <tr><td>Status Pembayaran</td><td>:</td><td><?php echo ucfirst(str_replace('_', ' ', $pesanan['status'])); ?></td></tr>
            <tr><td>Metode Pembayaran</td><td>:</td><td><?php echo strtoupper($pesanan['metode_pembayaran']); ?></td></tr>
        </table>
        <hr />
        <?php if ($pesanan['metode_pembayaran'] == 'transfer_bank'): ?>
            <div>
                <h3>Instruksi Pembayaran</h3>
                <p>Transfer ke: BANK CBA (1234567890)</p>
                <p>Jumlah: Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></p>
                <p>Kode Referensi: ORDER-<?php echo $pesanan['id_pesanan']; ?></p>
            </div>
        <?php endif; ?>
        <hr />
        <a href="index.php" style="display: inline-block; margin-top: 20px;">Kembali ke Beranda</a>
    </div>
    </div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>