<?php
require 'config.php';
require_login();

$page_title = 'Data Produk';

// Pencarian sederhana
$cari = trim($_GET['cari'] ?? '');
if ($cari !== '') {
    $stmt = $pdo->prepare('SELECT * FROM produk WHERE nama_produk LIKE ? ORDER BY id DESC');
    $stmt->execute(['%' . $cari . '%']);
} else {
    $stmt = $pdo->query('SELECT * FROM produk ORDER BY id DESC');
}
$produk = $stmt->fetchAll();

require 'partials/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">Daftar Produk</h4>
    <a href="tambah.php" class="btn btn-success">+ Tambah Produk</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-sm-8 col-md-5">
        <input type="text" name="cari" class="form-control" placeholder="Cari nama produk..."
               value="<?= e($cari) ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Cari</button>
        <?php if ($cari !== ''): ?>
            <a href="index.php" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Produk</th>
                    <th class="text-end">Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center" style="width:160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$produk): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
            <?php endif; ?>

            <?php foreach ($produk as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($p['nama_produk']) ?></td>
                    <td class="text-end">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= (int)$p['stok'] ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                        <form action="hapus.php" method="post" class="d-inline"
                              onsubmit="return confirm('Yakin hapus produk ini?')">
                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'partials/footer.php'; ?>
