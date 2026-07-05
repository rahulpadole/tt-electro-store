<?php
declare(strict_types=1);

/**
 * Order status / shipment notification helpers.
 * Sends email (via mail()) and SMS (via Fast2SMS) when an order's status
 * changes or Delhivery updates a shipment. Fails silently (logs only) —
 * notification failures must never break the order/shipment flow.
 */

function notificationsStatusCopy(string $status): array {
    $map = [
        'pending'    => ['label' => 'Order Placed',      'line' => 'Your order has been placed successfully.'],
        'processing' => ['label' => 'Order Processing',  'line' => 'Your order is being processed and will be shipped soon.'],
        'shipped'    => ['label' => 'Order Shipped',      'line' => 'Great news — your order has been shipped!'],
        'delivered'  => ['label' => 'Order Delivered',    'line' => 'Your order has been delivered. Thank you for shopping with us!'],
        'cancelled'  => ['label' => 'Order Cancelled',    'line' => 'Your order has been cancelled.'],
    ];
    return $map[$status] ?? ['label' => ucfirst($status), 'line' => "Your order status is now: {$status}."];
}

function sendSms(string $phone, string $message): bool {
    $apiKey = getenv('FAST2SMS_KEY') ?: '';
    if (empty($apiKey)) return false;

    $phone = preg_replace('/^\+91|^91/', '', preg_replace('/\D/', '', $phone));
    if (strlen($phone) !== 10) return false;

    $payload = http_build_query([
        'route'   => 'q',
        'message' => $message,
        'numbers' => $phone,
    ]);
    $ctx = stream_context_create(['http' => [
        'method'         => 'POST',
        'header'         => "authorization: {$apiKey}\r\nContent-Type: application/x-www-form-urlencoded\r\n",
        'content'        => $payload,
        'timeout'        => 8,
        'ignore_errors'  => true,
    ]]);

    try {
        $res    = @file_get_contents('https://www.fast2sms.com/dev/bulkV2', false, $ctx);
        $result = json_decode((string)$res, true);
        return ($result['return'] ?? false) === true;
    } catch (\Throwable $e) {
        error_log('sendSms failed: ' . $e->getMessage());
        return false;
    }
}

function sendOrderEmail(string $email, string $name, string $subject, string $htmlBody): bool {
    if (empty($email)) return false;

    $host    = parse_url(APP_URL, PHP_URL_HOST) ?: 'ttelectro.in';
    $from    = 'no-reply@' . $host;
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= 'From: ' . APP_NAME . " <{$from}>\r\n";
    $headers .= "Reply-To: {$from}\r\n";

    try {
        return @mail($email, $subject, $htmlBody, $headers);
    } catch (\Throwable $e) {
        error_log('sendOrderEmail failed: ' . $e->getMessage());
        return false;
    }
}

function buildOrderStatusEmailBody(array $order, string $name, string $line, ?string $extra = null): string {
    $orderNo   = clean($order['order_number'] ?? '');
    $trackUrl  = APP_URL . '/track-order?order=' . urlencode($orderNo);
    $extraHtml = $extra ? "<p style=\"margin:12px 0 0;color:#475569;font-size:14px\">{$extra}</p>" : '';

    return <<<HTML
    <div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;padding:24px;background:#f8fafc">
      <div style="background:#ffffff;border-radius:16px;padding:28px;border:1px solid #e2e8f0">
        <h2 style="margin:0 0 4px;color:#0f172a;font-size:20px">Hi {$name},</h2>
        <p style="margin:0 0 16px;color:#475569;font-size:14px">{$line}</p>
        <div style="background:#f1f5f9;border-radius:10px;padding:14px 16px;margin-bottom:16px">
          <p style="margin:0;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.5px">Order Number</p>
          <p style="margin:2px 0 0;color:#0f172a;font-size:16px;font-weight:700">{$orderNo}</p>
        </div>
        {$extraHtml}
        <a href="{$trackUrl}" style="display:inline-block;margin-top:20px;background:#2563eb;color:#fff;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:14px;font-weight:600">Track Your Order</a>
        <p style="margin:24px 0 0;color:#94a3b8;font-size:12px">— Team {APP_NAME}</p>
      </div>
    </div>
    HTML;
}

/**
 * Fire order-status notifications (email + SMS). Called after any order
 * status change (manual admin update or automatic Delhivery sync).
 *
 * @param array      $order Full order row (post-update), including shipping_address
 * @param array|null $user  User row (id,name,email,phone) — optional
 * @param string|null $extraLine Optional extra line (e.g. AWB number / ETA)
 */
