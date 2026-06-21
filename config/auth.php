<?php
declare(strict_types=1);

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => 86400 * 7,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function getCurrentUser(): ?array {
    startSession();
    return $_SESSION['user'] ?? null;
}

function getCurrentUserId(): int {
    $user = getCurrentUser();
    return $user ? (int)$user['id'] : GUEST_USER_ID;
}

function isLoggedIn(): bool {
    return getCurrentUser() !== null;
}

function isAdmin(): bool {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

function setCurrentUser(array $user): void {
    startSession();
    session_regenerate_id(true);
    $_SESSION['user'] = $user;
}

function logoutUser(): void {
    startSession();
    $_SESSION = [];
    session_destroy();
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        if (isApiRequest()) {
            jsonError('Unauthorized', 401);
        }
        redirect('/login');
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        if (isApiRequest()) {
            jsonError('Forbidden', 403);
        }
        redirect('/admin/login');
    }
}

function isApiRequest(): bool {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return str_starts_with($path, '/api/');
}

function redirect(string $url): never {
    header("Location: {$url}");
    exit;
}

function generateCsrfToken(): string {
    startSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    startSession();
    $stored = $_SESSION['csrf_token'] ?? '';
    return hash_equals($stored, $token);
}
