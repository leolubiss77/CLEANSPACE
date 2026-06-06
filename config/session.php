<?php
if (session_status() === PHP_SESSION_NONE) {
    // Pastikan session save path writable — Railway tidak set default path
    $sessionPath = sys_get_temp_dir() . '/php_sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0700, true);
    }
    session_save_path($sessionPath);

    // Railway pakai reverse proxy — HTTPS dideteksi via X-Forwarded-Proto
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');

    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    if ($isHttps) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}