function notifyOrderStatusChange(array $order, ?array $user = null, ?string $extraLine = null): void {
    try {
        $addr  = is_array($order['shipping_address'] ?? null) ? $order['shipping_address'] : [];
        $name  = $addr['name']  ?? ($user['name']  ?? 'Customer');
        $phone = $addr['phone'] ?? ($user['phone'] ?? null);
        $email = $user['email'] ?? null;

        $status  = $order['status'] ?? 'pending';
        $copy    = notificationsStatusCopy($status);
        $subject = APP_NAME . ' — ' . $copy['label'] . ' (' . ($order['order_number'] ?? '') . ')';

        if ($email) {
            $body = buildOrderStatusEmailBody($order, $name, $copy['line'], $extraLine);
            sendOrderEmail($email, $name, $subject, $body);
        }

        if ($phone) {
            $smsText = APP_NAME . ': ' . $copy['line'];
            if ($extraLine) $smsText .= ' ' . strip_tags($extraLine);
            $smsText .= ' Order #' . ($order['order_number'] ?? '') . '. Track: ' . APP_URL . '/track-order?order=' . urlencode($order['order_number'] ?? '');
            sendSms($phone, $smsText);
        }
    } catch (\Throwable $e) {
        error_log('notifyOrderStatusChange failed: ' . $e->getMessage());
    }
}

/**
 * Store a notification record in admin_notifications table.
 * Fails silently — never breaks the calling flow.
 */
function createAdminNotification(string $type, string $title, string $message, array $data = []): void {
    try {
        Database::getConnection()->prepare(
            'INSERT INTO admin_notifications (type, title, message, data, is_read, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())'
        )->execute([$type, $title, $message, json_encode($data)]);
    } catch (\Throwable $e) {
        error_log('createAdminNotification failed: ' . $e->getMessage());
    }
}

/**
 * Notify admin when a new order is placed (email + DB record).
 */
function notifyAdminNewOrder(array $order): void {
    try {
        $orderNo = $order['order_number'] ?? '?';
        $total   = '₹' . number_format((float)($order['total'] ?? 0), 0);
        $method  = strtoupper($order['payment_method'] ?? 'COD');
        $addr    = is_array($order['shipping_address'] ?? null) ? $order['shipping_address'] : [];
        $buyer   = $addr['name'] ?? ($order['user_name'] ?? 'Customer');

        $title   = "New Order: #{$orderNo}";
        $message = "{$buyer} placed order {$orderNo} for {$total} via {$method}.";

        createAdminNotification('new_order', $title, $message, [
            'order_id'       => $order['id']    ?? null,
            'order_number'   => $orderNo,
            'total'          => $order['total'] ?? 0,
            'payment_method' => $order['payment_method'] ?? '',
        ]);

        $adminUrl = APP_URL . '/admin/orders';
        $subject  = APP_NAME . ' — New Order #' . $orderNo;
        $html     = <<<HTML
        <div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;padding:24px;background:#f8fafc">
          <div style="background:#ffffff;border-radius:16px;padding:28px;border:1px solid #e2e8f0">
            <h2 style="margin:0 0 4px;color:#0f172a;font-size:20px">🛍️ New Order Received!</h2>
            <p style="margin:0 0 16px;color:#475569;font-size:14px">{$message}</p>
            <div style="background:#f1f5f9;border-radius:10px;padding:14px 16px;margin-bottom:16px">
              <p style="margin:0;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.5px">Order Number</p>
              <p style="margin:2px 0 0;color:#0f172a;font-size:18px;font-weight:700">{$orderNo}</p>
              <p style="margin:4px 0 0;color:#64748b;font-size:14px">{$total} &nbsp;·&nbsp; {$method}</p>
            </div>
            <a href="{$adminUrl}" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:14px;font-weight:600">View in Admin Panel</a>
          </div>
        </div>
        HTML;

        sendOrderEmail(ADMIN_EMAIL, 'Admin', $subject, $html);
    } catch (\Throwable $e) {
        error_log('notifyAdminNewOrder failed: ' . $e->getMessage());
    }
}

/**
 * Notify admin when auto-Delhivery shipment creation fails.
 */
