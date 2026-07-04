<?php
declare(strict_types=1);

/**
 * Delhivery courier integration helpers.
 * All functions gracefully no-op / return an error array when
 * DELHIVERY_API_KEY is not configured, so the rest of the app keeps working
 * without the integration being set up.
 */

function delhiveryIsConfigured(): bool {
    return DELHIVERY_API_KEY !== '';
}

function delhiveryRequest(string $method, string $path, array $data = []): array {
    if (!delhiveryIsConfigured()) {
        return ['success' => false, 'error' => 'Delhivery API key not configured'];
    }

    $url = rtrim(DELHIVERY_API_BASE, '/') . $path;
    $ch  = curl_init();

    $headers = [
        'Authorization: Token ' . DELHIVERY_API_KEY,
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    if ($method === 'GET' && !empty($data)) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($data);
    }

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => $headers,
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['format' => 'json', 'data' => json_encode($data)]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . DELHIVERY_API_KEY,
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
        ]);
    }

    $response = curl_exec($ch);
    $errno    = curl_errno($ch);
    $error    = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno) {
        return ['success' => false, 'error' => "Delhivery request failed: {$error}"];
    }

    $decoded = json_decode((string)$response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return ['success' => true, 'data' => $decoded ?? $response];
    }

    return ['success' => false, 'error' => 'Delhivery API error (HTTP ' . $httpCode . ')', 'raw' => $decoded ?? $response];
}

/** Check if a pincode is serviceable by Delhivery. */
function delhiveryCheckPincode(string $pincode): array {
    if (!delhiveryIsConfigured()) {
        return ['success' => false, 'error' => 'Delhivery API key not configured'];
    }
    return delhiveryRequest('GET', '/c/api/pin-codes/json/', ['filter_codes' => $pincode]);
}

/**
 * Create a Delhivery shipment (waybill) for an order.
 * Expects: order (array with order_number,total,payment_method), address (array with name,phone,address_line1,address_line2,city,state,pincode), items (array)
 */
function delhiveryCreateShipment(array $order, array $address, array $items): array {
    if (!delhiveryIsConfigured()) {
        return ['success' => false, 'error' => 'Delhivery is not configured. Set DELHIVERY_API_KEY to enable shipment creation.'];
    }

    $codAmount = (($order['payment_method'] ?? 'cod') === 'cod') ? (float)($order['total'] ?? 0) : 0;

    $shipment = [
        'name'             => $address['name'] ?? '',
        'add'              => trim(($address['address_line1'] ?? '') . ' ' . ($address['address_line2'] ?? '')),
        'city'             => $address['city'] ?? '',
        'state'            => $address['state'] ?? '',
        'country'          => $address['country'] ?? 'India',
        'phone'            => $address['phone'] ?? '',
        'pin'              => $address['pincode'] ?? '',
        'order'            => $order['order_number'] ?? '',
        'payment_mode'     => (($order['payment_method'] ?? 'cod') === 'cod') ? 'COD' : 'Prepaid',
        'cod_amount'       => $codAmount,
        'total_amount'     => (float)($order['total'] ?? 0),
        'quantity'         => (string)array_sum(array_map(fn($i) => (int)($i['quantity'] ?? 1), $items)),
        'products_desc'    => implode(', ', array_map(fn($i) => $i['product_name'] ?? '', $items)),
        'shipment_width'   => '10',
        'shipment_height'  => '10',
        'weight'           => '0.5',
    ];

    $payload = [
        'shipments'      => [$shipment],
        'pickup_location' => [
            'name' => DELHIVERY_PICKUP_NAME,
            'pin'  => DELHIVERY_PICKUP_PINCODE,
        ],
    ];

    $result = delhiveryRequest('POST', '/api/cmu/create.json', $payload);

    if (!$result['success']) {
        return $result;
    }

    $pkg = $result['data']['packages'][0] ?? null;
    if (!$pkg || empty($pkg['waybill'])) {
        return ['success' => false, 'error' => 'Delhivery did not return a waybill number', 'raw' => $result['data']];
    }

    return [
        'success'     => true,
        'awb_number'  => $pkg['waybill'],
        'status'      => $pkg['status'] ?? 'Manifested',
    ];
}

/** Fetch live tracking status for a Delhivery AWB (waybill) number. */
function delhiveryTrackShipment(string $awbNumber): array {
    if (!delhiveryIsConfigured()) {
        return ['success' => false, 'error' => 'Delhivery API key not configured'];
    }
    $result = delhiveryRequest('GET', '/api/v1/packages/json/', ['waybill' => $awbNumber]);
    if (!$result['success']) return $result;

    $shipment = $result['data']['ShipmentData'][0]['Shipment'] ?? null;
    if (!$shipment) {
        return ['success' => false, 'error' => 'No tracking data found for this AWB'];
    }

    return [
        'success'                => true,
        'status'                 => $shipment['Status']['Status'] ?? null,
        'status_type'            => $shipment['Status']['StatusType'] ?? null,
        'expected_delivery_date' => $shipment['ExpectedDeliveryDate'] ?? null,
        'raw'                    => $shipment,
    ];
}
