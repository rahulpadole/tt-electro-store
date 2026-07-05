<?php
$pageTitle = 'Orders';
$om = new OrderModel();
$page = max(1,(int)($_GET['page']??1));
$limit = 20; $offset = ($page-1)*$limit;

$filters = [
  'status'         => $_GET['status'] ?? '',
  'payment_method' => $_GET['payment_method'] ?? '',
  'search'         => trim($_GET['search'] ?? ''),
  'date_from'      => $_GET['date_from'] ?? '',
  'date_to'        => $_GET['date_to'] ?? '',
];
$hasFilters = array_filter($filters);

if ($hasFilters) {
  $orders = $om->search($filters, $limit, $offset);
  $total  = $om->searchCount($filters);
} else {
  $orders = $om->all($limit, $offset);
  $total  = $om->count();
}
$totalPages = (int)ceil($total/$limit);
$statusColors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];

$qsBase = $_GET;
unset($qsBase['page']);
$qs = function(array $extra = []) use ($qsBase): string {
    $params = array_merge($qsBase, $extra);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return htmlspecialchars('?' . http_build_query($params));
};
?>
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
  <div><h2 class="text-white font-semibold">All Orders <span class="text-gray-500 font-normal text-sm">(<?= number_format($total) ?>)</span></h2></div>
  <div class="flex items-center gap-2">
    <a href="/api/admin/orders/export<?= $qs() ?>" class="px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-medium flex items-center gap-1.5 transition-all">
      <i class="fa-solid fa-file-csv"></i> Export CSV
    </a>
  </div>
</div>

<!-- Filters -->
<form method="get" class="bg-[hsl(222,47%,10%)] border border-white/5 rounded-2xl p-4 mb-5 grid grid-cols-1 md:grid-cols-6 gap-3">
  <div class="md:col-span-2">
    <input type="text" name="search" value="<?= clean($filters['search']) ?>" placeholder="Search order #, name, email, phone, AWB..."
           class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500/50">
  </div>
  <div>
    <select name="status" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500/50">
      <option value="">All Statuses</option>
      <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $st): ?>
      <option value="<?= $st ?>" <?= $filters['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <select name="payment_method" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500/50">
      <option value="">All Payments</option>
      <option value="cod" <?= $filters['payment_method']==='cod'?'selected':'' ?>>Cash on Delivery</option>
      <option value="razorpay" <?= $filters['payment_method']==='razorpay'?'selected':'' ?>>Razorpay (Online)</option>
    </select>
  </div>
  <div>
    <input type="date" name="date_from" value="<?= clean($filters['date_from']) ?>"
           class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500/50">
  </div>
  <div class="flex gap-2">
    <input type="date" name="date_to" value="<?= clean($filters['date_to']) ?>"
           class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500/50">
  </div>
  <div class="md:col-span-6 flex items-center gap-2">
    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all">Apply Filters</button>
    <?php if ($hasFilters): ?>
    <a href="/admin/orders" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-medium transition-all">Clear</a>
    <?php endif; ?>
  </div>
</form>

<!-- Bulk action bar -->
<div id="bulk-bar" class="hidden items-center justify-between bg-blue-500/10 border border-blue-500/20 rounded-xl px-4 py-3 mb-4">
  <p class="text-sm text-blue-300"><span id="bulk-count">0</span> order(s) selected</p>
  <div class="flex items-center gap-2">
    <select id="bulk-status" class="px-2 py-1.5 rounded-lg bg-white/5 border border-white/10 text-white text-xs focus:outline-none">
      <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $st): ?>
      <option value="<?= $st ?>"><?= ucfirst($st) ?></option>
      <?php endforeach; ?>
    </select>
    <button onclick="applyBulkStatus()" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all">Update Status</button>
  </div>
</div>

