<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

$file = $_FILES['image'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $codes = [
        UPLOAD_ERR_INI_SIZE   => 'File too large (server limit)',
        UPLOAD_ERR_FORM_SIZE  => 'File too large',
        UPLOAD_ERR_PARTIAL    => 'Upload interrupted',
        UPLOAD_ERR_NO_FILE    => 'No file selected',
        UPLOAD_ERR_NO_TMP_DIR => 'Temp directory missing',
        UPLOAD_ERR_CANT_WRITE => 'Cannot write file',
        UPLOAD_ERR_EXTENSION  => 'Upload blocked by extension',
    ];
    $msg = $codes[$file['error'] ?? UPLOAD_ERR_NO_FILE] ?? 'Upload failed';
    jsonError($msg, 422);
}

// Validate size (10 MB max)
if ($file['size'] > MAX_UPLOAD_SIZE) {
    jsonError('Image must be under 10 MB', 422);
}

// Validate MIME type (read actual bytes, not Content-Type header)
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
if (!isset($allowed[$mime])) {
    jsonError('Only JPEG, PNG, WebP and GIF images are allowed', 422);
}

$ext     = $allowed[$mime];
$destDir = UPLOAD_DIR . 'products/';
if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

$filename = 'prod_' . bin2hex(random_bytes(10)) . '.' . $ext;
$destPath = $destDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    jsonError('Failed to save image', 500);
}

$url = UPLOAD_URL . 'products/' . $filename;
jsonSuccess(['url' => $url], 'Image uploaded successfully');
