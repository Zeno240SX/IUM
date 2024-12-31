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

// Ambil data pesanan dari database
$pesanan = $koneksi->query("
    SELECT tb_pembelian.*, tb_pelanggan.nama_pelanggan 
    FROM tb_pembelian 
    JOIN tb_pelanggan ON tb_pembelian.id_pelanggan = tb_pelanggan.id_pelanggan
    ORDER BY tb_pembelian.tanggal_pembelian DESC
");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Admin - Daftar Pesanan</title>
</head>
<body>
<div class="container mt-5">
    <h2>Daftar Pesanan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Pembelian</th>
                <th>Total Pembayaran</th>
                <th>Metode Pickup</th>
                <th>Biaya Admin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $pesanan->fetch_assoc()): ?>
            <tr>
                <td><?= $row["id_pembelian"]; ?></td>
                <td><?= $row["nama_pelanggan"]; ?></td>
                <td><?= $row["tanggal_pembelian"]; ?></td>
                <td>Rp <?= number_format($row["total_pembelian"], 0, ',', '.'); ?></td>
                <td><?= ucfirst($row["pickup_option"]); ?></td>
                <td>Rp <?= number_format($row["delivery_cost"], 0, ',', '.'); ?></td>
                <td><?= $row["status"] ?: "Belum Dibayar"; ?></td>
                <td>
                    <a href="detail.php?id=<?= $row["id_pembelian"]; ?>" class="btn btn-info btn-sm">Detail</a>
                    <a href="hapus_pesanan.php?id=<?= $row["id_pembelian"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan ini?');">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
