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
$pesanan = $koneksi->query("
    SELECT tb_pembelian.*, tb_pelanggan.nama_pelanggan 
    FROM tb_pembelian 
    JOIN tb_pelanggan ON tb_pembelian.id_pelanggan = tb_pelanggan.id_pelanggan
    WHERE tb_pembelian.id_pembelian = '$id_pembelian'
")->fetch_assoc();

$produk = $koneksi->query("
    SELECT tb_pembelian_produk.*, tb_produk.nama_produk, tb_produk.harga_produk 
    FROM tb_pembelian_produk 
    JOIN tb_produk ON tb_pembelian_produk.id_produk = tb_produk.id_produk
    WHERE tb_pembelian_produk.id_pembelian = '$id_pembelian'
");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Admin - Detail Pesanan</title>
</head>
<body>
<div class="container mt-5">
    <h2>Detail Pesanan #<?= $pesanan["id_pembelian"]; ?></h2>
    <p><strong>Nama Pelanggan:</strong> <?= $pesanan["nama_pelanggan"]; ?></p>
    <p><strong>Tanggal Pembelian:</strong> <?= $pesanan["tanggal_pembelian"]; ?></p>
    <p><strong>Metode Pickup:</strong> <?= ucfirst($pesanan["pickup_option"]); ?></p>
    <p><strong>Biaya Admin:</strong> Rp <?= number_format($pesanan["delivery_cost"], 0, ',', '.'); ?></p>
    <p><strong>Total Pembayaran:</strong> Rp <?= number_format($pesanan["total_pembelian"], 0, ',', '.'); ?></p>

    <h3>Item Produk</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $produk->fetch_assoc()): ?>
            <tr>
                <td><?= $row["nama_produk"]; ?></td>
                <td>Rp <?= number_format($row["harga_produk"], 0, ',', '.'); ?></td>
                <td><?= $row["jumlah"]; ?></td>
                <td>Rp <?= number_format($row["harga_produk"] * $row["jumlah"], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="pembelian.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>
