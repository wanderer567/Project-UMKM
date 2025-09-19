<?php
$koneksi = new mysqli("localhost", "root", "", "irsyad");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
