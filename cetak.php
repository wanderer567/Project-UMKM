<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "irsyad");

$id = $_GET['id'];
$id_user = $_SESSION['id_user'];

// Ambil data transaksi (gabungan transaksi + user)
$res = "SELECT * FROM transaksi 
        INNER JOIN user ON transaksi.id_pelanggan = user.id
        WHERE transaksi.id_transaksi = '$id' 
        AND transaksi.id_pelanggan = '$id_user'";
$query = mysqli_query($koneksi, $res);
$user = mysqli_fetch_array($query);

// Ambil detail transaksi (produk + jumlah)
$prod = "SELECT detail.*, produk.nama_produk, produk.harga 
         FROM detail 
         INNER JOIN produk ON detail.id_produk = produk.id
         WHERE detail.id_transaksi = '$id'";
$query2 = mysqli_query($koneksi, $prod);
?>

<!-- Info Transaksi -->
<div class="invoice-info">
    <p><strong>No. Invoice :</strong> INV-<?= $id ?></p>
    <p><strong>Nama Pembeli :</strong> <?= $user['nama'] ?></p>
    <p><strong>Tanggal :</strong> <?= $user['tanggal'] ?></p>
</div>
<button class="btn btn-outline-secondary" onclick="window.print()">Cetak</button>

<!-- Table Produk -->
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr>
        <th>Nama Barang</th>
        <th class="text-center">Qty</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Subtotal</th>
    </tr>

    <?php 
    $grandTotal = 0;
    while ($row = mysqli_fetch_array($query2)) {
        $subtotal = $row['jumlah'] * $row['harga'];
        $grandTotal += $subtotal;
    ?>
    <tr>
        <td><?= $row['nama_produk'] ?></td>
        <td class="text-center"><?= $row['jumlah'] ?></td>
        <td class="text-right">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td class="text-right">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
    </tr>
    <?php } ?>
    
    <tr class="total-row">
        <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
        <td class="text-right">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
    </tr>
</table>
