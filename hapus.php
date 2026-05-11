<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM transactions WHERE id = $id";
    mysqli_query($koneksi, $sql);
}

header("Location: index.php");
exit;
?>
