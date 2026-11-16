<?php

require_once dirname(__DIR__) . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('405 - Metode tidak diizinkan.');
}

verify_csrf_token();

session_unset();
if (ini_get('session.use_cookies')) {
    $cookie = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookie['path'],
        $cookie['domain'],
        $cookie['secure'],
        $cookie['httponly']
    );
}
session_destroy();

header('Location: ../../public/login.html?logout=1');
exit;
