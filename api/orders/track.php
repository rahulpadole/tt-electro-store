<?php declare(strict_types=1);

if (isGet()) {
    // Allow GET with query params too
    $_POST['order_number'] = $_GET['q'] ?? $_GET['order_number'] ?? '';
    $_POST['email']        = $_GET['email'] ?? '';
}

if (!isPost() && !isGet()) jsonError('Method not allowed', 405);

$d           = getJsonBody();
$orderNumber = trim(strtoupper($d['order_number'] ?? $_POST['order_number'] ?? $_GET['q'] ?? ''));
$awbInput    = trim(strtoupper($d['awb'] ?? $_GET['awb'] ?? ''));
$email       = trim($d['email'] ?? $_POST['email'] ?? '');

$om    = new OrderModel();
$order = null;

// ── Find by AWB number first if that's what was typed ──────────────────────
if ($awbInput && empty($orderNumber)) {
    $order = $om->findByAwb($awbInput);
} elseif ($orderNumber) {
    // Could be TTE-XXXXXXXX or a raw AWB number
    if (str_starts_with($orderNumber, 'TTE-') || str_starts_with($orderNumber, 'TTE')) {
        $order = $om->findByOrderNumber($orderNumber);
    } else {
        // Might be an AWB number typed into order field
        $order = $om->findByOrderNumber($orderNumber) ?? $om->findByAwb($orderNumber);
    }
}

if (!$order) {
    jsonError('No order found. Check your order number or AWB and try again.', 404);
}

// ── Optionally verify email ────────────────────────────────────────────────
if (!empty($email)) {
    $user = (new UserModel())->findById((int)$order['user_id']);
    if ($user && strtolower($user['email']) !== strtolower($email)) {
        jsonError('Order not found. Check your details and try again.', 404);
    }
}

// ── Fetch live Delhivery tracking if AWB exists ───────────────────────────
$delhiveryScans    = [];
$delhiveryStatus   = null;
$expectedDelivery  = $order['expected_delivery_date'] ?? null;

$awb = $order['awb_number'] ?? '';
if (!empty($awb) && function_exists('delhiveryTrackShipment') && delhiveryIsConfigured()) {
    $result = delhiveryTrackShipment($awb);
    if ($result['success'] && !empty($result['raw'])) {
        $raw               = $result['raw'];
        $delhiveryStatus   = $result['status'] ?? null;
        $expectedDelivery  = $result['expected_delivery_date'] ?? $expectedDelivery;

        // Parse scan events from Delhivery into a clean array
        $scans = $raw['Scans'] ?? [];
        foreach ($scans as $s) {
            $sd = $s['ScanDetail'] ?? $s;
            $loc = $sd['ScannedLocation'] ?? $sd['ScanLocation'] ?? '';
            $delhiveryScans[] = [
                'time'        => $sd['ScanDateTime']   ?? $sd['StatusDateTime'] ?? '',
                'status'      => $sd['Scan']            ?? $sd['ScanType']       ?? '',
                'instruction' => $sd['Instructions']    ?? '',
                'location'    => $loc,
            ];
        }
        // Sort newest first
        usort($delhiveryScans, fn($a,$b) => strcmp($b['time'], $a['time']));
    }
}

// ── Build unified response ────────────────────────────────────────────────
$out = [
    'id'                    => $order['id'],
    'order_number'          => $order['order_number']      ?? '',
    'status'                => $order['status']            ?? 'pending',
    'payment_status'        => $order['payment_status']    ?? 'pending',
    'created_at'            => $order['created_at']        ?? '',
    'total'                 => $order['total']             ?? 0,
    'items_count'           => count($order['items'] ?? []),
    // Delhivery fields
    'awb_number'            => $awb,
    'delivery_partner'      => $order['delivery_partner']  ?? '',
    'delivery_status'       => $delhiveryStatus ?? $order['delivery_status'] ?? '',
    'expected_delivery_date'=> $expectedDelivery,
    // Live scan timeline from Delhivery
    'delhivery_scans'       => $delhiveryScans,
    // Shipping address city/state for display
    'ship_city'             => is_array($order['shipping_address'])
                                 ? ($order['shipping_address']['city'] ?? '')
                                 : '',
];

jsonSuccess($out);
