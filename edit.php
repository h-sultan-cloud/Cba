<?php
require 'config.php';
require_login();

$page_title = 'Edit Produk';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM produk WHERE id = ?');
$stmt->execute([$id]);
$produk = $stmt->fetch();

if (!$produk) {
    set_flash('danger', 'Data produk tidak ditemukan.');
    header('Location: index.php');
    exit;
}

$errors = [];
$nama  = $produk['nama_produk'];
$harga = $produk['harga'];
$stok  = $produk['stok'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $nama  = trim($_POST['nama_produk'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok  = trim($_POST['stok'] ?? '');

    if ($nama === '')          $errors[] = 'Nama produk wajib diisi.';
    if (!ctype_digit($harga))  $errors[] = 'Harga harus berupa angka bulat (0 atau lebih).';
    if (!ctype_digit($stok))   $errors[] = 'Stok harus berupa angka bulat (0 atau lebih).';

    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE produk SET nama_produk = ?, harga = ?, stok = ? WHERE id = ?');
        $stmt->execute([$nama, $harga, $stok, $id]);
        set_flash('success', 'Produk berhasil diperbarui.');
        header('Location: index.php');
        exit;
    }
}

require 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">Edit Produk</div>
            <div class="card-body">

                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= e($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$id ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= e($nama) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" min="0" value="<?= e($harga) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" min="0" value="<?= e($stok) ?>" required>
                    </div>
                    <button class="btn btn-warning">Update</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'partials/footer.php'; ?>
