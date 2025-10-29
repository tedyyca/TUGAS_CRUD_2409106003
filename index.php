<?php
require 'config/database.php';
require 'includes/header.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = '';
$params = [];

if (!empty($search)) {
    $where_clause = " WHERE nama_lengkap LIKE :search_nama 
                      OR email LIKE :search_email 
                      OR divisi LIKE :search_divisi";
    
    $search_term = "%$search%";
    $params = [
        ':search_nama'   => $search_term,
        ':search_email'  => $search_term,
        ':search_divisi' => $search_term
    ];
}

try {
    $sql_count = "SELECT COUNT(id) FROM employees" . $where_clause;
    $stmt_count = $pdo->prepare($sql_count);
    
    $stmt_count->execute($params); 
    
    $total_rows = $stmt_count->fetchColumn();
    $total_pages = ceil($total_rows / $limit);

} catch (PDOException $e) {
    echo '<div class="message error">Error (Query 1): ' . $e->getMessage() . '</div>';
    $total_rows = 0;
    $total_pages = 0;
}

$karyawan_list = [];
if ($total_rows > 0) {
    try {
        $sql_data = "SELECT id, nama_lengkap, email, divisi, tanggal_masuk 
                     FROM employees" 
                     . $where_clause .
                     " ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset";
        
        $stmt_data = $pdo->prepare($sql_data);
        
        foreach ($params as $key => &$val) {
            $stmt_data->bindParam($key, $val, PDO::PARAM_STR);
        }
        
        $stmt_data->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt_data->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt_data->execute(); 
        
        $karyawan_list = $stmt_data->fetchAll();
        
    } catch (PDOException $e) {
        echo '<div class="message error">Error (Query 2): ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Daftar Karyawan</h2>

<form action="index.php" method="GET" class="form-search">
    <div class="form-group">
        <input type="text" id="search" name="search" placeholder="Cari berdasarkan nama, email, atau divisi..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn">Cari</button>
        <?php if (!empty($search)): ?>
            <a href="index.php" class="btn btn-warning">Reset</a>
        <?php endif; ?>
    </div>
</form>

<style>
    .form-search .form-group { display: flex; gap: 10px; }
    .form-search .form-group input[type="text"] { flex-grow: 1; }
</style>

<?php if (empty($karyawan_list) && $total_rows > 0): ?>
    <div class="message info">Tidak ada data di halaman ini.</div>
<?php elseif (empty($karyawan_list) && $total_rows == 0 && empty($search)): ?>
    <div class="message info">Belum ada data karyawan. <a href="create.php">Tambah data baru</a>.</div>
<?php elseif (empty($karyawan_list) && $total_rows == 0 && !empty($search)): ?>
    <div class="message info">Data tidak ditemukan untuk keyword: "<?= htmlspecialchars($search) ?>".</div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Divisi</th>
                <th>Tanggal Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $nomor = $offset + 1; ?>
            <?php foreach ($karyawan_list as $karyawan): ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= htmlspecialchars($karyawan['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($karyawan['email']) ?></td>
                <td><?= htmlspecialchars($karyawan['divisi']) ?></td>
                <td><?= htmlspecialchars(date('d F Y', strtotime($karyawan['tanggal_masuk']))) ?></td>
                <td class="action-links">
                    <a href="detail.php?id=<?= $karyawan['id'] ?>" class="btn btn-primary">Detail</a>
                    <a href="edit.php?id=<?= $karyawan['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $karyawan['id'] ?>" class="btn btn-danger btn-hapus">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="current"><?= $i ?></span>
            <?php else: ?>
                <a href="index.php?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <a href="index.php?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>">Next</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

<?php endif; ?>

<?php
require 'includes/footer.php';
?>