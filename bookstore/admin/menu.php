<!-- menu.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Menu untuk mobile -->
        <ul class="navbar-nav ml-auto d-lg-none">
            <li class="nav-item">
                <a class="nav-link" href="penerbit.php">Penerbit</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kategori.php">Kategori</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user.php">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>

        <!-- Menu untuk desktop -->
        <ul class="navbar-nav ml-auto d-none d-lg-flex">
            <li class="nav-item active">
                <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>