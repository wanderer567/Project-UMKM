<?php 
session_start();
if (!isset($_SESSION['id_user'])){
    header('location:login.php');
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "irsyad");
$id_user = $_SESSION['id_user'];

// Tambah produk ke keranjang
if (isset($_POST["add"])) {
    $id_produk = $_POST["id"];
    if (isset($_SESSION["cart"][$id_produk])) {
        $_SESSION["cart"][$id_produk]['jumlah'] += $_POST["jumlah"];
    } else {
        $_SESSION["cart"][$id_produk] = [
            'id'     => $id_produk,
            'nama'   => $_POST["hidden_name"],
            'harga'  => $_POST["hidden_harga"],
            'foto'   => $_POST["hidden_foto"],
            'jumlah' => $_POST["jumlah"],
        ];
    }
    header("Location: keranjang.php");
    exit();
}

// Aksi hapus / beli
if (isset($_GET["aksi"])) {
    if ($_GET["aksi"] == "hapus") {
        $id_produk = $_GET["id"];
        unset($_SESSION["cart"][$id_produk]);
        header("Location: keranjang.php");
        exit();
    } elseif ($_GET["aksi"] == "beli") {
        $total = 0;
        foreach ($_SESSION["cart"] as $v) {
            $total += ($v["jumlah"] * $v["harga"]);
        }

        // Simpan transaksi utama
        mysqli_query($koneksi, "INSERT INTO transaksi(tanggal, id_pelanggan, total_harga)
                    VALUES ('".date("Y-m-d")."', '$id_user', '$total')")
        or die("Error transaksi: " . mysqli_error($koneksi));
        $id_transaksi = mysqli_insert_id($koneksi);

        // Simpan detail produk
        foreach ($_SESSION["cart"] as $v) {
            $id_produk = $v['id'];
            $jumlah = $v['jumlah'];
            mysqli_query($koneksi, "INSERT INTO detail(id_transaksi, id_produk, jumlah)
                        VALUES ('$id_transaksi', '$id_produk', '$jumlah')");
        }

        unset($_SESSION["cart"]);
        header("Location: cetak.php?id=$id_transaksi");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .navbar {
            background-color: #d6e4ff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light"><nav class="navbar navbar-expand-lg bg-body-tertiary">
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


<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Keranjang Belanja</h3>
        <a href="index.php" class="btn btn-secondary">← Kembali</a>
    </div>

    <?php if (!empty($_SESSION["cart"])): ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center bg-white">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                foreach ($_SESSION["cart"] as $item): 
                    $subtotal = $item["jumlah"] * $item["harga"];
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td class="text-start">
                        <img src="img/<?= $item["foto"] ?>" width="60" class="me-2 rounded">
                        <?= $item["nama"] ?>
                    </td>
                    <td><?= $item["jumlah"] ?></td>
                    <td>Rp <?= number_format($item["harga"], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    <td>
                        <a href="keranjang.php?aksi=hapus&id=<?= $item['id'] ?>" 
                           class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Grand Total</td>
                    <td colspan="2" class="fw-bold">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-end">
        <a href="keranjang.php?aksi=beli" class="btn btn-success">Checkout</a>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Keranjang masih kosong</div>
    <?php endif; ?>
</div>

</body>
</html>
