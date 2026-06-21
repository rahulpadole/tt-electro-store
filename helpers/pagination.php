<?php
declare(strict_types=1);

function paginate(PDO $db, string $query, array $params, int $page, int $perPage = ITEMS_PER_PAGE): array {
    $page   = max(1, $page);
    $offset = ($page - 1) * $perPage;

    // Count total
    $countSql = "SELECT COUNT(*) as total FROM ({$query}) as sub";
    $st = $db->prepare($countSql);
    $st->execute($params);
    $total = (int)($st->fetch()['total'] ?? 0);

    // Fetch page
    $st = $db->prepare("{$query} LIMIT {$perPage} OFFSET {$offset}");
    $st->execute($params);
    $items = $st->fetchAll();

    return [
        'items'       => $items,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $perPage,
        'total_pages' => (int)ceil($total / $perPage),
    ];
}