<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead><tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
        <th class="px-4 py-3 text-left w-8"><input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="rounded bg-white/10 border-white/20"></th>
        <th class="px-4 py-3 text-left">Order</th>
        <th class="px-4 py-3 text-left">Customer</th>
        <th class="px-4 py-3 text-left">Date</th>
        <th class="px-4 py-3 text-right">Total</th>
        <th class="px-4 py-3 text-center">Payment</th>
        <th class="px-4 py-3 text-center">Status</th>
        <th class="px-4 py-3 text-center">Delhivery</th>
        <th class="px-4 py-3 text-center">Actions</th>
      </tr></thead>
      <tbody>
        <?php if (empty($orders)): ?>
        <tr><td colspan="9" class="px-4 py-10 text-center text-gray-500 text-sm">No orders match your filters.</td></tr>
        <?php endif; ?>
        <?php foreach($orders as $o):
          $sc = $statusColors[$o['status']] ?? 'gray';
        ?>
        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="order-row-<?= $o['id'] ?>">
          <td class="px-4 py-3"><input type="checkbox" class="order-checkbox rounded bg-white/10 border-white/20" value="<?= $o['id'] ?>" onchange="updateBulkBar()"></td>
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
          <td class="px-4 py-3 text-center" id="delhivery-cell-<?= $o['id'] ?>">
            <?php if(!empty($o['awb_number'])): ?>
              <p class="text-gray-200 font-mono text-xs">AWB: <?= clean($o['awb_number']) ?></p>
              <p class="text-gray-500 text-[11px] mb-1"><?= clean($o['delivery_status'] ?? '—') ?></p>
              <button onclick="refreshDelhivery(<?= $o['id'] ?>)" class="text-blue-400 hover:text-blue-300 text-[11px] px-2 py-0.5 rounded border border-blue-500/20 hover:bg-blue-500/10 transition-all">Refresh</button>
            <?php else: ?>
              <button onclick="createDelhiveryShipment(<?= $o['id'] ?>)" class="text-purple-400 hover:text-purple-300 text-xs px-2 py-1 rounded border border-purple-500/20 hover:bg-purple-500/10 transition-all">Ship via Delhivery</button>
            <?php endif; ?>
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
      <?php if($page>1): ?><a href="<?= $qs(['page'=>$page-1]) ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">‹ Prev</a><?php endif; ?>
      <?php if($page<$totalPages): ?><a href="<?= $qs(['page'=>$page+1]) ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">Next ›</a><?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<script>
async function updateOrderStatus(id, status) {
  try { await apiFetch(`/api/orders/${id}`, { method:'PATCH', body:JSON.stringify({status}) }); showToast('Status updated!','success'); } catch(e) { showToast(e.message,'error'); }
}

function renderDelhiveryCell(order) {
  const cell = document.getElementById(`delhivery-cell-${order.id}`);
  if (!cell) return;
  if (order.awb_number) {
    cell.innerHTML = `
      <p class="text-gray-200 font-mono text-xs">AWB: ${order.awb_number}</p>
      <p class="text-gray-500 text-[11px] mb-1">${order.delivery_status || '—'}</p>
      <button onclick="refreshDelhivery(${order.id})" class="text-blue-400 hover:text-blue-300 text-[11px] px-2 py-0.5 rounded border border-blue-500/20 hover:bg-blue-500/10 transition-all">Refresh</button>`;
  } else {
    cell.innerHTML = `<button onclick="createDelhiveryShipment(${order.id})" class="text-purple-400 hover:text-purple-300 text-xs px-2 py-1 rounded border border-purple-500/20 hover:bg-purple-500/10 transition-all">Ship via Delhivery</button>`;
  }
}

async function createDelhiveryShipment(id) {
  try {
    const d = await apiFetch(`/api/admin/orders/${id}/delhivery`, { method:'POST', body: JSON.stringify({ action:'create' }) });
    renderDelhiveryCell(d.data);
    showToast('Delhivery shipment created! AWB: ' + d.data.awb_number, 'success');
  } catch(e) { showToast(e.message, 'error'); }
}

async function refreshDelhivery(id) {
  try {
    const d = await apiFetch(`/api/admin/orders/${id}/delhivery`, { method:'POST', body: JSON.stringify({ action:'refresh' }) });
    renderDelhiveryCell(d.data);
    showToast('Tracking status refreshed', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}

function toggleSelectAll(cb) {
  document.querySelectorAll('.order-checkbox').forEach(c => c.checked = cb.checked);
  updateBulkBar();
}

function updateBulkBar() {
  const checked = document.querySelectorAll('.order-checkbox:checked');
  const bar = document.getElementById('bulk-bar');
  const count = document.getElementById('bulk-count');
  count.textContent = checked.length;
  bar.classList.toggle('hidden', checked.length === 0);
  bar.classList.toggle('flex', checked.length > 0);
}

async function applyBulkStatus() {
  const ids = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(c => parseInt(c.value));
  if (!ids.length) return;
  const status = document.getElementById('bulk-status').value;
  try {
    const r = await apiFetch('/api/admin/orders/bulk', { method:'POST', body: JSON.stringify({ ids, status }) });
    showToast(r.message, 'success');
    setTimeout(() => location.reload(), 700);
  } catch(e) { showToast(e.message, 'error'); }
}
</script>
