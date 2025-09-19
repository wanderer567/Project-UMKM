<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil daftar kategori unik dari database
$kategori_result = $koneksi->query("SELECT DISTINCT id_kategori FROM produk");


$id_user = $_SESSION['id_user'];
$result = $koneksi->query("SELECT * FROM user WHERE id = '$id_user'");
$userData = $result->fetch_assoc();


$showAlert = false;


if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hp = $_POST['hp'];
    $alamat = $_POST['alamat'];
    $role = $_POST['user']; 

    //ganti gambarrrrrrrrrr 
     if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "img/$gambar");
        $koneksi->query("UPDATE user SET nama='$nama', email='$email', username='$username', password='$password', hp='$hp', alamat='$alamat', gambar='$gambar' WHERE id=$id_user");
      }else {
        $koneksi->query("UPDATE user SET nama='$nama', email='$email', username='$username', password='$password', hp='$hp', alamat='$alamat', gambar='$gambar' WHERE id=$id_user");
}

 $showAlert = true; // set untuk menampilkan SweetAlert

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Website Anindia Kitchen</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="index.css">
  
  <!-- Tambahkan AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>
 .carousel-inner {
  border-radius: 15px;
  overflow: hidden;
}

.carousel-item {
  transition: transform 1s ease-in-out;
}

.carousel-item-next,
.carousel-item-prev,
.carousel-item.active {
  display: block;
}


  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="">
      <img src="img/logo anindia.jpg" alt="Logo" width="40" class="me-2 rounded">
      Anindia Kitchen
    </a>

     <!-- Welcome tengah -->
  <div class="welcome text-center flex-grow-1">
    <h3 class="mb-0">Selamat Datang, <?= $_SESSION["user"] ?>!</h3>
  </div>
    
  <div class="d-flex align-items-center gap-3">

    <nav>
      <ul class="nav mb-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Kategori
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Kue Kering</a></li>
            <li><a class="dropdown-item" href="#">Kue Basah</a></li>
            <li><a class="dropdown-item" href="#">Puding</a></li>
          </ul>
        </li>
      </ul>
    </nav>

      <div class="cart-icon">
      <a href="keranjang.php" class="text-dark">
        <i class="bi bi-cart3" style="font-size: 28px;"></i>
      </a>
    </div>

        <a href="index.php" class="text-dark">
  <i class="bi bi-house-fill" style="font-size: 28px;"></i>
</a>




    <!-- Profil -->
    <div class="nav-links text-end">
      <?php if (isset($_SESSION['user'])): ?>
        <a href="profil.php" class="btn btn-outline-pink">
          <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
        </a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-pink">
          <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
        </a>
      <?php endif; ?>
    </div>
  </div>
      </nav>


 <div class="container mt-4">
  <div id="carouselExampleIndicators" class="carousel slide carousel-fade rounded shadow" data-bs-ride="carousel" data-bs-interval="3000">
    
    <!-- Indicator -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
    </div>

    

    <!-- Slide -->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="img/banner 1.png" class="d-block w-100" alt="Banner 1">
      </div>
      <div class="carousel-item">
        <img src="img/banner 2.jpg" class="d-block w-100" alt="Banner 2">
      </div>
      <div class="carousel-item">
        <img src="img/banner 3.jpg" class="d-block w-100" alt="Banner 3">
      </div>
    </div>

    
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>

  </div>
</div>



    <br>

    <div class="container mt-4">
  <?php 
// Ambil semua kategori dari tabel kategori
$kategori_result = $koneksi->query("SELECT * FROM kategori");

while ($kategori_row = $kategori_result->fetch_assoc()): ?>
    <h4 class="mb-3"><?= $kategori_row['nama_kategori'] ?></h4>
    <div class="row g-4 mb-5">
      <?php
      // Ambil produk berdasarkan id_kategori
      $produk_result = $koneksi->query("
        SELECT p.* 
        FROM produk p
        WHERE p.id_kategori = '".$kategori_row['id_kategori']."'
      ");
      while ($row = $produk_result->fetch_assoc()):
      ?>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="img/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['nama_produk'] ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $row['nama_produk'] ?></h5>
              <p class="card-text">Rp<?= number_format($row['harga']) ?></p>
              <p class="card-text">Stok: <?= number_format($row['stok']) ?></p>
              <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-primary">lihat detail</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endwhile; ?>
</div>

   
   

 <footer class="footer text-center text-white py-4" style="background-color: #8f9ef4ff;">
  <div class="container">
    <hr class="border-white opacity-25">
    <p class="mb-0">
      Anindia Kitchen merupakan platfrom independen yang di tujukan kepada komunitas yang ingin melakukan jual beli dengan cara yang aman dan praktis karena berbasis website online.
    </p>
  </div>
</footer>


  <!-- Script bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

  <!-- Tambahkan AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,  // durasi animasi dalam ms
      once: true       // animasi hanya sekali saat pertama scroll
    });
  </script>
</body>
</html>
