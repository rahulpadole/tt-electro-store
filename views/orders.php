<?php
$pageTitle = 'My Orders';
$om     = new OrderModel();
$orders = $om->getForUser(getCurrentUserId());
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">My Orders</h1>
  <?php if(empty($orders)): ?>
  <div class="text-center py-20 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5">
    <div class="text-6xl mb-4">📦</div>
    <p class="text-slate-500 dark:text-slate-400 text-lg font-medium mb-2">No orders yet</p>
    <a href="/products" class="mt-2 inline-block px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">Start Shopping →</a>
  </div>
  <?php else: ?>
  <div class="space-y-4">
    <?php foreach($orders as $order):
      $sc = $statusColors[$order['status']] ?? 'gray';
    ?>
    <a href="/orders/<?= $order['id'] ?>"
       class="block rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5 hover:border-blue-500/30 dark:hover:border-blue-500/20 p-5 transition-all hover:-translate-y-0.5 shadow-sm">
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
          <p class="text-slate-900 dark:text-white font-semibold"><?= clean($order['order_number']) ?></p>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5"><?= date('M j, Y', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="flex items-center gap-4">
          <span class="px-3 py-1 rounded-full text-xs font-semibold bg-<?= $sc ?>-500/20 text-<?= $sc ?>-500 dark:text-<?= $sc ?>-400 border border-<?= $sc ?>-500/30 capitalize">
            <?= clean($order['status']) ?>
          </span>
          <span class="text-slate-900 dark:text-white font-bold">₹<?= number_format((float)$order['total'], 2) ?></span>
        </div>
      </div>
      <?php if(!empty($order['tracking_number'] ?? null)): ?>
      <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">Tracking: <span class="text-blue-600 dark:text-blue-400"><?= clean($order['tracking_number']) ?></span></p>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
