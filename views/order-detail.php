<?php
$om    = new OrderModel();
$order = $om->findById($orderId);
if (!$order || ($order['user_id'] != getCurrentUserId() && !isAdmin())) {
    http_response_code(404);
    include __DIR__ . '/not-found.php';
    return;
}

$pageTitle    = 'Order ' . $order['order_number'];
$addr         = is_array($order['shipping_address']) ? $order['shipping_address'] : [];
$timeline     = is_array($order['status_timeline'])  ? $order['status_timeline']  : [];
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
$sc           = $statusColors[$order['status']] ?? 'gray';
$cancellable  = in_array($order['status'], ['pending','processing']);

// Returns
$rm           = new ReturnModel();
$existingReturn = $rm->getForOrder((int)$order['id']);
$deliveredAt  = null;
foreach (array_reverse($timeline) as $t) {
    if (($t['status'] ?? '') === 'delivered') { $deliveredAt = strtotime($t['time'] ?? ''); break; }
}
$canReturn = $order['status'] === 'delivered'
          && !$existingReturn
          && ($deliveredAt === null || (time() - $deliveredAt) <= 86400);

// 5-step progress stepper
$stepOrder = ['pending', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
$statusStep = [
    'pending'    => 0,
    'processing' => 1,
    'shipped'    => 2,
    'delivered'  => 4,
    'cancelled'  => -1,
];
$currentStep = $statusStep[$order['status']] ?? 0;
if ($order['status'] === 'shipped' && !empty($order['delivery_status'])) {
    $ds = strtolower($order['delivery_status'] ?? '');
    if (str_contains($ds, 'out for delivery') || str_contains($ds, 'ofd')) $currentStep = 3;
}

$steps = [
    ['icon' => 'fa-clipboard-check', 'label' => 'Order Placed',        'sub' => 'We received your order'],
    ['icon' => 'fa-credit-card',     'label' => 'Payment Confirmed',    'sub' => 'Payment verified'],
    ['icon' => 'fa-box',             'label' => 'Shipped',              'sub' => 'On its way to you'],
    ['icon' => 'fa-truck',           'label' => 'Out for Delivery',     'sub' => 'Arriving today'],
    ['icon' => 'fa-house-circle-check','label'=> 'Delivered',           'sub' => 'Enjoy your purchase!'],
];

$returnStatusColors = ['pending'=>'yellow','approved'=>'blue','rejected'=>'red','picked_up'=>'purple','refunded'=>'green'];
?>

<div class="max-w-5xl mx-auto px-4 py-8"
     x-data="orderDetailPage(<?= (int)$order['id'] ?>, <?= $cancellable ? 'true' : 'false' ?>, <?= $canReturn ? 'true' : 'false' ?>)">

  <!-- Cancel Modal -->
  <div x-show="showCancelModal" x-cloak
       class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
       @click.self="showCancelModal=false">
    <div class="w-full max-w-md bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-2xl">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/15 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i>
        </div>
        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Cancel Order?</h3>
      </div>
      <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">This action cannot be undone.</p>
      <div class="mb-4">
        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5 block">Reason (optional)</label>
        <textarea x-model="cancelReason" rows="2" placeholder="Why are you cancelling?"
                  class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-red-400 resize-none"></textarea>
      </div>
      <div class="flex gap-3">
        <button @click="showCancelModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Keep Order</button>
        <button @click="cancelOrder()" :disabled="actionLoading"
                class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold disabled:opacity-50 flex items-center justify-center gap-2">
          <span x-show="!actionLoading">Cancel Order</span>
          <span x-show="actionLoading" class="flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>Cancelling...</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Return Modal -->
  <div x-show="showReturnModal" x-cloak
       class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
       @click.self="showReturnModal=false">
    <div class="w-full max-w-md bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-2xl">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-500/15 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-rotate-left text-purple-500 text-lg"></i>
        </div>
        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Request Return</h3>
      </div>
      <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Returns are accepted within <strong class="text-slate-700 dark:text-white">24 hours</strong> of delivery. Please describe the issue.</p>
      <div class="mb-3">
        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5 block">Reason <span class="text-red-400">*</span></label>
        <select x-model="returnReason" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-purple-400">
          <option value="">Select a reason…</option>
          <option value="Defective product">Defective product</option>
          <option value="Wrong item received">Wrong item received</option>
          <option value="Item not as described">Item not as described</option>
          <option value="Damaged in transit">Damaged in transit</option>
          <option value="Changed my mind">Changed my mind</option>
          <option value="Missing parts/accessories">Missing parts/accessories</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5 block">Additional Details</label>
        <textarea x-model="returnDesc" rows="2" placeholder="Optional — describe the issue in more detail"
                  class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-400 resize-none"></textarea>
      </div>
      <div class="flex gap-3">
        <button @click="showReturnModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Cancel</button>
        <button @click="submitReturn()" :disabled="!returnReason || actionLoading"
                class="flex-1 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold disabled:opacity-50 flex items-center justify-center gap-2">
          <span x-show="!actionLoading">Submit Return</span>
          <span x-show="actionLoading" class="flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>Submitting…</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Header -->
  <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
    <div class="flex items-center gap-2 text-sm min-w-0">
      <a href="/orders" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors shrink-0">← My Orders</a>
      <span class="text-slate-300 dark:text-slate-600">/</span>
      <span class="text-slate-900 dark:text-white font-semibold font-mono truncate"><?= clean($order['order_number']) ?></span>
      <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-<?= $sc ?>-500/15 text-<?= $sc ?>-600 dark:text-<?= $sc ?>-400 border border-<?= $sc ?>-500/25 capitalize shrink-0"><?= clean($order['status']) ?></span>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <?php if ($canReturn): ?>
      <button @click="showReturnModal=true"
              class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 text-purple-600 dark:text-purple-400 text-xs font-semibold hover:bg-purple-100 dark:hover:bg-purple-500/15 transition-all">
        <i class="fa-solid fa-rotate-left text-xs"></i> Return
      </button>
      <?php endif; ?>
      <?php if ($cancellable): ?>
      <button @click="showCancelModal=true"
              class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-500/15 transition-all">
        <i class="fa-solid fa-xmark text-xs"></i> Cancel
      </button>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($order['status'] !== 'cancelled'): ?>
  <!-- 5-step Progress Stepper -->
  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-6 mb-5 shadow-sm overflow-x-auto">
    <div class="flex items-start min-w-[500px]">
      <?php foreach ($steps as $i => $step):
        $done    = $i <= $currentStep;
        $current = $i === $currentStep;
        $last    = $i === count($steps) - 1;
      ?>
      <div class="flex-1 flex flex-col items-center relative">
        <?php if (!$last): ?>
        <div class="absolute top-5 left-1/2 w-full h-0.5 <?= $done && $i < $currentStep ? 'bg-green-500' : 'bg-slate-200 dark:bg-white/10' ?> z-0"></div>
        <?php endif; ?>
        <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center border-2 mb-2 flex-shrink-0
          <?= $done
            ? 'bg-green-500 border-green-500 text-white'
            : 'bg-white dark:bg-[hsl(222,47%,14%)] border-slate-200 dark:border-white/15 text-slate-400 dark:text-slate-600' ?>
          <?= $current && !$done ? 'ring-4 ring-blue-500/20' : '' ?>">
          <?php if ($done && !$current): ?>
            <i class="fa-solid fa-check text-xs"></i>
          <?php else: ?>
            <i class="fa-solid <?= $step['icon'] ?> text-xs"></i>
          <?php endif; ?>
        </div>
        <p class="text-xs font-semibold text-center <?= $done ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600' ?>"><?= $step['label'] ?></p>
        <p class="text-[10px] text-slate-400 dark:text-slate-600 text-center mt-0.5 hidden sm:block"><?= $step['sub'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">

      <!-- Delhivery Tracking (if AWB exists) -->
      <?php if (!empty($order['awb_number'])): ?>
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-truck-fast text-purple-500"></i>
            <h2 class="font-semibold text-slate-900 dark:text-white">Shipment Tracking</h2>
          </div>
          <a href="/track-order?awb=<?= urlencode($order['awb_number']) ?>" target="_blank"
             class="text-xs text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 transition-colors">
            Full Tracker <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
          </a>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div class="bg-slate-50 dark:bg-[hsl(222,47%,13%)] rounded-xl p-3">
            <p class="text-xs text-slate-500 dark:text-slate-500 mb-1">AWB Number</p>
            <p class="font-mono font-semibold text-slate-800 dark:text-white text-sm"><?= clean($order['awb_number']) ?></p>
          </div>
          <div class="bg-slate-50 dark:bg-[hsl(222,47%,13%)] rounded-xl p-3">
            <p class="text-xs text-slate-500 dark:text-slate-500 mb-1">Courier Partner</p>
            <p class="font-semibold text-slate-800 dark:text-white text-sm"><?= clean($order['delivery_partner'] ?? 'Delhivery') ?></p>
          </div>
          <?php if (!empty($order['delivery_status'])): ?>
          <div class="bg-slate-50 dark:bg-[hsl(222,47%,13%)] rounded-xl p-3">
            <p class="text-xs text-slate-500 dark:text-slate-500 mb-1">Current Status</p>
            <p class="font-semibold text-purple-600 dark:text-purple-400 text-sm"><?= clean($order['delivery_status']) ?></p>
          </div>
          <?php endif; ?>
          <?php if (!empty($order['expected_delivery_date'])): ?>
          <div class="bg-green-50 dark:bg-green-500/8 rounded-xl p-3 border border-green-200 dark:border-green-500/20">
            <p class="text-xs text-green-600 dark:text-green-500 mb-1">Expected Delivery</p>
            <p class="font-semibold text-green-700 dark:text-green-400 text-sm"><?= date('D, M j', strtotime($order['expected_delivery_date'])) ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Order Timeline -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Order Timeline</h2>
        <?php if (!empty($timeline)): ?>
        <div class="relative">
          <div class="absolute left-4 top-0 bottom-0 w-px bg-slate-200 dark:bg-white/8"></div>
          <div class="space-y-4">
            <?php foreach (array_reverse($timeline) as $idx => $ti):
              $tiStatus = $ti['status'] ?? '';
              $tiDotColor = match($tiStatus) {
                'delivered'  => 'bg-green-500',
                'shipped'    => 'bg-purple-500',
                'processing' => 'bg-blue-500',
                'cancelled'  => 'bg-red-500',
                default      => 'bg-slate-400',
              };
            ?>
            <div class="flex items-start gap-4 relative pl-9">
              <div class="absolute left-0 top-1 w-8 h-8 rounded-full flex items-center justify-center <?= $idx === 0 ? 'bg-blue-500/15 ring-2 ring-blue-500/30' : 'bg-slate-100 dark:bg-white/8' ?>">
                <div class="w-2.5 h-2.5 rounded-full <?= $tiDotColor ?>"></div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 dark:text-white"><?= clean($ti['label'] ?? ucfirst($tiStatus)) ?></p>
                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5"><?= date('D, M j Y · g:i a', strtotime($ti['time'] ?? '')) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <p class="text-sm text-slate-500 dark:text-slate-500">No timeline events yet.</p>
        <?php endif; ?>
      </div>

      <!-- Order Items -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Order Items</h2>
        <div class="space-y-4">
          <?php foreach (($order['items'] ?? []) as $item): ?>
          <div class="flex items-center gap-3">
            <?php if ($item['thumbnail']): ?>
            <img src="<?= clean($item['thumbnail']) ?>" class="w-14 h-14 rounded-xl object-cover flex-shrink-0 bg-slate-100 dark:bg-[hsl(222,47%,15%)] border border-slate-200 dark:border-white/8">
            <?php else: ?>
            <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,15%)] flex items-center justify-center text-2xl flex-shrink-0 border border-slate-200 dark:border-white/8">📦</div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-2"><?= clean($item['product_name']) ?></p>
              <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">Qty: <?= (int)$item['quantity'] ?> × ₹<?= number_format((float)$item['price'], 0) ?></p>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white shrink-0">₹<?= number_format((float)$item['price'] * (int)$item['quantity'], 0) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Existing Return Request -->
      <?php if ($existingReturn): ?>
      <?php $rsc = $returnStatusColors[$existingReturn['status']] ?? 'gray'; ?>
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <h2 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-rotate-left text-purple-500 text-sm"></i> Return Request
          </h2>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-<?= $rsc ?>-500/15 text-<?= $rsc ?>-600 dark:text-<?= $rsc ?>-400 border border-<?= $rsc ?>-500/25 capitalize"><?= clean($existingReturn['status']) ?></span>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400"><span class="font-medium text-slate-800 dark:text-slate-200">Reason:</span> <?= clean($existingReturn['reason']) ?></p>
        <?php if ($existingReturn['description']): ?>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1"><?= clean($existingReturn['description']) ?></p>
        <?php endif; ?>
        <p class="text-xs text-slate-400 dark:text-slate-600 mt-2">Submitted <?= date('M j, Y', strtotime($existingReturn['created_at'])) ?></p>
        <?php if ($existingReturn['admin_notes']): ?>
        <div class="mt-3 p-3 bg-slate-50 dark:bg-white/4 rounded-xl border border-slate-200 dark:border-white/8">
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Admin Notes</p>
          <p class="text-sm text-slate-700 dark:text-slate-300"><?= clean($existingReturn['admin_notes']) ?></p>
        </div>
        <?php endif; ?>
        <?php if ($existingReturn['refund_amount']): ?>
        <div class="mt-3 flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-semibold">
          <i class="fa-solid fa-circle-check"></i> Refund: ₹<?= number_format((float)$existingReturn['refund_amount'], 2) ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-5">

      <!-- Price Summary -->
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Order Summary</h2>
        <div class="space-y-2.5 text-sm">
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
            <span class="text-slate-700 dark:text-slate-300">₹<?= number_format((float)$order['subtotal'], 2) ?></span>
          </div>
          <?php if ((float)($order['discount'] ?? 0) > 0): ?>
          <div class="flex justify-between text-green-600 dark:text-green-400">
            <span class="flex items-center gap-1"><i class="fa-solid fa-tag text-[10px]"></i> Discount<?= $order['coupon_code'] ? ' (' . clean($order['coupon_code']) . ')' : '' ?></span>
            <span>−₹<?= number_format((float)$order['discount'], 2) ?></span>
          </div>
          <?php endif; ?>
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Shipping</span>
            <span class="<?= (float)($order['shipping'] ?? 0) === 0.0 ? 'text-green-600 dark:text-green-400' : 'text-slate-700 dark:text-slate-300' ?>">
              <?= (float)($order['shipping'] ?? 0) === 0.0 ? 'FREE' : '₹' . number_format((float)$order['shipping'], 2) ?>
            </span>
          </div>
          <?php if ((float)($order['tax'] ?? 0) > 0): ?>
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">GST / Tax</span>
            <span class="text-slate-700 dark:text-slate-300">₹<?= number_format((float)$order['tax'], 2) ?></span>
          </div>
          <?php endif; ?>
          <div class="flex justify-between font-bold text-slate-900 dark:text-white border-t border-slate-100 dark:border-white/10 pt-2.5 text-base">
            <span>Total</span>
            <span>₹<?= number_format((float)$order['total'], 2) ?></span>
          </div>
        </div>

        <!-- Payment Info -->
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/8 space-y-1.5">
          <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500 dark:text-slate-400">Payment</span>
            <span class="text-slate-700 dark:text-slate-300 font-medium capitalize"><?= clean($order['payment_method'] ?? '') ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500 dark:text-slate-400">Status</span>
            <?php $ps = $order['payment_status'] ?? 'pending'; ?>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize <?= $ps === 'paid' ? 'bg-green-100 dark:bg-green-500/15 text-green-700 dark:text-green-400' : ($ps === 'failed' ? 'bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400' : 'bg-yellow-100 dark:bg-yellow-500/15 text-yellow-700 dark:text-yellow-400') ?>"><?= clean($ps) ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500 dark:text-slate-400">Ordered</span>
            <span class="text-slate-700 dark:text-slate-300"><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
          </div>
        </div>
      </div>

      <!-- Shipping Address -->
      <?php if (!empty($addr)): ?>
      <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900 dark:text-white mb-3">Delivery Address</h2>
        <div class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed space-y-0.5">
          <p class="text-slate-800 dark:text-white font-semibold"><?= clean($addr['name'] ?? '') ?></p>
          <p><?= clean($addr['address_line1'] ?? '') ?></p>
          <?php if (!empty($addr['address_line2'])): ?><p><?= clean($addr['address_line2']) ?></p><?php endif; ?>
          <p><?= clean($addr['city'] ?? '') ?>, <?= clean($addr['state'] ?? '') ?> – <?= clean($addr['pincode'] ?? '') ?></p>
          <p><?= clean($addr['country'] ?? 'India') ?></p>
          <?php if (!empty($addr['phone'])): ?>
          <p class="mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-phone text-xs text-slate-400"></i> <?= clean($addr['phone']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Track Order link -->
      <?php if ($order['status'] !== 'cancelled' && !empty($order['awb_number'])): ?>
      <a href="/track-order?awb=<?= urlencode($order['awb_number']) ?>"
         class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 text-sm font-semibold hover:bg-blue-100 dark:hover:bg-blue-500/15 transition-all">
        <i class="fa-solid fa-satellite-dish text-xs"></i> Live Tracking
      </a>
      <?php elseif ($order['status'] !== 'cancelled'): ?>
      <a href="/track-order?order=<?= urlencode($order['order_number']) ?>"
         class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-400 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-white/8 transition-all">
        <i class="fa-solid fa-magnifying-glass text-xs"></i> Track Order
      </a>
      <?php endif; ?>

      <!-- Cancellation Details -->
      <?php if (!empty($order['cancellation_reason'] ?? null)): ?>
      <div class="rounded-2xl bg-red-50 dark:bg-red-500/8 border border-red-200 dark:border-red-500/20 p-5">
        <h2 class="font-semibold text-red-700 dark:text-red-400 mb-2 flex items-center gap-2 text-sm">
          <i class="fa-solid fa-circle-xmark"></i> Cancellation Details
        </h2>
        <?php if(!empty($order['cancelled_at'])): ?>
        <p class="text-xs text-red-600 dark:text-red-400 mb-1">Cancelled on: <?= date('M j, Y g:i a', strtotime($order['cancelled_at'])) ?></p>
        <?php endif; ?>
        <p class="text-sm text-red-600 dark:text-red-400"><?= clean($order['cancellation_reason']) ?></p>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
function orderDetailPage(orderId, cancellable, canReturn) {
  return {
    orderId,
    cancellable,
    canReturn,
    showCancelModal:  false,
    showReturnModal:  false,
    actionLoading:    false,
    cancelReason:     '',
    returnReason:     '',
    returnDesc:       '',

    async cancelOrder() {
      if (!this.cancellable) return;
      this.actionLoading = true;
      try {
        await apiFetch(`/api/orders/${this.orderId}/cancel`, {
          method: 'POST',
          body: JSON.stringify({ reason: this.cancelReason })
        });
        showToast('Order cancelled successfully', 'success');
        setTimeout(() => location.reload(), 1000);
      } catch(e) {
        showToast(e.message, 'error');
        this.actionLoading = false;
        this.showCancelModal = false;
      }
    },

    async submitReturn() {
      if (!this.returnReason || !this.canReturn) return;
      this.actionLoading = true;
      try {
        await apiFetch('/api/returns', {
          method: 'POST',
          body: JSON.stringify({
            order_id:    this.orderId,
            reason:      this.returnReason,
            description: this.returnDesc,
          })
        });
        showToast('Return request submitted!', 'success');
        setTimeout(() => location.reload(), 1200);
      } catch(e) {
        showToast(e.message, 'error');
        this.actionLoading = false;
        this.showReturnModal = false;
      }
    },
  };
}
</script>
