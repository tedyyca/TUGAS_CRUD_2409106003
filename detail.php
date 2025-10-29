<?php
require 'config/database.php';
require 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    $_SESSION['error_message'] = "ID karyawan tidak valid.";
    header("Location: index.php");
    exit;
}

try {
    $sql = "SELECT * FROM employees WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $karyawan = $stmt->fetch();

    if (!$karyawan) {
        $_SESSION['error_message'] = "Data karyawan tidak ditemukan.";
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
?>

<h2>Detail Karyawan: <?= htmlspecialchars($karyawan['nama_lengkap']) ?></h2>

<div class="detail-view">
    <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($karyawan['nama_lengkap']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($karyawan['email']) ?></p>
    <p><strong>No Telepon:</strong> <?= htmlspecialchars($karyawan['no_telepon'] ?: '-') ?></p>
    <p><strong>Divisi:</strong> <?= htmlspecialchars($karyawan['divisi']) ?></p>
    <p><strong>Tanggal Masuk:</strong> <?= htmlspecialchars(date('d F Y', strtotime($karyawan['tanggal_masuk']))) ?></p>
    <p><strong>Gaji Pokok:</strong> Rp <?= htmlspecialchars(number_format($karyawan['gaji'], 2, ',', '.')) ?></p>
    <p><strong>Data Dibuat Pada:</strong> <?= htmlspecialchars(date('d F Y H:i:s', strtotime($karyawan['created_at']))) ?></p>
</div>

<div style="margin-top: 20px;">
    <a href="edit.php?id=<?= $karyawan['id'] ?>" class="btn btn-warning">Edit Data</a>
    <a href="index.php" class="btn btn-secondary">Kembali ke Daftar</a>
</div>

<?php
require 'includes/footer.php';
?>