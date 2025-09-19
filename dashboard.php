<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $ambil = $koneksi->query("SELECT gambar FROM produk WHERE id = $id");
    $row = $ambil->fetch_assoc();
    if ($row['gambar'] && file_exists("img/" . $row['gambar'])) {
        unlink("img/" . $row['gambar']);
    }
    $koneksi->query("DELETE FROM produk WHERE id = $id");
    header("Location: dashboard.php?status=hapus");
    exit();
}

// Tambah produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = 'img/' . $gambar;
    move_uploaded_file($tmp, $folder);

    $koneksi->query("INSERT INTO produk (nama_produk, harga, stok, id_kategori, deskripsi, gambar) 
                     VALUES ('$nama', '$harga', '$stok', '$id_kategori', '$deskripsi', '$gambar')");
    header("Location: dashboard.php?status=tambah");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
    

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
    <a href="data_transaksi.php">Data Transaksi</a>
    <a href="data_user.php">Data User</a>
    <form action="logout.php" method="POST" class="logout-btn">
  <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
</form>
</div>

<div class="main">
<?php if (isset($_GET['status'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if ($_GET['status'] == 'tambah'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan',
            confirmButtonColor: '#28a745'
        });
    <?php elseif ($_GET['status'] == 'hapus'): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Dihapus!',
            text: 'Produk berhasil dihapus',
            confirmButtonColor: '#d33'
        });
    <?php endif; ?>
});
</script>
<?php endif; ?>



    <div class="card p-4 mb-4">
        <h4 class="mb-3">Tambah Produk</h4>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="block font-semibold mb-2">Kategori</label>
                 <select name="id_kategori" class="w-full border rounded-lg p-3" required>
                 <option value="">-- Pilih Kategori --</option>
                 <?php
                 $kategori_q = mysqli_query($koneksi, "SELECT * FROM kategori");
                 while ($row = mysqli_fetch_assoc($kategori_q)) {
                 $selected = ($editMode && $row['id_kategori'] == @$id_kategori) ? "selected" : "";
                echo "<option value='{$row['id_kategori']}' $selected>{$row['nama_kategori']}</option>";
        }
        ?>
    </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>
            <button type="submit" name="tambah" class="btn btn-purple">Tambah Produk</button>
        </form>
    </div>

    <div class="table-responsive">
        <h4>Daftar Produk</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $result = $koneksi->query("SELECT p.*, k.nama_kategori 
                    FROM produk p
                    JOIN kategori k ON p.id_kategori = k.id_kategori");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_produk'] ?></td>
                    <td>Rp<?= number_format($row['harga']) ?></td>
                    <td><?= number_format($row['stok']) ?></td>
                    <td><?= $row['nama_kategori'] ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td>
                        <?php if ($row['gambar']): ?>
                            <img src="img/<?= $row['gambar'] ?>" alt="gambar">
                        <?php else: ?>
                            Tidak ada gambar
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mb-2">Edit</a><br>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="btn btn-sm btn-danger">Hapus</a>
                        
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>




</body>
</html>
