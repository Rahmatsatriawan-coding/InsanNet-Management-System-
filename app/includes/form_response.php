<?php

function redirect_form_result(string $status, string $fallbackPath): void
{
    $targetPath = $fallbackPath;
    $targetQuery = '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($referer !== '') {
        $refererPath = parse_url($referer, PHP_URL_PATH);
        $refererQuery = parse_url($referer, PHP_URL_QUERY);
        $publicPrefix = public_base_path() . '/';
        $segments = is_string($refererPath) ? explode('/', rawurldecode($refererPath)) : [];
        $hasTraversal = in_array('..', $segments, true) || in_array('.', $segments, true);

        if (is_string($refererPath)
            && strpos($refererPath, $publicPrefix) === 0
            && !$hasTraversal) {
            $targetPath = $refererPath;
            $targetQuery = is_string($refererQuery) ? $refererQuery : '';
        }
    }

    $query = $targetQuery === '' ? '' : $targetQuery . '&';
    header('Location: ' . $targetPath . '?' . $query . 'status=' . rawurlencode($status));
    exit;
}

function require_form_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('405 - Metode tidak diizinkan.');
    }
}

function form_transaction(mysqli $koneksi, callable $operation)
{
    $koneksi->begin_transaction();
    try {
        $result = $operation();
        $koneksi->commit();
        return $result;
    } catch (Throwable $exception) {
        $koneksi->rollback();
        throw $exception;
    }
}

function form_text_length_valid(string $value, int $maxLength): bool
{
    return preg_match('/\A.{0,' . $maxLength . '}\z/us', $value) === 1;
}
