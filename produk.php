<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #3d98f9ff;
            color: white;
            padding-top: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #ce21b4ff;
        }
        .main {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }
        .btn-purple {
            background-color: #d63384;
            color: white;
        }
        .btn-purple:hover {
            background-color: #c2185b;
        }
        img {
            width: 80px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <h3>Anindia Kitchen

    </h3>
    <a href="dashboard.php">Dashboard Admin</a>
    <a href="edit.php">Edit</a>
    <a href="../dashboard_pelanggan.php">Produk</a>
    <form action="logout.php" method="POST" class="logout-btn">
  <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
</form>
</div>

    <div class="container mt-4">
  <?php while ($kategori_row = $kategori_result->fetch_assoc()): ?>
    <h4 class="mb-3"><?= $kategori_row['kategori'] ?></h4>
    <div class="row g-4 mb-5">
      <?php
      // Ambil produk per kategori
      $produk_result = $koneksi->query("SELECT * FROM produk WHERE kategori = '".$kategori_row['kategori']."'");
      while ($row = $produk_result->fetch_assoc()):
      ?>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="img/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['nama_produk'] ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $row['nama_produk'] ?></h5>
              <p class="card-text">Rp<?= number_format($row['harga']) ?></p>
              <p class="card-text">Stok: <?= number_format($row['stok']) ?></p>
              <p class="card-text"><?= $row['deskripsi'] ?></p>
              <button class="btn btn-primary">Beli Sekarang</button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endwhile; ?>
</div>

<footer>
  <div class="container">
    <hr class="border-white opacity-25">
    <p class="mb-0">Anindia Kitchen adalah platform jual beli kue yang aman, praktis, dan terpercaya berbasis website.</p>
  </div>
</footer>
</body>
</html>