<?php
session_start(); // Pastikan session dimulai

include "koneksi.php";

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['level_akses'] !== 'Customer'; // Misal user_id adalah session login user

// Ambil data keranjang
$query = mysqli_query($conn, "
    SELECT tb_buku.judul_buku, tb_buku.harga, tb_keranjang.jumlah 
    FROM tb_keranjang 
    JOIN tb_buku ON tb_keranjang.id_buku = tb_buku.id_buku
    WHERE tb_keranjang.id_user = {$_SESSION['user_id']}
");

$total = 0;
$item_count = mysqli_num_rows($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "title.php"; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <style>
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

<script>
        function validateCheckout() {
            <?php if ($item_count == 0): ?>
                alert("Keranjang kosong! Tambahkan produk terlebih dahulu.");
                window.location.href = "index.php";
                return false;
            <?php else: ?>
                return true;
            <?php endif; ?>
        }
    </script>

</head>
<body  class="d-flex flex-column h-100">
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div class="container-fluid">
        <div class="row">            
             <!-- Kategori Produk -->
                <div class="col-md-3 mb-4">
                    <h4>Kategori Produk</h4>
                    <ul class="list-group mb-4">
                        <!-- Tambahkan opsi All Produk di sini -->
                        <li class="list-group-item"><a href="index.php">All Kategori</a></li>
                        <?php foreach($kategori_list as $kategori): ?>
                            <li class="list-group-item">
                            <a href="index.php?kategori=<?= $kategori['id_kategori']; ?>"><?= $kategori['nama_kategori']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>


            <!-- Konten Produk buku -->
            <div class="col-md-9">
                <h4>Keranjang Belanja Anda</h4>
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color: #4CAF50; color: white;">
                            <tr>
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $total = 0;
                            while ($row = mysqli_fetch_assoc($query)): 
                                $subtotal = $row['harga'] * $row['jumlah'];
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['jumlah']; ?></td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <tr style="font-weight: bold;">
                                <td colspan="4" style="text-align: right;">Total</td>
                                <td>Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top: 20px; text-align: right;">
                        <a href="checkout.php" onclick="return validateCheckout()" style="background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none;">Checkout</a>
                    </div>

                    <script>
                    function validateCheckout() {
                        if (confirm("Apakah Anda yakin ingin melanjutkan ke checkout?")) {
                            return true;
                        }
                        return false;
                    }
                    </script>

            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>