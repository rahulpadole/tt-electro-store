<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Output Buffering — catches any stray output (notices, warnings, BOM)
| before JSON headers are sent. Critical for Hostinger / Apache deployments.
|--------------------------------------------------------------------------
*/
ob_start();

/*
|--------------------------------------------------------------------------
| Timezone — set IST so strtotime() matches datetime strings stored in the
| DB. Without this, Hostinger (UTC) shifts all date comparisons by 5.5 hrs.
|--------------------------------------------------------------------------
*/
date_default_timezone_set('Asia/Kolkata');

/*
|--------------------------------------------------------------------------
| Error Display — never show PHP errors in the response body on production.
| Errors in the output stream cause "Unexpected end of JSON input".
|--------------------------------------------------------------------------
*/
$_appEnv = getenv('APP_ENV') ?: 'production';
if ($_appEnv === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '0'); // log only, never echo — even in dev
    ini_set('log_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

/*
|--------------------------------------------------------------------------
| Load .env
|--------------------------------------------------------------------------
*/

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (str_contains($line, '=')) {

            [$key, $value] = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            if ($key !== '' && getenv($key) === false) {

                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;

            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Core Files
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

require_once __DIR__ . '/helpers/response.php';
require_once __DIR__ . '/helpers/sanitize.php';
require_once __DIR__ . '/helpers/validator.php';
require_once __DIR__ . '/helpers/pagination.php';
require_once __DIR__ . '/helpers/delhivery.php';
require_once __DIR__ . '/helpers/notifications.php';
require_once __DIR__ . '/helpers/ShipmentService.php';

/*
|--------------------------------------------------------------------------
| Load Models
|--------------------------------------------------------------------------
*/

$modelFiles = glob(__DIR__ . '/models/*.php');

if ($modelFiles === false || empty($modelFiles)) {

    die(json_encode([
        "success" => false,
        "message" => "No model files found.",
        "path" => __DIR__ . "/models/"
    ]));

}

foreach ($modelFiles as $model) {
    require_once $model;
}

/*
|--------------------------------------------------------------------------
| Debug (Temporary)
|--------------------------------------------------------------------------
*/

if (!class_exists('BrandModel')) {

    die(json_encode([
        "success" => false,
        "message" => "BrandModel class not loaded.",
        "expected_file" => __DIR__ . "/models/BrandModel.php",
        "loaded_files" => $modelFiles
    ], JSON_PRETTY_PRINT));

}

if (!class_exists('ProductModel')) {

    die(json_encode([
        "success" => false,
        "message" => "ProductModel class not loaded."
    ], JSON_PRETTY_PRINT));

}

/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

startSession();

/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
*/

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');