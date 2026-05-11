<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Sanitization for basic security
    $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
    $jenis = mysqli_real_escape_string($koneksi, $jenis);
    $jumlah = floatval($jumlah);
    $keterangan = mysqli_real_escape_string($koneksi, $keterangan);

    $sql = "INSERT INTO transactions (tanggal, jenis, jumlah, keterangan) VALUES ('$tanggal', '$jenis', '$jumlah', '$keterangan')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Gagal menambah data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - UMUK</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container animate-fade-in" style="max-width: 600px;">
        <h1 class="header-title">Tambah Transaksi</h1>

        <div class="glass-card">
            <form action="tambah.php" method="POST">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label>Jenis Transaksi</label>
                    <select name="jenis" class="form-control" required>
                        <option value="Masuk">Pemasukan (Uang Masuk)</option>
                        <option value="Keluar">Pengeluaran (Uang Keluar)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Rupiah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" required min="1" placeholder="Contoh: 50000">
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" required placeholder="Contoh: Beli Bensin, Gaji, dll">
                </div>

                <div class="action-buttons" style="margin-top: 2rem;">
                    <a href="index.php" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
