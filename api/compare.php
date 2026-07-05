<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

if (!isGet()) jsonError('Method not allowed', 405);

$idsParam = $_GET['ids'] ?? '';
$ids = array_filter(array_map('intval', explode(',', $idsParam)));
$ids = array_slice(array_unique($ids), 0, 4);

if (empty($ids)) jsonSuccess([]);

$pm = new ProductModel();
$products = [];
foreach ($ids as $id) {
    $p = $pm->findById($id);
    if ($p) $products[] = $p;
}

jsonSuccess($products);
