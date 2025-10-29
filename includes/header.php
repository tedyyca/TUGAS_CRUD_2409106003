<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Karyawan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <a href="index.php" class="nav-brand">Manajemen Data Karyawan Samsung</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="create.php">Tambah Karyawan</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message success">
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="message error">
                <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']); 
                ?>
            </div>
        <?php endif; ?>