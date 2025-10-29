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


$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<h2>Edit Data Karyawan</h2>

<?php if (!empty($errors)): ?>
    <div class="message error">
        <strong>Validasi Gagal:</strong>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $karyawan['id'] ?>">
    
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($karyawan['nama_lengkap']) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($karyawan['email']) ?>" required>
    </div>
    <div class="form-group">
        <label for="no_telepon">No Telepon (Opsional)</label>
        <input type="text" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($karyawan['no_telepon']) ?>">
    </div>
    
    <div class="form-group">
        <label for="divisi">Divisi</label>
        <select id="divisi" name="divisi" required>
            <option value="" disabled>-- Pilih Divisi --</option>
            
            <option value="Engineering" <?= $karyawan['divisi'] == 'Engineering' ? 'selected' : '' ?>>
                Engineering
            </option>
            <option value="Marketing" <?= $karyawan['divisi'] == 'Marketing' ? 'selected' : '' ?>>
                Marketing
            </option>
            <option value="Sales" <?= $karyawan['divisi'] == 'Sales' ? 'selected' : '' ?>>
                Sales
            </option>
            <option value="Human Resources" <?= $karyawan['divisi'] == 'Human Resources' ? 'selected' : '' ?>>
                Human Resources
            </option>
            <option value="Finance" <?= $karyawan['divisi'] == 'Finance' ? 'selected' : '' ?>>
                Finance
            </option>
        </select>
    </div>
    <div class="form-group">
        <label for="tanggal_masuk">Tanggal Masuk</label>
        <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?= htmlspecialchars($karyawan['tanggal_masuk']) ?>" required>
    </div>
    <div class="form-group">
        <label for="gaji">Gaji Pokok</label>
        <input type="number" id="gaji" name="gaji" step="0.01" min="0" value="<?= htmlspecialchars($karyawan['gaji']) ?>" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Update Data</button>
        <a href="index.php" class="btn">Batal</a>
    </div>
</form>

<?php
require 'includes/footer.php';
?>