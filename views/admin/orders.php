<?php
$pageTitle = 'Orders';
$om = new OrderModel();
$page = max(1,(int)($_GET['page']??1));
$limit = 20; $offset = ($page-1)*$limit;
$orders = $om->all($limit,$offset);
$total = $om->count();
$totalPages = (int)ceil($total/$limit);
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
?>
<div class="flex items-center justify-between mb-5">
  <div><h2 class="text-white font-semibold">All Orders <span class="text-gray-500 font-normal text-sm">(<?= number_format($total) ?>)</span></h2></div>
</div>
<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead><tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
        <th class="px-4 py-3 text-left">Order</th>
        <th class="px-4 py-3 text-left">Customer</th>
        <th class="px-4 py-3 text-left">Date</th>
        <th class="px-4 py-3 text-right">Total</th>
        <th class="px-4 py-3 text-center">Payment</th>
        <th class="px-4 py-3 text-center">Status</th>
        <th class="px-4 py-3 text-center">Actions</th>
      </tr></thead>
      <tbody>
        <?php foreach($orders as $o):
          $sc = $statusColors[$o['status']] ?? 'gray';
        ?>
        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="order-row-<?= $o['id'] ?>">
          <td class="px-4 py-3"><p class="text-white font-mono text-xs"><?= clean($o['order_number']) ?></p></td>
          <td class="px-4 py-3"><p class="text-gray-200"><?= clean($o['user_name']??'—') ?></p><p class="text-gray-500 text-xs"><?= clean($o['user_email']??'') ?></p></td>
          <td class="px-4 py-3 text-gray-400 text-xs"><?= date('M j, Y',strtotime($o['created_at'])) ?></td>
          <td class="px-4 py-3 text-right font-semibold text-white">₹<?= number_format((float)$o['total'],0) ?></td>
          <td class="px-4 py-3 text-center"><span class="text-xs text-gray-400 uppercase"><?= clean($o['payment_method']??'—') ?></span></td>
          <td class="px-4 py-3 text-center">
            <select onchange="updateOrderStatus(<?= $o['id'] ?>,this.value)" class="px-2 py-1 rounded-lg bg-<?= $sc ?>-500/20 text-<?= $sc ?>-400 border border-<?= $sc ?>-500/30 text-xs focus:outline-none cursor-pointer">
              <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $st): ?>
              <option value="<?= $st ?>" <?= $o['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td class="px-4 py-3 text-center">
            <a href="/orders/<?= $o['id'] ?>" target="_blank" class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded border border-blue-500/20 hover:bg-blue-500/10 transition-all">View</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if($totalPages>1): ?>
  <div class="flex items-center justify-between px-4 py-3 border-t border-white/10">
    <p class="text-xs text-gray-500">Page <?= $page ?> of <?= $totalPages ?></p>
    <div class="flex gap-2">
      <?php if($page>1): ?><a href="?page=<?= $page-1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">‹ Prev</a><?php endif; ?>
      <?php if($page<$totalPages): ?><a href="?page=<?= $page+1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">Next ›</a><?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<script>
async function updateOrderStatus(id, status) {
  try { await apiFetch(`/api/orders/${id}`, { method:'PATCH', body:JSON.stringify({status}) }); showToast('Status updated!','success'); } catch(e) { showToast(e.message,'error'); }
}
</script>
