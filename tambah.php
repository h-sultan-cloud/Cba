<?php
require 'config.php';
require_login();

$page_title = 'Tambah Produk';
$errors = [];
$nama = '';
$harga = '';
$stok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $nama  = trim($_POST['nama_produk'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok  = trim($_POST['stok'] ?? '');

    if ($nama === '')                          $errors[] = 'Nama produk wajib diisi.';
    if (!ctype_digit($harga))                  $errors[] = 'Harga harus berupa angka bulat (0 atau lebih).';
    if (!ctype_digit($stok))                   $errors[] = 'Stok harus berupa angka bulat (0 atau lebih).';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO produk (nama_produk, harga, stok) VALUES (?, ?, ?)');
        $stmt->execute([$nama, $harga, $stok]);
        set_flash('success', 'Produk berhasil ditambahkan.');
        header('Location: index.php');
        exit;
    }
}

require 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">Tambah Produk</div>
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
                    <button class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'partials/footer.php'; ?>
