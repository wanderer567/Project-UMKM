<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id_user = $_SESSION['id_user'];
$result = $koneksi->query("SELECT * FROM user WHERE id = '$id_user'");
$userData = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $hp = $_POST['hp'];
    $alamat = $_POST['alamat'];

    // kalau password kosong, tetap pakai yang lama
    $password = !empty($_POST['password']) ? $_POST['password'] : $userData['password'];

    $sql = "UPDATE user SET 
                nama='$nama', 
                email='$email', 
                username='$username', 
                password='$password', 
                hp='$hp', 
                alamat='$alamat'
            WHERE id='$id_user'";

    if ($koneksi->query($sql)) {
        // update session biar langsung kebaca di index.php
        $_SESSION['user'] = $username;
    } else {
        echo "Error: " . $koneksi->error;
    }
    header("Location: index.php");
    exit();
}

// ======================
// Tambahan histori transaksi
// ======================
$histori = $koneksi->prepare("
    SELECT t.id_transaksi, t.tanggal, p.nama_produk, d.jumlah, p.harga, 
           (d.jumlah * p.harga) AS subtotal
    FROM transaksi t
    JOIN detail d ON t.id_transaksi = d.id_transaksi
    JOIN produk p ON d.id_produk = p.id
    WHERE t.id_pelanggan = ?
    ORDER BY t.tanggal DESC
");
$histori->bind_param("i", $id_user);
$histori->execute();
$hasilHistori = $histori->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar {
            background-color: #d6e4ff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        } 
        .navbar-brand {
            font-weight: 600;
            color: #8f9ef4;
        }
  </style>
</head>
<body class="bg-light">

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
  <div class="row">
    <!-- Profil Kiri -->
    <div class="col-md-5">
      <div class="card shadow-lg rounded-4 mb-4">
        <div class="card-header bg-secondary text-white text-center rounded-top-4">
          <h4 class="mb-0">Profil Anda</h4>
        </div>
        <div class="card-body">
          <p><strong>Nama:</strong> <?= $userData['nama'] ?></p>
          <p><strong>Username:</strong> <?= $userData['username'] ?></p>
          <p><strong>Email:</strong> <?= $userData['email'] ?></p>
          <p><strong>No HP:</strong> <?= $userData['hp'] ?></p>
          <p><strong>Alamat:</strong> <?= $userData['alamat'] ?></p>
          <header class="d-flex justify-content-between align-items-center p-3 bg-light shadow-sm">
  <h3 class="mb-0">Selamat Datang, <?= $_SESSION["user"] ?>!</h3>
  
  <!-- Tombol Logout -->
  <a href="login.php" class="btn btn-danger">Logout</a>
</header>

        </div>
      </div>

      <!-- Tambahan Histori Transaksi -->
      <div class="card shadow-lg rounded-4">
        <div class="card-header bg-info text-white text-center rounded-top-4">
          <h4 class="mb-0">Riwayat Transaksi</h4>
        </div>
        <div class="card-body">
          <?php if ($hasilHistori->num_rows > 0) { ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="table-dark">
                  <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $hasilHistori->fetch_assoc()) { ?>
                    <tr>
                      <td><?= $row['id_transaksi'] ?></td>
                      <td><?= $row['tanggal'] ?></td>
                      <td><?= $row['nama_produk'] ?></td>
                      <td><?= $row['jumlah'] ?></td>
                      <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                      <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?>
                    
  <a href="hapus_transaksi.php?id=<?= $row['id_transaksi'] ?>" 
     class="btn btn-sm btn-danger"
     onclick="return confirm('Yakin mau hapus transaksi ini?')">
     Hapus
  </a>
</td>

                    
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php } else { ?>
            <p class="text-muted">Belum ada transaksi.</p>
          <?php } ?>
        </div>
      </div>
      <!-- End Histori -->
    </div>

    <!-- Form Edit Kanan -->
    <div class="col-md-7">
      <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
          <h4 class="mb-0">Edit Profil</h4>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama" class="form-control" value="<?= $userData['nama'] ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" value="<?= $userData['username'] ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= $userData['email'] ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">No HP</label>
              <input type="text" name="hp" class="form-control" value="<?= $userData['hp'] ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="3"><?= $userData['alamat'] ?></textarea>
            </div>

          

            
 
 

<div class="d-flex justify-content-between">
  <a href="index.php" class="btn btn-secondary">Kembali</a>
  <button type="submit" name="update" class="btn btn-primary">Update Profil</button>
</div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
