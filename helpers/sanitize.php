<?php
declare(strict_types=1);

function clean(mixed $value): string {
    return htmlspecialchars(trim((string)$value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function cleanArr(array $data, array $fields): array {
    $out = [];
    foreach ($fields as $f) {
        $out[$f] = isset($data[$f]) ? clean($data[$f]) : '';
    }
    return $out;
}

function slug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// MySQL stores arrays as JSON; decode them safely
function decodeJsonArray(?string $val): array {
    if ($val === null || $val === '') return [];
    $decoded = json_decode($val, true);
    return is_array($decoded) ? $decoded : [];
}

function normalizeProduct(array $row): array {
    if (isset($row['images']) && is_string($row['images'])) {
        $row['images'] = decodeJsonArray($row['images']);
    }
    if (isset($row['tags']) && is_string($row['tags'])) {
        $row['tags'] = decodeJsonArray($row['tags']);
    }
    if (isset($row['specifications']) && is_string($row['specifications'])) {
        $decoded = json_decode($row['specifications'], true);
        $row['specifications'] = $decoded ?? $row['specifications'];
    }
    // Cast booleans
    foreach (['is_featured','is_trending','is_best_seller','is_flash_sale','is_active'] as $b) {
        if (isset($row[$b])) $row[$b] = (bool)(int)$row[$b];
    }
    return $row;
}

function normalizeBlog(array $row): array {
    if (isset($row['tags']) && is_string($row['tags'])) {
        $row['tags'] = decodeJsonArray($row['tags']);
    }
    return $row;
}

function normalizeDiyKit(array $row): array {
    if (isset($row['images']) && is_string($row['images'])) {
        $row['images'] = decodeJsonArray($row['images']);
    }
    if (isset($row['components']) && is_string($row['components'])) {
        $row['components'] = decodeJsonArray($row['components']);
    }
    return $row;
}

function normalizeOrder(array $row): array {
    if (isset($row['status_timeline']) && is_string($row['status_timeline'])) {
        $row['status_timeline'] = json_decode($row['status_timeline'], true) ?? [];
    }
    if (isset($row['shipping_address']) && is_string($row['shipping_address'])) {
        $decoded = json_decode($row['shipping_address'], true);
        if ($decoded) $row['shipping_address'] = $decoded;
    }
    return $row;
}
