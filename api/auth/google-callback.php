<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

startSession();

// ─────────────────────────────────────────────
// 1. STATE VALIDATION (SECURITY)
// ─────────────────────────────────────────────
$state        = $_GET['state'] ?? '';
$sessionState = $_SESSION['google_oauth_state'] ?? null;

unset($_SESSION['google_oauth_state']);

if (empty($state) || empty($sessionState) || !hash_equals($sessionState, $state)) {
    $_SESSION['flash_error'] = 'Security validation failed. Try again.';
    redirect('/login');
}

// ─────────────────────────────────────────────
// 2. GOOGLE ERROR CHECK
// ─────────────────────────────────────────────
if (!empty($_GET['error'])) {
    $_SESSION['flash_error'] = 'Google login cancelled.';
    redirect('/login');
}

// ─────────────────────────────────────────────
// 3. CONFIG VALIDATION
// ─────────────────────────────────────────────
$code = $_GET['code'] ?? '';

$clientId     = getenv('GOOGLE_CLIENT_ID') ?: '';
$clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: '';

// MUST MATCH google.php
$redirectUri = APP_URL . '/auth/google/callback';

if (empty($code) || empty($clientId) || empty($clientSecret)) {
    $_SESSION['flash_error'] = 'Google configuration error.';
    redirect('/login');
}

// ─────────────────────────────────────────────
// 4. GET ACCESS TOKEN
// ─────────────────────────────────────────────
$tokenData = http_build_query([
    'code'          => $code,
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri'  => $redirectUri,
    'grant_type'    => 'authorization_code',
]);

$context = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $tokenData,
        'timeout' => 10,
    ]
]);

$response = file_get_contents('https://oauth2.googleapis.com/token', false, $context);
$data = json_decode($response ?: '', true);

if (empty($data['access_token'])) {
    $_SESSION['flash_error'] = 'Google authentication failed.';
    redirect('/login');
}

// ─────────────────────────────────────────────
// 5. GET USER INFO
// ─────────────────────────────────────────────
$profileCtx = stream_context_create([
    'http' => [
        'method'  => 'GET',
        'header'  => "Authorization: Bearer {$data['access_token']}\r\n",
        'timeout' => 10,
    ]
]);

$profileResponse = file_get_contents(
    'https://www.googleapis.com/oauth2/v3/userinfo',
    false,
    $profileCtx
);

$profile = json_decode($profileResponse ?: '', true);

if (empty($profile['email'])) {
    $_SESSION['flash_error'] = 'Failed to get Google profile.';
    redirect('/login');
}

// ─────────────────────────────────────────────
// 6. USER DATA
// ─────────────────────────────────────────────
$googleId = $profile['sub'] ?? '';
$email    = strtolower(trim($profile['email']));
$name     = $profile['name'] ?? explode('@', $email)[0];
$avatar   = $profile['picture'] ?? null;

// ─────────────────────────────────────────────
// 7. DATABASE LOGIC
// ─────────────────────────────────────────────
$db = Database::getConnection();

$um = new UserModel();

$stmt = $db->prepare("SELECT * FROM users WHERE google_id = ? LIMIT 1");
$stmt->execute([$googleId]);
$user = $stmt->fetch();

if (!$user) {
    $user = $um->findByEmail($email);
}

if ($user) {

    $update = [];

    if (empty($user['google_id'])) {
        $update['google_id'] = $googleId;
    }

    if (empty($user['google_avatar']) && $avatar) {
        $update['google_avatar'] = $avatar;
    }

    if ($update) {
        $set = implode(',', array_map(fn($k) => "$k = ?", array_keys($update)));
        $vals = array_values($update);
        $vals[] = $user['id'];

        $db->prepare("UPDATE users SET {$set}, updated_at = NOW() WHERE id = ?")
            ->execute($vals);
    }

    $user = $um->findById((int)$user['id']);

} else {

    $db->prepare("
        INSERT INTO users (name, email, password, google_id, google_avatar, role, is_active)
        VALUES (?, ?, '', ?, ?, 'user', 1)
    ")->execute([$name, $email, $googleId, $avatar]);

    $newId = (int)$db->lastInsertId();
    $stNew = $db->prepare('SELECT * FROM users WHERE id=?');
    $stNew->execute([$newId]);
    $user = $stNew->fetch();
}

// ─────────────────────────────────────────────
// 8. LOGIN
// ─────────────────────────────────────────────
unset($user['password']);
setCurrentUser($user);

// ─────────────────────────────────────────────
// 9. SAFE REDIRECT
// ─────────────────────────────────────────────
$redirect = $_SESSION['google_oauth_redirect'] ?? '/dashboard';
unset($_SESSION['google_oauth_redirect']);

if (!str_starts_with($redirect, '/')) {
    $redirect = '/dashboard';
}

redirect($redirect);