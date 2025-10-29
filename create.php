<?php
// File: create.php
require 'includes/header.php';

// Ambil data lama jika ada validasi gagal (dari save.php)
$old = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

// Pesan error validasi sekarang ditangani di header.php
?>

<h2>Tambah Karyawan Baru</h2>

<form action="save.php" method="POST" class="form-card">
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($old['nama_lengkap'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="no_telepon">No Telepon (Opsional)</label>
        <input type="text" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($old['no_telepon'] ?? '') ?>">
    </div>
    
    <div class="form-group">
        <label for="divisi">Divisi</label>
        <select id="divisi" name="divisi" required>
            <option value="" disabled <?= !isset($old['divisi']) ? 'selected' : '' ?>>-- Pilih Divisi --</option>
            <option value="Engineering" <?= ($old['divisi'] ?? '') == 'Engineering' ? 'selected' : '' ?>>Engineering</option>
            <option value="Marketing" <?= ($old['divisi'] ?? '') == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
            <option value="Sales" <?= ($old['divisi'] ?? '') == 'Sales' ? 'selected' : '' ?>>Sales</option>
            <option value="Human Resources" <?= ($old['divisi'] ?? '') == 'Human Resources' ? 'selected' : '' ?>>Human Resources</option>
            <option value="Finance" <?= ($old['divisi'] ?? '') == 'Finance' ? 'selected' : '' ?>>Finance</option>
        </select>
    </div>

    <div class="form-group">
        <label for="tanggal_masuk">Tanggal Masuk</label>
        <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?= htmlspecialchars($old['tanggal_masuk'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="gaji">Gaji Pokok</label>
        <input type="number" id="gaji" name="gaji" step="0.01" min="0" value="<?= htmlspecialchars($old['gaji'] ?? '') ?>" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Simpan Data</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
</form>

<?php
require 'includes/footer.php';
?>