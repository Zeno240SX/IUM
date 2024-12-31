<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "db_toko");

// Pastikan admin sudah login
if (!isset($_SESSION["admin"])) {
    echo "<script>alert('Silahkan Login Sebagai Admin');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

$id_pembelian = $_GET["id"];

// Hapus data pesanan dari tabel tb_pembelian
$koneksi->query("DELETE FROM tb_pembelian WHERE id_pembelian = '$id_pembelian'");

// Hapus data produk terkait pesanan dari tabel tb_pembelian_produk
$koneksi->query("DELETE FROM tb_pembelian_produk WHERE id_pembelian = '$id_pembelian'");

// Redirect kembali ke halaman pembelian dengan pesan sukses
echo "<script>alert('Pesanan berhasil dihapus');</script>";
echo "<script>location='pembelian.php';</script>";
?>
