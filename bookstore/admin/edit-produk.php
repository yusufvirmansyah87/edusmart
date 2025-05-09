
<?php
session_start(); // Pastikan session dimulai
include "../koneksi.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])|| ($_SESSION['level_akses'] !== 'Admin')) {
    header("Location: ../index.php");
    exit;
}

// ambil data Kategori
$queryGetKategori = "SELECT id_kategori, nama_kategori FROM tb_kategori ORDER BY id_kategori DESC";
$resultKategori = $conn->query($queryGetKategori);

// ambil data Penerbit
$queryGetPenerbit = "SELECT id_penerbit, nama_penerbit FROM tb_penerbit ORDER BY id_penerbit DESC";
$resultPenerbit = $conn->query($queryGetPenerbit);


if (!isset($_GET['id_buku'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = intval($_GET['id_buku']);
$queryBuku = "SELECT * FROM tb_buku WHERE id_buku = $id";
$resultBuku = $conn->query($queryBuku);

if ($resultBuku->num_rows > 0) {
    $data = $resultBuku->fetch_assoc();
} else {
    echo "Data tidak ditemukan!";
}


if (isset($_POST['UPDATE'])) {
    $id_buku = $_POST['id_buku'];
    $id_kategori = $_POST['id_kategori'];
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $id_penerbit = $_POST['id_penerbit'];
    $tahun = $_POST['tahun'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah gambar baru di-upload
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $upload_dir = "foto_produk/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Hapus gambar lama jika ada
        if (file_exists($upload_dir . $gambar_lama)) {
            unlink($upload_dir . $gambar_lama);
        }

        // Pindahkan file gambar yang di-upload
        $gambar_path = $upload_dir . basename($gambar);
        move_uploaded_file($tmp_name, $gambar_path);
    } else {
        $gambar = $gambar_lama; // Jika gambar tidak diubah, pakai gambar lama
    }

    // Query untuk update produk
    $query = "UPDATE tb_buku SET 
                id_kategori = '$id_kategori', 
                judul_buku = '$judul_buku', 
                pengarang = '$pengarang', 
                id_penerbit = '$id_penerbit', 
                tahun = '$tahun', 
                harga = '$harga', 
                foto = '$gambar', 
                stok = '$stok' 
              WHERE id_buku = $id_buku";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data buku berhasil diupdate!'); window.location='produk.php';</script>";
        header("Location: produk.php"); // Redirect kembali ke halaman index setelah update
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
                <h4 class="text-center" >Edit Produk Buku</h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_buku" value="<?php echo $data['id_buku']; ?>">
                    <input type="hidden" name="gambar_lama" value="<?php echo $data['foto']; ?>">
                        <div class="form-group">
                            <label for="id_kategori">Pilih Kategori:</label>
                            <select class="form-control" id="id_kategori" name="id_kategori">
                                <?php
                                while ($row = $resultKategori->fetch_assoc()) {
                                    $selected = ($row['id_kategori'] == $data['id_kategori']) ? "selected" : "";
                                    echo "<option value='" . $row['id_kategori'] . "' $selected>" . $row['nama_kategori'] . "</option>";
                                }                                
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="judul_buku">Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" value="<?php echo $data['judul_buku']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" value="<?php echo $data['pengarang']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="id_penerbit">Pilih Penerbit:</label>
                            <select class="form-control" id="id_penerbit" name="id_penerbit">
                                <?php
                                while ($row2 = $resultPenerbit->fetch_assoc()) {
                                    $selected = ($row2['id_penerbit'] == $data['id_penerbit']) ? "selected" : "";
                                    echo "<option value='" . $row2['id_penerbit'] . "' $selected>" . $row2['nama_penerbit'] . "</option>";
                                }                                
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun Terbit</label>
                            <input type="number" class="form-control" name="tahun" value="<?php echo $data['tahun']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" name="harga" value="<?php echo $data['harga']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Gambar Saat Ini:</label><br>
                            <img src="foto_produk/<?php echo $data['foto']; ?>" alt="Gambar Produk" width="100"><br><br>

                            <label for="gambar">Gambar Produk Baru:</label>
                            <input type="file" class="form-control" name="gambar">
                        </div>
                        <div class="form-group">
                            <label for="harga">Stok</label>
                            <input type="number" class="form-control" name="stok" value="<?php echo $data['stok']; ?>">
                        </div><br>
                        <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="UPDATE" value="Update">
                        <a href="produk.php"><input type="button" class="btn btn-secondary"  value="Kembali"></a>
                        </div>
                    </form>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
    
    
    <!-- Js Bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
