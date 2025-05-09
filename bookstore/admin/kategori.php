<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

// penenganan hapus data 
if(isset($_GET['delete_id'])) {
    $id_kategori = $_GET['delete_id'];
        $query_delete = "DELETE FROM tb_kategori WHERE id_kategori = '$id_kategori'";
        if(mysqli_query($conn, $query_delete)) {
            
            echo "<script>alert('Kategori berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Gagal menghapus Kategori!');</script>";
        }
    }

// Menangani data POST
if (isset($_POST['SIMPAN'])) {
    $nama_kategori =$_POST['nama_kategori'];
   
            $query = "INSERT INTO tb_kategori (nama_kategori) values ('$nama_kategori')";
            if(mysqli_query($conn, $query)) {
                echo "Kategori Baru Berhasil Disimpan";
                header("location:" .$_SERVER['PHP_SELF']);
                exit();
            }else{
                echo "Error:" .mysqli_error($conn);
            }
    }

$datakategori="SELECT * FROM tb_kategori ORDER BY id_Kategori DESC";
$resultkategori = $conn->query($datakategori);
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
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control" name="nama_kategori" placeholder="Input Nama Kategori" required>
            </div>
            <br>
            <div class="form-group">
            <input type="submit" class="btn btn-primary" name="SIMPAN" value="Simpan">
            </div>
        </from>
        
        <br><hr><br>

        <h4 class="text-center" >Data Kategori</h1>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
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
                                            echo '<a href="?delete_id=' . $kategori['id_kategori'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus Kategori ini?\')">Hapus</a> &nbsp;';
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