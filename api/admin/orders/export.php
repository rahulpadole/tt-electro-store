<?php
declare(strict_types=1);
requireAdmin();

$om = new OrderModel();
$filters = [
    'status'         => $_GET['status'] ?? '',
    'payment_method' => $_GET['payment_method'] ?? '',
    'search'         => $_GET['search'] ?? '',
    'date_from'      => $_GET['date_from'] ?? '',
    'date_to'        => $_GET['date_to'] ?? '',
];

$orders = $om->searchAll($filters);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="orders-export-' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Order Number', 'Customer', 'Email', 'Phone', 'Date', 'Total', 'Payment Method', 'Payment Status', 'Status', 'AWB Number', 'Delivery Status']);

foreach ($orders as $o) {
    fputcsv($out, [
        $o['order_number'],
        $o['user_name'] ?? '',
        $o['user_email'] ?? '',
        $o['user_phone'] ?? '',
        $o['created_at'],
        $o['total'],
        $o['payment_method'] ?? '',
        $o['payment_status'] ?? '',
        $o['status'],
        $o['awb_number'] ?? '',
        $o['delivery_status'] ?? '',
    ]);
}

fclose($out);
exit;
