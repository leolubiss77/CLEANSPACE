<?php
if (session_status() === PHP_SESSION_NONE) {
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
