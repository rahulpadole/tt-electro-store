<?php
declare(strict_types=1);
requireAdmin();

if (!isPost()) jsonError('Method not allowed', 405);

$d = getJsonBody();
$v = Validator::make($d)->required('ids')->required('status');
if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

$ids = array_filter(array_map('intval', (array)$d['ids']));
$status = $d['status'];
$allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
if (!in_array($status, $allowed, true)) jsonError('Invalid status', 422);
if (empty($ids)) jsonError('No order ids provided', 422);

$om = new OrderModel();
$updated = $om->bulkUpdateStatus($ids, $status);

jsonSuccess(['updated' => $updated], "{$updated} order(s) updated");
