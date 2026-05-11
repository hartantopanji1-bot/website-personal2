<?php
require 'config.php';

// Calculate Totals
$query_masuk = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_masuk FROM transactions WHERE jenis='Masuk'");
$row_masuk = mysqli_fetch_assoc($query_masuk);
$total_masuk = $row_masuk['total_masuk'] ?? 0;

$query_keluar = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_keluar FROM transactions WHERE jenis='Keluar'");
$row_keluar = mysqli_fetch_assoc($query_keluar);
$total_keluar = $row_keluar['total_keluar'] ?? 0;

$saldo = $total_masuk - $total_keluar;

// Fetch all transactions
$result = mysqli_query($koneksi, "SELECT * FROM transactions ORDER BY tanggal DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMUK - Uang Masuk Uang Keluar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container animate-fade-in">
        <h1 class="header-title">Uang Masuk & Keluar</h1>

        <div class="summary-grid">
            <div class="glass-card summary-card">
                <h3>Total Masuk</h3>
                <div class="amount masuk"><?= format_rupiah($total_masuk) ?></div>
            </div>
            <div class="glass-card summary-card">
                <h3>Total Keluar</h3>
                <div class="amount keluar"><?= format_rupiah($total_keluar) ?></div>
            </div>
            <div class="glass-card summary-card">
                <h3>Sisa Saldo</h3>
                <div class="amount saldo"><?= format_rupiah($saldo) ?></div>
            </div>
        </div>

        <div class="glass-card">
            <div class="action-buttons">
                <h2>Riwayat Transaksi</h2>
                <div>
                    <a href="tambah.php" class="btn btn-primary">+ Tambah Transaksi</a>
                    <a href="laporan.php" class="btn btn-success">🖨️ Cetak Laporan</a>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                    <td>
                                        <span class="badge <?= strtolower($row['jenis']) ?>">
                                            <?= $row['jenis'] ?>
                                        </span>
                                    </td>
                                    <td><?= format_rupiah($row['jumlah']) ?></td>
                                    <td>
                                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">Belum ada data transaksi. Silakan tambah transaksi baru.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
