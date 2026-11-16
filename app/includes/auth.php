<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    $httpsEnabled = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $httpsEnabled,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function require_login(): void
{
    if (empty($_SESSION['user']['id_pengguna'])) {
        header('Location: ' . public_base_path() . '/login.html');
        exit;
    }
}

function project_base_path(): string
{
    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (preg_match('~^(.*?)/(?:public|app)(?:/|$)~', $scriptPath, $matches)) {
        return $matches[1];
    }

    return '';
}

function public_base_path(): string
{
    return project_base_path() . '/public';
}

function require_role(string ...$allowedRoles): void
{
    require_login();

    $role = $_SESSION['user']['peran'] ?? '';
    if (!in_array($role, $allowedRoles, true)) {
        http_response_code(403);
        exit('403 - Anda tidak memiliki hak akses untuk halaman ini.');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(): void
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!is_string($submittedToken) || !is_string($sessionToken)
        || $sessionToken === '' || !hash_equals($sessionToken, $submittedToken)) {
        http_response_code(403);
        exit('403 - Token formulir tidak valid. Muat ulang halaman dan coba lagi.');
    }
}

function escape_html(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
