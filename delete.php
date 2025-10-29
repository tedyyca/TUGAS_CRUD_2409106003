<?php
session_start();
require 'config/database.php';


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id == 0) {
    $_SESSION['error_message'] = "ID karyawan tidak valid.";
    header("Location: index.php");
    exit;
}


try {
    $sql = "DELETE FROM employees WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Cek apakah ada baris yang terhapus
    if ($stmt->rowCount() > 0) {
        $_SESSION['success_message'] = "Data karyawan berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Data karyawan tidak ditemukan (mungkin sudah dihapus).";
    }
    
    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    
    $_SESSION['error_message'] = "Gagal menghapus data: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
?>