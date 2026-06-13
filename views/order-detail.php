<?php
$om    = new OrderModel();
$order = $om->findById($orderId);
if (!$order || ($order['user_id'] != getCurrentUserId() && !isAdmin())) {
    http_response_code(404);
    include __DIR__ . '/not-found.php';
    return;
}
$pageTitle    = 'Order ' . $order['order_number'];
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
$sc       = $statusColors[$order['status']] ?? 'gray';
$addr     = is_array($order['shipping_address']) ? $order['shipping_address'] : [];
$timeline = is_array($order['status_timeline'])  ? $order['status_timeline']  : [];
?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex items-center gap-3 mb-6">
    <a href="/orders" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors text-sm">← My Orders</a>
    <span class="text-slate-300 dark:text-slate-600">/</span>
    <h1 class="text-slate-900 dark:text-white font-semibold"><?= clean($order['order_number']) ?></h1>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
      <!-- Status -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-slate-900 dark:text-white">Order Status</h2>
          <span class="px-3 py-1 rounded-full text-xs font-semibold bg-<?= $sc ?>-500/20 text-<?= $sc ?>-600 dark:text-<?= $sc ?>-400 border border-<?= $sc ?>-500/30 capitalize"><?= clean($order['status']) ?></span>
        </div>
        <?php if (!empty($timeline)): ?>
        <div class="space-y-3">
          <?php foreach (array_reverse($timeline) as $ti): ?>
          <div class="flex items-start gap-3">
            <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
            <div>
              <p class="text-sm font-medium text-slate-800 dark:text-white"><?= clean($ti['label'] ?? ucfirst($ti['status'] ?? '')) ?></p>
              <p class="text-xs text-slate-500 dark:text-slate-500"><?= date('M j, Y g:i a', strtotime($ti['time'] ?? '')) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($order['tracking_number'] ?? null)): ?>
        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Tracking No: <span class="text-blue-600 dark:text-blue-400 font-medium"><?= clean($order['tracking_number']) ?></span></p>
        <?php endif; ?>
        <?php if (!empty($order['delivery_estimate'] ?? null)): ?>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Estimated Delivery: <span class="text-green-600 dark:text-green-400"><?= clean($order['delivery_estimate']) ?></span></p>
        <?php endif; ?>
      </div>

      <!-- Items -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Order Items</h2>
        <div class="space-y-4">
          <?php foreach (($order['items'] ?? []) as $item): ?>
          <div class="flex items-center gap-3">
            <?php if ($item['thumbnail']): ?>
            <img src="<?= clean($item['thumbnail']) ?>" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-slate-100 dark:bg-[hsl(222,47%,15%)]">
            <?php else: ?>
            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-[hsl(222,47%,15%)] flex items-center justify-center text-2xl flex-shrink-0">📦</div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-1"><?= clean($item['product_name']) ?></p>
              <p class="text-xs text-slate-500 dark:text-slate-500">Qty: <?= (int)$item['quantity'] ?></p>
            </div>
            <span class="text-sm font-semibold text-slate-900 dark:text-white">₹<?= number_format((float)$item['price'] * (int)$item['quantity'], 2) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="space-y-5">
      <!-- Price Summary -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm space-y-2">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-3">Price Breakdown</h2>
        <div class="flex justify-between text-sm">
          <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
          <span class="text-slate-700 dark:text-slate-300">₹<?= number_format((float)$order['subtotal'], 2) ?></span>
        </div>
        <?php if ((float)($order['discount'] ?? 0) > 0): ?>
        <div class="flex justify-between text-sm text-green-600 dark:text-green-400">
          <span>Discount</span>
          <span>−₹<?= number_format((float)$order['discount'], 2) ?></span>
        </div>
        <?php endif; ?>
        <div class="flex justify-between text-sm">
          <span class="text-slate-500 dark:text-slate-400">Shipping</span>
          <span class="<?= (float)$order['shipping'] === 0.0 ? 'text-green-600 dark:text-green-400' : 'text-slate-700 dark:text-slate-300' ?>">
            <?= (float)$order['shipping'] === 0.0 ? 'FREE' : '₹' . number_format((float)$order['shipping'], 2) ?>
          </span>
        </div>
        <div class="flex justify-between font-bold text-slate-900 dark:text-white border-t border-slate-100 dark:border-white/10 pt-2">
          <span>Total</span>
          <span>₹<?= number_format((float)$order['total'], 2) ?></span>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-500 pt-1">Payment: <span class="text-slate-700 dark:text-slate-300 capitalize"><?= clean($order['payment_method'] ?? '') ?></span></div>
        <?php if (!empty($order['coupon_code'] ?? null)): ?>
        <div class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
          <i class="fa-solid fa-tag text-[10px]"></i> Coupon: <?= clean($order['coupon_code']) ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Shipping Address -->
      <?php if (!empty($addr)): ?>
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-3">Shipping Address</h2>
        <div class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
          <p class="text-slate-800 dark:text-white font-medium"><?= clean($addr['name'] ?? '') ?></p>
          <p><?= clean($addr['address_line1'] ?? '') ?></p>
          <?php if (!empty($addr['address_line2'])): ?><p><?= clean($addr['address_line2']) ?></p><?php endif; ?>
          <p><?= clean($addr['city'] ?? '') ?>, <?= clean($addr['state'] ?? '') ?> – <?= clean($addr['pincode'] ?? '') ?></p>
          <p><?= clean($addr['country'] ?? 'India') ?></p>
          <?php if (!empty($addr['phone'])): ?><p class="mt-1">📞 <?= clean($addr['phone']) ?></p><?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
