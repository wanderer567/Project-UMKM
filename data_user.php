<?php
session_start();
include 'koneksi.php';



// Ambil data user dari database
$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background: #343a40;
    color: #fff;
    padding: 20px;
    box-sizing: border-box;
}

.sidebar h3 {
    margin: 0 0 20px;
    font-size: 20px;
    text-align: center;
    border-bottom: 1px solid #489febff;
    padding-bottom: 10px;
}

.sidebar a {
    display: block;
    color: #ddd;
    text-decoration: none;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 10px;
    transition: background 0.3s, color 0.3s;
}

.sidebar a:hover {
    background: #3997f0ff;
    color: #fff;
}

.sidebar .logout-btn {
    margin-top: 20px;
    text-align: center;
}

.sidebar button {
    background: #dc3545;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s;
}

.sidebar button:hover {
    background: #c82333;
}

/* Konten utama */
.content {
    margin-left: 240px; /* kasih jarak biar gak ketimpa sidebar */
    padding: 20px;
}

h2 {
    text-align: center;
    margin: 20px 0;
}

table {
    border-collapse: collapse;
    width: 95%;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background: #f2f2f2;
}

a.btn {
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
    color: #fff;
}

.edit { background: #28a745; }
.hapus { background: #dc3545; }

    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Anindia Kitchen</h3>
        <a href="dashboard.php">Dashboard Admin</a>
        <a href="data_transaksi.php">Data Transaksi</a>
        <a href="data_user.php">Data User</a>
        <form action="logout.php" method="POST" class="logout-btn">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>

    <!-- konten utama digeser ke kanan -->
    <div class="content">
        <h2>Data User</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Role</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['hp']; ?></td>
                <td><?= $row['alamat']; ?></td>
                <td><?= $row['role']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>
