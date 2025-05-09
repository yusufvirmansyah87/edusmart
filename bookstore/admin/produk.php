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
    $id_buku = $_GET['delete_id'];

    // ambil nama foto buku dari tabel untuk dihapus dari server
    $query = "SELECT foto FROM tb_buku WHERE id_buku = '$id_buku'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $foto = $data['foto'];

        // Hapus data buku dari tabel
        $query_delete = "DELETE FROM tb_buku WHERE id_buku = '$id_buku'";
        if(mysqli_query($conn, $query_delete)) {
            
            // Hapus foto dari folder
            if(!empty($foto) && file_exists('foto_produk/' . $foto)) {
                unlink('foto_produk/' . $foto);
            }

            echo "<script>alert('Barang berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Gagal menghapus barang!');</script>";
        }
    }
}

// ambil data Kategori
$queryGetKategori = "SELECT id_kategori, nama_kategori FROM tb_kategori ORDER BY id_kategori DESC";
$resultKategori = $conn->query($queryGetKategori);

// ambil data Penerbit
$queryGetPenerbit = "SELECT id_penerbit, nama_penerbit FROM tb_penerbit ORDER BY id_penerbit DESC";
$resultPenerbit = $conn->query($queryGetPenerbit);

// Menangani data POST
if (isset($_POST['SIMPAN'])) {
    $id_kategori =$_POST['id_kategori'];
    $judul_buku =$_POST['judul_buku'];
    $pengarang =$_POST['pengarang'];
    $id_penerbit =$_POST['id_penerbit'];
    $tahun =$_POST['tahun'];
    $harga =$_POST['harga'];
    $stok =$_POST['stok'];

    //upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = "foto_produk/";

    if(!file_exists($upload_dir)){
        mkdir($upload_dir, 0777, true);
    }

    $gambar_path = $upload_dir . basename($gambar);

        if(move_uploaded_file($tmp_name, $gambar_path)) {
            $query = "INSERT INTO tb_buku (id_kategori, judul_buku, pengarang, id_penerbit, tahun, harga, foto, stok)
            values ('$id_kategori','$judul_buku','$pengarang','$id_penerbit','$tahun','$harga', '$gambar', '$stok')";
            if(mysqli_query($conn, $query)) {
                echo "Produk Baru Berhasil Disimpan";
                header("location:" .$_SERVER['PHP_SELF']);
                exit();
            }else{
                echo "Error:" .mysqli_error($conn);
            }
        }else{
        echo "Gagal Upload Gambar";
        }

    }

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
                <h4 class="text-center" >Tambah Produk Buku</h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="id_kategori">Pilih Kategori:</label>
                            <select class="form-control" id="id_kategori" name="id_kategori">
                                <?php
                                while ($row = $resultKategori->fetch_assoc()) {
                                    echo "<option value='" . $row['id_kategori'] . "'>" . $row['nama_kategori'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="judul_buku">Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" placeholder="Input Judul Buku" required>
                        </div>
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" placeholder="Input Pengarang" required>
                        </div>
                        <div class="form-group">
                            <label for="id_penerbit">Pilih Penerbit:</label>
                            <select class="form-control" id="id_penerbit" name="id_penerbit">
                                <?php
                                while ($row2 = $resultPenerbit->fetch_assoc()) {
                                    echo "<option value='" . $row2['id_penerbit'] . "'>" . $row2['nama_penerbit'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun Terbit</label>
                            <input type="number" class="form-control" name="tahun" placeholder="contoh : 2025" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" name="harga" placeholder="contoh : 5000" required>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Pilih Gambar Produk...</label>
                            <input type="file" class="form-control" name="gambar" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Stok</label>
                            <input type="number" class="form-control" name="stok" placeholder="contoh : 10" required>
                        </div><br>
                        <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="SIMPAN" value="Simpan">
                        </div>
                    </from>
        
                    <br><hr><br>

                    <h4 class="text-center" >Data Produk Buku</h1>
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered table-striped">
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
    
    
    <!-- Js Bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
