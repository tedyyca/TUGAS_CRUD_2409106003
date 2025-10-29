<?php
session_start();
require 'config/database.php';

$divisi_valid = ['Engineering', 'Marketing', 'Sales', 'Human Resources', 'Finance'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $no_telepon = trim($_POST['no_telepon']);
    $divisi = trim($_POST['divisi']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $gaji = $_POST['gaji'];

    $errors = [];

    if (empty($id) || !is_numeric($id)) {
        $errors[] = "ID Karyawan tidak valid.";
    }
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap wajib diisi.";
    if (empty($email)) $errors[] = "Email wajib diisi.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";
    
    if (empty($divisi)) {
        $errors[] = "Divisi wajib dipilih.";
    } elseif (!in_array($divisi, $divisi_valid)) {
        $errors[] = "Pilihan divisi tidak valid.";
    }

    if (empty($tanggal_masuk)) $errors[] = "Tanggal masuk wajib diisi.";
    if (empty($gaji)) $errors[] = "Gaji wajib diisi.";
    elseif (!is_numeric($gaji) || $gaji <= 0) $errors[] = "Gaji harus berupa angka positif.";

    if (empty($errors)) {
        try {
            $sql_check = "SELECT COUNT(id) FROM employees WHERE email = :email AND id != :id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':email' => $email, ':id' => $id]);
            if ($stmt_check->fetchColumn() > 0) {
                $errors[] = "Email '$email' sudah terdaftar. Gunakan email lain.";
            }
        } catch (PDOException $e) {
            $errors[] = "Gagal memvalidasi email: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: edit.php?id=" . $id);
        exit;
    } else {
        try {
            $sql = "UPDATE employees SET 
                        nama_lengkap = :nama_lengkap, 
                        email = :email, 
                        no_telepon = :no_telepon, 
                        divisi = :divisi, 
                        tanggal_masuk = :tanggal_masuk, 
                        gaji = :gaji 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nama_lengkap' => $nama_lengkap,
                ':email'        => $email,
                ':no_telepon'   => $no_telepon,
                ':divisi'       => $divisi,
                ':tanggal_masuk'=> $tanggal_masuk,
                ':gaji'         => $gaji,
                ':id'           => $id
            ]);

            $_SESSION['success_message'] = "Data karyawan berhasil diperbarui!";
            header("Location: index.php");
            exit;

        } catch (PDOException $e) {
            $_SESSION['errors'] = ["Gagal memperbarui data: " . $e->getMessage()];
            header("Location: edit.php?id=" . $id);
            exit;
        }
    }

} else {
    $_SESSION['error_message'] = "Akses tidak sah.";
    header("Location: index.php");
    exit;
}
?>