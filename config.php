<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "umuk";

// Connect to MySQL server first (without database)
$koneksi = mysqli_connect($host, $user, $pass);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Create database if not exists
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
mysqli_query($koneksi, $sql_create_db);

// Select the database
mysqli_select_db($koneksi, $db_name);

// Create transactions table if not exists
$sql_create_table = "CREATE TABLE IF NOT EXISTS transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    jenis ENUM('Masuk', 'Keluar') NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    penginput_nama VARCHAR(100) NULL,
    penginput_foto VARCHAR(255) NULL
)";
mysqli_query($koneksi, $sql_create_table);

// ALTER TABLE to add the columns if the table already existed before this feature
$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM transactions LIKE 'penginput_nama'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($koneksi, "ALTER TABLE transactions ADD penginput_nama VARCHAR(100) NULL AFTER keterangan");
    mysqli_query($koneksi, "ALTER TABLE transactions ADD penginput_foto VARCHAR(255) NULL AFTER penginput_nama");
}

// Set timezone to Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

// Utility function to format Rupiah
function format_rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
?>
