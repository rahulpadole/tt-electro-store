<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';


startSession();

// ── GOOGLE CLIENT ID ─────────────────────────────
$clientId = getenv('GOOGLE_CLIENT_ID') ?: '';

if (empty($clientId)) {
    $_SESSION['flash_error'] = 'Google login not configured.';
    redirect('/login');
}

// ── STATE (CSRF PROTECTION) ──────────────────────
$state = bin2hex(random_bytes(16));
$_SESSION['google_oauth_state'] = $state;

// ── REDIRECT AFTER LOGIN ─────────────────────────
$_SESSION['google_oauth_redirect'] = $_GET['redirect'] ?? '/dashboard';

// ── FIXED CALLBACK URL (IMPORTANT) ───────────────
$redirectUri = APP_URL . '/auth/google/callback';

// ── GOOGLE AUTH URL ──────────────────────────────
$params = http_build_query([
    'client_id'     => $clientId,
    'redirect_uri'  => $redirectUri,
    'response_type' => 'code',
    'scope'         => 'openid email profile',
    'access_type'   => 'online',
    'state'         => $state,
    'prompt'        => 'select_account',
]);

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
exit;