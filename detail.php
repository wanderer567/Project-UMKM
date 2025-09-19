<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Produk tidak ditemukan!";
    exit();
}

$id = intval($_GET['id']);
$result = $koneksi->query("SELECT * FROM produk WHERE id = $id");
$produk = $result->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan!";
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #d6e4ff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 600;
            color: #8f9ef4;
        }
        .product-img {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .product-img img {
            width: 100%;
            border-radius: 15px;
            transition: transform 0.4s ease;
        }
        .product-img:hover img {
            transform: scale(1.05);
        }
        .price {
            font-size: 26px;
            font-weight: bold;
            color: #ff6b6b;
        }
        .badge-stock {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .btn-primary {
            background-color: #8f9ef4;
            border: none;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #6f85e4;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .cart-icon a {
  color: #2d2d2d;
  transition: 0.3s;
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
    
  <div class="d-flex align-items-center gap-3">
      <div class="cart-icon">
      <a href="keranjang.php" class="text-dark">
        <i class="bi bi-cart3" style="font-size: 28px;"></i>
      </a>
    </div>

        <a href="index.php" class="text-dark">
  <i class="bi bi-house-fill" style="font-size: 28px;"></i>
</a>

      <div class="nav-links ms-3 text-end">
    <?php if (isset($_SESSION['user'])): ?>
     
      <a href="profil.php" class="btn btn-outline-pink">
        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
      </a>
    <?php else: ?>
      <!-- Kalau belum login arahkan ke login.php -->
      <a href="login.php" class="btn btn-outline-pink">
        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
      </a>
    <?php endif; ?>
  </div>
  </div>
</nav>
</div>

<div class="container mt-5">
    <div class="row g-5 align-items-center" data-aos="fade-up">
        <!-- Gambar Produk -->
        <div class="col-md-6">
            <div class="product-img shadow">
                <img src="img/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
            </div>
        </div>

        <!-- Info Produk -->
        <div class="col-md-6">
            <h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>
            <p class="price">Rp<?= number_format($produk['harga']) ?></p>

            <!-- Stok -->
            <?php if ($produk['stok'] <= 5): ?>
                <span class="badge bg-danger badge-stock">Stok hampir habis (<?= $produk['stok'] ?>)</span>
            <?php else: ?>
                <span class="badge bg-success badge-stock">Stok tersedia (<?= $produk['stok'] ?>)</span>
            <?php endif; ?>

            <p class="mt-4"><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>

            <!-- Tombol -->
            <div class="mt-4">
                <a href="index.php" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <form method="post" action="keranjang.php" class="mt-3">
    <input type="hidden" name="id" value="<?= $produk['id'] ?>">
    <input type="hidden" name="hidden_name" value="<?= $produk['nama_produk'] ?>">
    <input type="hidden" name="hidden_harga" value="<?= $produk['harga'] ?>">
    <input type="hidden" name="hidden_foto" value="<?= $produk['gambar'] ?>">

    <div class="d-flex align-items-center">
        <input type="number" name="jumlah" value="1" min="1" class="form-control w-25 me-2">
        <button type="submit" name="add" class="btn btn-primary">
            <i class="bi bi-cart-check"></i> Masukan Keranjang
        </button>
    </div>
</form>
            </div>
        </div>
    </div>

   

<!-- SweetAlert Script -->
<script>
document.getElementById('btnBeli').addEventListener('click', function() {
    Swal.fire({
        title: 'Pilih Tindakan',
        text: "Apa yang ingin Anda lakukan?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Checkout',
        cancelButtonText: 'Masukan Keranjang',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "checkout.php?id=<?= $produk['id'] ?>";
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = "keranjang.php?action=add&id=<?= $produk['id'] ?>";
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

</body>
</html>
