<?php

$db_host = 'localhost';   
$db_name = 'db_karyawan'; 
$db_user = 'root';        
$db_pass = '';            

try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    
    // Opsi PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
        PDO::ATTR_EMULATE_PREPARES   => false,                
    ];

    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
} catch (PDOException $e) {

    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>