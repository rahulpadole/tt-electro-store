<?php
declare(strict_types=1);
requireAdmin();

$om = new OrderModel();
$id = (int)($_GET['id'] ?? 0);
$order = $om->findById($id);
if (!$order) jsonError('Order not found', 404);

if (isPost()) {
    $d      = getJsonBody();
    $action = $d['action'] ?? 'create';

    if ($action === 'create') {
        if (!empty($order['awb_number'])) {
            jsonError('This order already has a Delhivery AWB number: ' . $order['awb_number'], 422);
        }
        $addr = is_array($order['shipping_address']) ? $order['shipping_address'] : [];
        if (empty($addr)) jsonError('Order has no shipping address', 422);

        $result = delhiveryCreateShipment($order, $addr, $order['items'] ?? []);
        if (!$result['success']) {
            jsonError($result['error'] ?? 'Failed to create Delhivery shipment', 502);
        }

        $updated = $om->updateDelhivery($id, [
            'delivery_partner' => 'Delhivery',
            'awb_number'       => $result['awb_number'],
            'delivery_status'  => $result['status'] ?? 'Manifested',
        ]);
        jsonSuccess($updated, 'Delhivery shipment created');
    }

    if ($action === 'refresh') {
        if (empty($order['awb_number'])) jsonError('No AWB number on this order yet', 422);

        $result = delhiveryTrackShipment($order['awb_number']);
        if (!$result['success']) {
            jsonError($result['error'] ?? 'Failed to fetch tracking status', 502);
        }

        $updated = $om->updateDelhivery($id, [
            'delivery_status'        => $result['status'] ?? $order['delivery_status'],
            'expected_delivery_date' => $result['expected_delivery_date'] ?? $order['expected_delivery_date'],
        ]);
        jsonSuccess($updated, 'Tracking status refreshed');
    }

    jsonError('Invalid action', 422);
}

jsonError('Method not allowed', 405);
