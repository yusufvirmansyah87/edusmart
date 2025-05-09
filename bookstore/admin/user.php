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
    $id_user = $_GET['delete_id'];
        $query_delete = "DELETE FROM tb_user WHERE id_user = '$id_user'";
        if(mysqli_query($conn, $query_delete)) {
            
            echo "<script>alert('User berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Gagal menghapus User!');</script>";
        }
    }

$datauser="SELECT * FROM tb_user ORDER BY id_user DESC";
$resultuser = $conn->query($datauser);
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
            <h4 class="text-center" >Manajemen User</h1>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama User</th>
                                <th>Level Akses</th>
                                <th>Alamat</th>
                                <th>Telpon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($user = $resultuser->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . $user['username'] . '</td>';
                                echo '<td>' . $user['nama_user'] . '</td>';
                                echo '<td>' . $user['level_akses'] . '</td>';
                                echo '<td>' . $user['alamat'] . '</td>';
                                echo '<td>' . $user['no_telpon'] . '</td>';
                                echo '<td>';
                                            echo '<a href="?delete_id=' . $user['id_user'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus user ini?\')">Hapus</a> &nbsp;';
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