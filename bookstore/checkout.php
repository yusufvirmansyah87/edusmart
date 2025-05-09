<?php
session_start(); // Pastikan session dimulai

include "koneksi.php";

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['level_akses'] !== 'Customer'; // Misal user_id adalah session login user

// Ambil data user
$user_query = mysqli_query($conn, "SELECT * FROM tb_user WHERE id_user = {$_SESSION['user_id']}");
$user_data = mysqli_fetch_assoc($user_query);

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
                <h3>Checkout</h3>
                <div class="row mt-4">
                    <!-- Form Checkout -->
                    <form action="proses_checkout.php" method="POST" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Nama Pemesan</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($user_data['nama_user'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>No. Tlpn/HP</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($user_data['no_telpon'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($user_data['alamat'] ?? '') ?></td>
                            </tr>
                        </table>
                        <hr style="border-top: 1px dashed #000;" />
                            <!-- Tampung Data Pengiriman -->
                            <input type="hidden" name="nama_lengkap" value="<?= htmlspecialchars($user_data['nama_user'] ?? '') ?>">
                            <input type="hidden" name="no_telpon" value="<?= htmlspecialchars($user_data['no_telpon'] ?? '') ?>">
                            <input type="hidden" name="alamat" rows="3" value="<?= htmlspecialchars($user_data['alamat'] ?? '') ?>" >
                            <!-- End Tampung Data Pengiriman -->
                        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color:rgb(147, 156, 147); color: white;">
                            <tr align="center">
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Harga</th>
                                <th>Qty</th>
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
                                <td align="center"><?php echo $no++; ?></td>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td align="center"><?php echo $row['jumlah']; ?> Eks.</td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <tr style="font-weight: bold;">
                                <td colspan="4" style="text-align: right;">Total</td>
                                <td>Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                        <hr style="border-top: 1px dashed #000;" />

                            <!-- Metode Pembayaran (Hanya Transfer Bank) -->
                            <input type="hidden" name="metode_pembayaran" value="transfer_bank">

                            <!-- Instruksi Transfer Bank -->
                            <div class="bank-info">
                                <h3>Instruksi Pembayaran</h3>
                                <p>Silakan transfer ke rekening berikut:</p>
                                <p><strong>Bank CBA</strong></p>
                                <p>Nomor Rekening: <strong>1234 5678 9012</strong></p>
                                <p>Atas Nama: <strong>BookStore P4 Jakarta Timur</strong></p>
                                <p>Jumlah: <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
                                <p>Kode Referensi: <strong>ORDER-<?= time() ?></strong></p>
                            </div>

                            <!-- Upload Bukti Transfer -->
                            <div class="form-group">
                                <label>Upload Bukti Transfer (Format: JPG/PNG, max 2MB)</label>
                                <input type="file" name="bukti_transfer" accept="image/jpeg, image/png" required>
                            </div>

                            <button type="submit" class="btn-submit">Konfirmasi Pesanan</button>
                        </form>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>