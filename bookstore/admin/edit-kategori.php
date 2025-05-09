<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id_kategori'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = $_GET['id_kategori'];
$query = "SELECT * FROM tb_kategori WHERE id_kategori = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek jika data tidak ditemukan
if (!$data) {
    echo "Produk tidak ditemukan!";
    exit;
}

// Menangani data POST
if (isset($_POST['UPDATE'])) {
    $id =$_POST['id_kategori'];
    $nama_kategori =$_POST['nama_kategori'];
   
            $queryupdate = "UPDATE tb_kategori SET nama_kategori='$nama_kategori' WHERE id_kategori='$id'";
            if (mysqli_query($conn, $queryupdate)) {
                echo "Data Kategori berhasil diupdate!";
                header("Location: kategori.php"); // Redirect kembali ke halaman index setelah update
            } else {
                echo "Error: " . mysqli_error($conn);
            }
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
            <h4 class="text-center" >Manajemen Kategori</h1>
        <form action="" method="POST">
            <input type="hidden" name="id_kategori" value="<?php echo $data['id_kategori']; ?>" />
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control" name="nama_kategori" value="<?php echo $data['nama_kategori']; ?>" >
            </div>
            <br>
            <div class="form-group">
            <input type="submit" class="btn btn-primary" name="UPDATE" value="Update">
            <a href="Kategori.php"><input type="button" class="btn btn-secondary"  value="Kembali"></a>
            </div>
    </from>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>