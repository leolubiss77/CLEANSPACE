<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='font-family:monospace;background:#0f172a;color:#a3e635;padding:2rem;'>";

// 1. PHP & environment
echo "=== PHP & ENVIRONMENT ===\n";
echo "PHP Version     : " . PHP_VERSION . "\n";
echo "HTTPS           : " . ($_SERVER['HTTPS'] ?? 'not set') . "\n";
echo "X-Forwarded-Proto: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set') . "\n";
echo "RAILWAY_ENV     : " . (getenv('RAILWAY_ENVIRONMENT') ?: 'not set') . "\n";
echo "CWD             : " . getcwd() . "\n";
echo "\n";

// 2. Database paths
echo "=== DATABASE PATHS ===\n";
$dbPath = __DIR__ . '/database/cleanspace.db';
echo "DB Path         : $dbPath\n";
echo "DB Exists       : " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
if (file_exists($dbPath)) {
    echo "DB Writable     : " . (is_writable($dbPath) ? 'YES' : 'NO') . "\n";
    echo "DB Size         : " . filesize($dbPath) . " bytes\n";
}
echo "Dir Writable    : " . (is_writable(__DIR__ . '/database') ? 'YES' : 'NO') . "\n";
echo "/tmp writable   : " . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";
echo "\n";

// 3. Try opening DB
echo "=== DATABASE CONNECTION ===\n";
try {
    $db = new SQLite3($dbPath);
    echo "SQLite open     : OK\n";
    echo "SQLite version  : " . SQLite3::version()['versionString'] . "\n";

    $total = $db->querySingle("SELECT COUNT(*) FROM users");
    echo "Total users     : $total\n";

    $admin = $db->querySingle("SELECT id, email, role, password FROM users WHERE email='admin@cleanspace.com'", true);
    if ($admin) {
        echo "Admin found     : YES (id={$admin['id']}, role={$admin['role']})\n";
        echo "Hash length     : " . strlen($admin['password']) . "\n";
        echo "Hash preview    : " . substr($admin['password'], 0, 7) . "...\n";
        $ok = password_verify('admin123', $admin['password']);
        echo "password_verify : " . ($ok ? "TRUE ✓" : "FALSE ✗") . "\n";
    } else {
        echo "Admin found     : NO\n";
    }
} catch (Exception $e) {
    echo "DB ERROR        : " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Session test
echo "=== SESSION ===\n";
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
echo "isHttps         : " . ($isHttps ? 'true' : 'false') . "\n";
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
if ($isHttps) ini_set('session.cookie_secure', 1);
session_start();
$_SESSION['test'] = 'hello';
echo "Session ID      : " . session_id() . "\n";
echo "Session save    : " . session_save_path() . "\n";
echo "Session test    : " . ($_SESSION['test'] ?? 'MISSING') . "\n";
echo "\n";

echo "=== DONE ===\n";
echo "</pre>";
