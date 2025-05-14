<?php
session_start(); // Pastikan session dimulai

include "koneksi.php";

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);

// Jika user sudah login, cek level aksesnya
if ($is_logged_in && (!isset($_SESSION['level_akses']) || $_SESSION['level_akses'] !== 'Customer')) {
    echo "Akses ditolak. Anda bukan Customer";
    exit();
}

// Query untuk kategori
$query_kategori = "SELECT * FROM tb_kategori";
$result_kategori = mysqli_query($conn, $query_kategori);

$kategori_list = [];
while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
    $kategori_list[] = $row_kategori;
}

// Query buku dengan kategori
$where_clause = "";
if (isset($_GET['kategori'])) {
    $kategori_id = mysqli_real_escape_string($conn, $_GET['kategori']);
    $where_clause = "WHERE b.id_kategori = $kategori_id";
}

// Query untuk buku
$query = "SELECT b.id_buku, k.nama_kategori, b.judul_buku, b.pengarang,
        p.nama_penerbit, b.tahun, b.harga, b.foto, b.stok 
        FROM tb_buku b 
        JOIN tb_kategori k ON b.id_kategori = k.id_kategori 
        JOIN tb_penerbit p ON b.id_penerbit = p.id_penerbit
        $where_clause";

// Filter search
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
if (!empty($keyword)) {
    $query .= (empty($where_clause) ? " WHERE " : " AND ") . " (b.judul_buku LIKE '%$keyword%' OR k.nama_kategori LIKE '%$keyword%' OR p.nama_penerbit LIKE '%$keyword%')";
}

$result = mysqli_query($conn, $query);

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
                <div class="col-md-3 mb-4 d-none d-md-block">
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
                <h2>KATALOK BUKU</h2>
                <div class="col-md-4">
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="keyword" value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                    </div>

                <div class="row mt-4">
                    
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card product-card">
                            <img src="admin/foto_produk/<?= $row['foto']; ?>" class="card-img-top" alt="<?= $row['judul_buku']; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $row['judul_buku']; ?></h5>
                                <p class="card-text">Kategori: <?= $row['nama_kategori']; ?><br>Harga: Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                                <?php if ($is_logged_in): ?>
                                    <a href="add_to_chart.php?id_buku=<?= $row['id_buku']; ?>" class="btn btn-success btn-buy">Add to Cart</a>
                                <?php else: ?>
                                    <a href="#" class="btn btn-success btn-buy" data-toggle="modal" data-target="#loginModal">Add to Cart</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                
                    <!-- Loop melalui semua produk buku dan tampilkan -->
                   <!-- <?php foreach ($buku_list as $buku): ?>
                        <div class="col-md-3 mb-3">
                            <div class="card product-card">
                                <img src="admin/foto_produk/<?= $buku['foto']; ?>" class="card-img-top" alt="<?= $buku['judul_buku']; ?>">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?= $buku['judul_buku']; ?></h6>
                                    <p class="card-text">Stok: <?= $buku['stok']; ?> | Harga : Rp <?= number_format($buku['harga'], 0, ',', '.'); ?></p>
                                    <?php if ($is_logged_in): ?>
                                        <a href="add_to_chart.php?id_buku=<?= $buku['id_buku']; ?>" class="btn btn-primary btn-buy">Add to Chart</a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-primary btn-buy" data-toggle="modal" data-target="#loginModal">Add to Chart</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>-->
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" />
</body>
</html>
