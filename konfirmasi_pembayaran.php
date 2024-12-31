<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$koneksi = new mysqli("localhost", "root", "", "db_toko");

// Pastikan admin sudah login
if (!isset($_SESSION["admin"])) {
    echo "<script>alert('Silahkan Login Sebagai Admin');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

$id_pembelian = $_GET["id"];

// Periksa apakah data pembelian valid
$cek_pembelian = $koneksi->query("SELECT * FROM tb_pembelian WHERE id_pembelian = '$id_pembelian'");
if ($cek_pembelian->num_rows === 0) {
    echo "<script>alert('ID Pembelian tidak ditemukan');</script>";
    echo "<script>location='pembayaran.php';</script>";
    exit();
}

// Update status pembayaran menjadi Lunas
$koneksi->query("UPDATE tb_pembelian SET status = 'Lunas' WHERE id_pembelian = '$id_pembelian'");

// Redirect ke halaman pembayaran dengan pesan sukses
echo "<script>alert('Pembayaran berhasil dikonfirmasi');</script>";
echo "<script>location='pembayaran.php';</script>";
?>
