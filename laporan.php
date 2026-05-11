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

// Fetch all descending but for report usually ascending date
$result = mysqli_query($koneksi, "SELECT * FROM transactions ORDER BY tanggal ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan UMUK</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .report-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .report-header h1 {
            color: #000;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        body {
            background: white !important;
            color: #000;
        }
    </style>
</head>
<body> 
    <div class="container" style="max-width: 900px;">
        
        <div class="no-print" style="margin-bottom: 1rem; text-align: center;">
            <button onclick="window.print()" class="btn btn-primary" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">🖨️ Cetak Laporan</button>
            <a href="index.php" class="btn btn-danger" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">Kembali</a>
        </div>

        <div class="report-header">
            <h1>Laporan Arus Kas</h1>
            <p><strong>Buku Uang Masuk & Uang Keluar</strong></p>
            <p style="color: #666; font-size: 0.9rem;">Dicetak pada: <?= date('d M Y, H:i') ?> WIB</p>
        </div>

        <div class="summary-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 2rem; background: #fff;">
            <div class="summary-card" style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
                <h3 style="color:#444; margin-bottom: 0.5rem; font-size: 1rem;">Total Pemasukan</h3>
                <div class="amount" style="color:#059669; font-size:1.5rem;"><?= format_rupiah($total_masuk) ?></div>
            </div>
            <div class="summary-card" style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
                <h3 style="color:#444; margin-bottom: 0.5rem; font-size: 1rem;">Total Pengeluaran</h3>
                <div class="amount" style="color:#dc2626; font-size:1.5rem;"><?= format_rupiah($total_keluar) ?></div>
            </div>
            <div class="summary-card" style="border: 2px solid #ddd; padding: 1rem; background-color: #f9fafb; border-radius: 8px;">
                <h3 style="color:#444; margin-bottom: 0.5rem; font-size: 1rem;">Saldo Akhir</h3>
                <div class="amount" style="color:#4f46e5; font-size:1.5rem; font-weight:bold;"><?= format_rupiah($saldo) ?></div>
            </div>
        </div>

        <table style="border: 1px solid #ddd; margin-bottom: 3rem;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; width: 5%;">No</th>
                    <th style="border: 1px solid #ddd; width: 15%;">Tanggal</th>
                    <th style="border: 1px solid #ddd;">Keterangan</th>
                    <th style="border: 1px solid #ddd; text-align:right; width: 15%;">Masuk</th>
                    <th style="border: 1px solid #ddd; text-align:right; width: 15%;">Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php 
                    $no = 1; 
                    while ($row = mysqli_fetch_assoc($result)): 
                        if ($row['jenis'] == 'Masuk') {
                            $m = format_rupiah($row['jumlah']);
                            $k = "-";
                        } else {
                            $m = "-";
                            $k = format_rupiah($row['jumlah']);
                        }
                    ?>
                        <tr>
                            <td style="border: 1px solid #ddd; text-align:center;"><?= $no++ ?></td>
                            <td style="border: 1px solid #ddd;"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                            <td style="border: 1px solid #ddd;"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td style="border: 1px solid #ddd; text-align:right; color: #059669;"><?= $m ?></td>
                            <td style="border: 1px solid #ddd; text-align:right; color: #dc2626;"><?= $k ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state" style="border: 1px solid #ddd; text-align:center;">Tidak ada data transaksi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="border: 1px solid #ddd; text-align:right; font-size:1.1rem; padding: 1rem;">Total Saldo Akhir</th>
                    <th colspan="2" style="border: 1px solid #ddd; text-align:right; font-size:1.3rem; background-color:#f9fafb; color: #4f46e5; padding: 1rem;"><?= format_rupiah($saldo) ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 3rem; text-align: right; padding-right: 2rem;" class="signature">
            <p>Mengetahui,</p>
            <br><br><br><br>
            <p><strong>(.......................................)</strong></p>
        </div>
    </div>
</body>
</html>
