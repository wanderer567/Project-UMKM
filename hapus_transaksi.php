<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_transaksi = intval($_GET['id']); // amanin input

// Pastikan transaksi milik user
$cek = $koneksi->prepare("SELECT * FROM transaksi WHERE id_transaksi=? AND id_pelanggan=?");
$cek->bind_param("ii", $id_transaksi, $id_user);
$cek->execute();
$res = $cek->get_result();

if ($res->num_rows > 0) {
    // Hapus detail transaksi dulu
    $koneksi->query("DELETE FROM detail WHERE id_transaksi='$id_transaksi'");
    // Hapus transaksi
    $koneksi->query("DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'");
}

// Balik ke profil
header("Location: profil.php");
exit();
?>