function notifyAdminShipmentFailed(array $order, string $error): void {
    try {
        $orderNo = $order['order_number'] ?? '?';
        $title   = "Shipment Failed: #{$orderNo}";
        $message = "Auto-Delhivery shipment for order #{$orderNo} failed. Error: {$error}";

        createAdminNotification('shipment_failed', $title, $message, [
            'order_id'     => $order['id'] ?? null,
            'order_number' => $orderNo,
            'error'        => $error,
        ]);

        $adminUrl = APP_URL . '/admin/orders';
        $subject  = APP_NAME . ' — ⚠️ Shipment Failed #' . $orderNo;
        $html     = <<<HTML
        <div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;padding:24px;background:#f8fafc">
          <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #fca5a5">
            <h2 style="margin:0 0 8px;color:#dc2626;font-size:20px">⚠️ Shipment Creation Failed</h2>
            <p style="color:#475569;font-size:14px;margin:0 0 12px">Auto-Delhivery shipment for order <strong>{$orderNo}</strong> could not be created.</p>
            <div style="color:#dc2626;font-size:13px;background:#fef2f2;padding:12px 14px;border-radius:8px;margin-bottom:16px;border-left:3px solid #dc2626">{$error}</div>
            <p style="color:#64748b;font-size:13px;margin:0 0 16px">Please create the shipment manually from the admin panel.</p>
            <a href="{$adminUrl}" style="display:inline-block;background:#dc2626;color:#fff;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:14px;font-weight:600">Go to Admin Orders</a>
          </div>
        </div>
        HTML;

        sendOrderEmail(ADMIN_EMAIL, 'Admin', $subject, $html);
    } catch (\Throwable $e) {
        error_log('notifyAdminShipmentFailed failed: ' . $e->getMessage());
    }
}

/**
 * Notify admin when a customer submits a return request.
 */
function notifyAdminNewReturn(array $ret): void {
    try {
        $orderNo = $ret['order_number'] ?? '?';
        $buyer   = $ret['user_name']   ?? 'Customer';
        $reason  = $ret['reason']      ?? '?';

        $title   = "Return Request: Order #{$orderNo}";
        $message = "{$buyer} requested a return for order #{$orderNo}. Reason: {$reason}";

        createAdminNotification('new_return', $title, $message, [
            'return_id' => $ret['id']       ?? null,
            'order_id'  => $ret['order_id'] ?? null,
            'order_number' => $orderNo,
        ]);

        $adminUrl = APP_URL . '/admin/returns';
        $subject  = APP_NAME . ' — Return Request for #' . $orderNo;
        $html     = <<<HTML
        <div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;padding:24px;background:#f8fafc">
          <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #e2e8f0">
            <h2 style="margin:0 0 8px;color:#0f172a;font-size:20px">📦 Return Request Received</h2>
            <p style="color:#475569;font-size:14px;margin:0 0 16px">{$message}</p>
            <div style="background:#f1f5f9;border-radius:10px;padding:14px 16px;margin-bottom:16px">
              <p style="margin:0;color:#64748b;font-size:12px;text-transform:uppercase">Order</p>
              <p style="margin:2px 0 0;color:#0f172a;font-size:16px;font-weight:700">{$orderNo}</p>
              <p style="margin:4px 0 0;color:#64748b;font-size:13px">Reason: {$reason}</p>
            </div>
            <a href="{$adminUrl}" style="display:inline-block;background:#7c3aed;color:#fff;text-decoration:none;padding:10px 22px;border-radius:10px;font-size:14px;font-weight:600">Review Return Request</a>
          </div>
        </div>
        HTML;

        sendOrderEmail(ADMIN_EMAIL, 'Admin', $subject, $html);
    } catch (\Throwable $e) {
        error_log('notifyAdminNewReturn failed: ' . $e->getMessage());
    }
}

/**
 * Fire a Delhivery-specific shipment notification (AWB created or tracking
 * status changed), including the AWB number and expected delivery date.
 */
function notifyDelhiveryUpdate(array $order, ?array $user = null, bool $isNewShipment = false): void {
    try {
        $awb    = $order['awb_number'] ?? null;
        if (empty($awb)) return;

        $status = $order['delivery_status'] ?? null;
        $eta    = $order['expected_delivery_date'] ?? null;

        $lines = [];
        $lines[] = $isNewShipment
            ? "Your order has been handed over to Delhivery for delivery. AWB: <strong>{$awb}</strong>."
            : "Delivery status update from Delhivery — AWB: <strong>{$awb}</strong>.";
        if ($status) $lines[] = "Current status: <strong>" . clean($status) . '</strong>.';
        if ($eta)    $lines[] = 'Expected delivery: <strong>' . date('M j, Y', strtotime($eta)) . '</strong>.';

        notifyOrderStatusChange($order, $user, implode(' ', $lines));
    } catch (\Throwable $e) {
        error_log('notifyDelhiveryUpdate failed: ' . $e->getMessage());
    }
}
