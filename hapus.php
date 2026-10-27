<?php
require 'config.php';
require_login();

// Hapus hanya lewat POST + token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_check();

$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM produk WHERE id = ?');
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    set_flash('success', 'Produk berhasil dihapus.');
} else {
    set_flash('danger', 'Data produk tidak ditemukan.');
}

header('Location: index.php');
exit;
