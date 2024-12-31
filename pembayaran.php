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

// Ambil data pembayaran dari database
$pembayaran = $koneksi->query("
    SELECT tb_pembelian.id_pembelian, tb_pembelian.tanggal_pembelian, tb_pelanggan.nama_pelanggan, tb_pembelian.total_pembelian, tb_pembelian.status
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
    <title>Admin - Pembayaran</title>
</head>
<body>
<div class="container mt-5">
    <h2>Daftar Pembayaran</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pembelian</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Pembelian</th>
                <th>Total Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $pembayaran->fetch_assoc()): ?>
            <tr>
                <td><?= $row["id_pembelian"]; ?></td>
                <td><?= $row["nama_pelanggan"]; ?></td>
                <td><?= $row["tanggal_pembelian"]; ?></td>
                <td>Rp <?= number_format($row["total_pembelian"], 0, ',', '.'); ?></td>
                <td><?= $row["status"] ?: "Belum Dibayar"; ?></td>
                <td>
                    <?php if ($row["status"] == "Belum Dibayar"): ?>
                        <a href="konfirmasi_pembayaran.php?id=<?= $row["id_pembelian"]; ?>" class="btn btn-success btn-sm">Konfirmasi</a>
                    <?php else: ?>
                        <span class="badge badge-success">Lunas</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
