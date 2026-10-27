<?php
// ====== Konfigurasi Database ======
$db_host = 'localhost';
$db_name = 'crud_db';
$db_user = 'root';
$db_pass = '';

session_start();

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// ====== Fungsi bantu ======

// Escape output agar aman dari XSS
function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Halaman wajib login
function require_login()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

// Token CSRF
function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check()
{
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(403);
        die('Token tidak valid. Silakan kembali dan coba lagi.');
    }
}

// Pesan flash (notifikasi sekali tampil)
function set_flash($type, $msg)
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function get_flash()
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
