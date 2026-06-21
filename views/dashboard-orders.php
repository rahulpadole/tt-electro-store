<?php
$pageTitle = 'My Orders';
$orders = (new OrderModel())->getForUser(getCurrentUserId());
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-white mb-6">Order History</h1>
  <?php if(empty($orders)): ?>
  <div class="text-center py-16 rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5">
    <div class="text-5xl mb-4">📦</div>
    <p class="text-gray-400">No orders yet. <a href="/products" class="text-blue-400">Start shopping!</a></p>
  </div>
  <?php else: ?>
  <div class="space-y-3">
    <?php foreach($orders as $o):$sc=$statusColors[$o['status']]??'gray'; ?>
    <a href="/orders/<?= $o['id'] ?>" class="flex items-center justify-between p-4 rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 hover:border-blue-500/20 transition-all">
      <div><p class="text-white font-semibold"><?= clean($o['order_number']) ?></p><p class="text-gray-500 text-sm"><?= date('M j, Y',strtotime($o['created_at'])) ?></p></div>
      <div class="flex items-center gap-3"><span class="px-2 py-0.5 rounded-full text-xs bg-<?= $sc ?>-500/20 text-<?= $sc ?>-400 capitalize"><?= clean($o['status']) ?></span><span class="text-white font-bold">₹<?= number_format((float)$o['total'],2) ?></span><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
