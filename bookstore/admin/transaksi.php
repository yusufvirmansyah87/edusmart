<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

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
                <h4>Data Transaksi</h4>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php
                        $query_pesanan = "SELECT tb_pesanan.*, tb_user.nama_user
                                    FROM tb_pesanan 
                                    JOIN tb_user ON tb_pesanan.id_user = tb_user.id_user
                                    ORDER BY tb_pesanan.created_at DESC";
                        $result_pesanan = mysqli_query($conn, $query_pesanan);

                        if (mysqli_num_rows($result_pesanan) > 0) :
                            while ($row = mysqli_fetch_assoc($result_pesanan)) :
                        ?>
                            <tbody>
                                <tr>
                                    <td><?= $row['id_pesanan'] ?></td>
                                    <td><?= $row['nama_user'] ?></td>
                                    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <form action="update_status.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : '' ?>>pending</option>
                                                <option value="diproses" <?= ($row['status'] == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                                                <option value="dikirim" <?= ($row['status'] == 'dikirim') ? 'selected' : '' ?>>Dikirim</option>
                                                <option value="selesai" <?= ($row['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <a href="detail_transaksi.php?id=<?= $row['id_pesanan'] ?>">Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Tidak ada pesanan.</td>
                            </tr>
                        <?php endif; ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
    
    
    <!-- Js Bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
