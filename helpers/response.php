<?php
declare(strict_types=1);

/**
 * Flush any buffered output (PHP notices, warnings, BOM chars) before
 * sending JSON. Without this, stray text corrupts the JSON stream and
 * causes "Unexpected end of JSON input" on the client.
 */
function _clearOutputBuffer(): void {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
}

function jsonSuccess(mixed $data = null, string $message = 'Success', int $code = 200): never {
    _clearOutputBuffer();
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
}

function jsonError(string $message = 'Error', int $code = 400, mixed $errors = null): never {
    _clearOutputBuffer();
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    $body = ['success' => false, 'message' => $message];
    if ($errors !== null) $body['errors'] = $errors;
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
}

function jsonData(mixed $data, int $code = 200): never {
    _clearOutputBuffer();
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
}

function getJsonBody(): array {
    $raw = file_get_contents('php://input');
    if (empty($raw)) return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function getMethod(): string {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function isGet(): bool  { return getMethod() === 'GET'; }
function isPost(): bool { return getMethod() === 'POST'; }
function isPatch(): bool { return getMethod() === 'PATCH'; }
function isDelete(): bool { return getMethod() === 'DELETE'; }
