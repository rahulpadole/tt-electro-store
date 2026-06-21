<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly with correct MIME types
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    $mime = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
        'json' => 'application/json',
        'pdf'  => 'application/pdf',
    ];
    if (isset($mime[$ext])) {
        header('Content-Type: ' . $mime[$ext]);
    }
    return false; // let built-in server handle the file
}

// Route everything else through the front controller
require __DIR__ . '/index.php';
