<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

$datapenerbit="SELECT * FROM tb_penerbit ORDER BY id_penerbit DESC";
$resultpenerbit = $conn->query($datapenerbit);

$datakategori="SELECT * FROM tb_kategori ORDER BY id_Kategori DESC";
$resultkategori = $conn->query($datakategori);

// Query untuk menampilkan data buku dengan kategori dan penerbit
$sql = "SELECT 
b.id_buku,
k.nama_kategori,
b.judul_buku,
b.pengarang,
p.nama_penerbit,
b.tahun,
b.harga,
b.foto,
b.stok 
FROM 
    tb_buku b 
JOIN 
    tb_kategori k ON b.id_kategori = k.id_kategori 
JOIN 
    tb_penerbit p ON b.id_penerbit = p.id_penerbit";

$stmt = $conn->prepare($sql);
$stmt->execute();
$resultbuku = $stmt->get_result();
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
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>
            <!-- Content -->
            <div class="col-md-9">
                <h2>Selamat Datang Di Halaman Admin Bookstore</h2>

                <h4 class="text-center" >Data Penerbit</h1>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerbit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($penebit = $resultpenerbit->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . $penebit['nama_penerbit'] . '</td>';
                                echo '<td>';
                                            echo '<a href="edit-penerbit.php?id_penerbit=' . $penebit['id_penerbit'] . '" class="btn btn-primary">Edit</a> &nbsp';
                                            echo '<a href="delete-penerbit.php?id_penerbit=' . $penebit['id_penerbit'] . '" class="btn btn-warning" onclick="return confirm("Yakin ingin hapus produk ini?")>Hapus</a> &nbsp';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                            <hr>
                <h4 class="text-center" >Data Kategori</h1>
                <div class="table-responsive">
                    <table id="myTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($kategori = $resultkategori->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . $kategori['nama_kategori'] . '</td>';
                                echo '<td>';
                                            echo '<a href="edit-kategori.php?id_kategori=' . $kategori['id_kategori'] . '" class="btn btn-primary">Edit</a> &nbsp';
                                            echo '<a href="delete-kategori.php?id_kategori=' . $kategori['id_kategori'] . '" class="btn btn-warning" onclick="return confirm("Yakin ingin hapus produk ini?")>Hapus</a> &nbsp';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <hr>
                <h4 class="text-center" >Data Produk Buku</h1>
                        <div class="table-responsive">
                            <table id="myTable3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Judul Buku</th>
                                        <th>Pengarang</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Terbit</th>
                                        <th>Harga</th>
                                        <th>Foto</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($produk = $resultbuku->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $no++ . '</td>';
                                        echo '<td>' . $produk['nama_kategori'] . '</td>';
                                        echo '<td>' . $produk['judul_buku'] . '</td>';
                                        echo '<td>' . $produk['pengarang'] . '</td>';
                                        echo '<td>' . $produk['nama_penerbit'] . '</td>';
                                        echo '<td>' . $produk['tahun'] . '</td>';
                                        echo '<td>Rp ' . number_format($produk['harga'], 0, ',', '.'). '</td>';
                                        echo '<td><img src="foto_produk/'. $produk['foto'] . '" width="50%"/></td>';
                                        echo '<td>' . $produk['stok'] . '</td>';
                                        echo '<td>';
                                                echo '<a href="edit-produk.php?id_buku=' . $produk['id_buku'] . '" class="btn btn-primary">Edit</a> &nbsp';
                                                echo '<a href="?delete_id=' . $produk['id_buku'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus barang ini?\')">Hapus</a> &nbsp;';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>