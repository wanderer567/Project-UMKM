<?php
include 'koneksi.php';

// Ambil daftar kategori unik
$kategori_result = $koneksi->query("SELECT DISTINCT id_kategori FROM produk");

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan!";
    exit;
}

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM produk WHERE id = $id")->fetch_assoc();

if (!$data) {
    echo "Produk dengan ID tersebut tidak ditemukan!";
    exit;
}

$showAlert = false; // penanda untuk menampilkan SweetAlert

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];

    // jika ganti gambar
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "img/$gambar");
        $koneksi->query("UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok', kategori='$kategori', deskripsi='$deskripsi', gambar='$gambar' WHERE id=$id");
        } else {
            $koneksi->query("UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok', id_kategori='$id_kategori', deskripsi='$deskripsi' WHERE id=$id");
    }

    $showAlert = true; // set untuk menampilkan SweetAlert
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #3d98f9;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #ce21b4;
        }
        .logout-btn {
            padding: 12px 20px;
        }
        .logout-btn button {
            width: 100%;
            padding: 8px;
            border: none;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .logout-btn button:hover {
            background-color: #bb2d3b;
        }

        /* Main content */
        .main {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
        }

        /* Form */
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn {
            margin-top: 20px;
            background-color: #198754;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #157347;
        }
        img {
            width: 80px;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
      <div class="sidebar">
        <h3>Anindia Kitchen</h3>
        <a href="dashboard.php">Dashboard Admin</a>
        <a href="#">Edit</a>
        <a href="produk.php">Produk</a>
        <form action="logout.php" method="POST" class="logout-btn">
            <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <div class="main">
        <h2>Edit Produk</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="nama" value="<?= $data['nama_produk'] ?>" required>
            <label>Harga</label>
            <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
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
            <label>Deskripsi</label>
            <textarea name="deskripsi" required><?= $data['deskripsi'] ?></textarea>
            <label>Ganti Gambar</label>
            <input type="file" name="gambar">
            <button type="submit" name="update" class="btn">Update</button>
        </form>
    </div>

<?php if ($showAlert): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Produk berhasil diupdate',
        confirmButtonColor: '#198754'
    }).then(() => {
        window.location.href = "../admin_dashboard/dashboard.php";
    });
});
</script>
<?php endif; ?>

</body>
</html>