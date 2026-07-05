<?php
$pageTitle = 'Returns';
$rm        = new ReturnModel();
$page      = max(1, (int)($_GET['page'] ?? 1));
$limit     = 20;
$offset    = ($page - 1) * $limit;
$returns   = $rm->all($limit, $offset);
$total     = $rm->count();
$totalPages= (int)ceil($total / $limit);
$pending   = $rm->countByStatus('pending');

$statusColors = [
    'pending'   => 'yellow',
    'approved'  => 'blue',
    'rejected'  => 'red',
    'picked_up' => 'purple',
    'refunded'  => 'green',
];
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="text-white font-semibold">Return Requests <span class="text-gray-500 font-normal text-sm">(<?= number_format($total) ?>)</span></h2>
    <?php if ($pending > 0): ?>
    <p class="text-yellow-400 text-xs mt-0.5"><i class="fa-solid fa-circle-dot animate-pulse mr-1"></i><?= $pending ?> pending review</p>
    <?php endif; ?>
  </div>
</div>

<?php if (empty($returns)): ?>
<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 p-12 text-center">
  <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mx-auto mb-4">
    <i class="fa-solid fa-rotate-left text-gray-500 text-2xl"></i>
  </div>
  <p class="text-gray-400 font-medium mb-1">No return requests yet</p>
  <p class="text-gray-600 text-sm">Return requests from customers will appear here.</p>
</div>
<?php else: ?>
<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
          <th class="px-4 py-3 text-left">Return #</th>
          <th class="px-4 py-3 text-left">Order</th>
          <th class="px-4 py-3 text-left">Customer</th>
          <th class="px-4 py-3 text-left">Reason</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-right">Order Total</th>
          <th class="px-4 py-3 text-center">Date</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($returns as $r):
          $sc = $statusColors[$r['status']] ?? 'gray';
        ?>
        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="return-row-<?= $r['id'] ?>">
          <td class="px-4 py-3">
            <p class="text-white font-mono text-xs">#<?= $r['id'] ?></p>
          </td>
          <td class="px-4 py-3">
            <a href="/orders/<?= $r['order_id'] ?>" target="_blank" class="text-blue-400 hover:text-blue-300 font-mono text-xs transition-colors">
              <?= clean($r['order_number']) ?>
            </a>
          </td>
          <td class="px-4 py-3">
            <p class="text-gray-200"><?= clean($r['user_name'] ?? '—') ?></p>
            <p class="text-gray-500 text-xs"><?= clean($r['user_email'] ?? '') ?></p>
          </td>
          <td class="px-4 py-3">
            <p class="text-gray-300 text-xs max-w-[140px]"><?= clean($r['reason']) ?></p>
            <?php if ($r['description']): ?>
            <p class="text-gray-500 text-[11px] mt-0.5 line-clamp-1"><?= clean($r['description']) ?></p>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-<?= $sc ?>-500/20 text-<?= $sc ?>-400 border border-<?= $sc ?>-500/30 capitalize" id="return-status-<?= $r['id'] ?>">
              <?= clean($r['status']) ?>
            </span>
          </td>
          <td class="px-4 py-3 text-right text-gray-300 font-semibold">
            ₹<?= number_format((float)($r['order_total'] ?? 0), 0) ?>
          </td>
          <td class="px-4 py-3 text-center text-gray-500 text-xs">
            <?= date('M j, Y', strtotime($r['created_at'])) ?>
          </td>
          <td class="px-4 py-3 text-center">
            <button onclick="openReturnModal(<?= $r['id'] ?>, '<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>')"
                    class="text-purple-400 hover:text-purple-300 text-xs px-2 py-1 rounded border border-purple-500/20 hover:bg-purple-500/10 transition-all">
              Manage
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
  <div class="flex items-center justify-between px-4 py-3 border-t border-white/10">
    <p class="text-xs text-gray-500">Page <?= $page ?> of <?= $totalPages ?></p>
    <div class="flex gap-2">
      <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">‹ Prev</a><?php endif; ?>
      <?php if ($page < $totalPages): ?><a href="?page=<?= $page + 1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">Next ›</a><?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Return Management Modal -->
<div id="returnModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 hidden">
  <div class="w-full max-w-lg bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 shadow-2xl">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-white text-lg">Return Request Details</h3>
      <button onclick="closeReturnModal()" class="text-gray-500 hover:text-white transition-colors">
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>
    </div>

    <div id="returnModalContent" class="space-y-4 mb-5">
      <!-- Filled by JS -->
    </div>

    <div class="space-y-3">
      <div>
        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5 block">Update Status</label>
        <select id="returnStatusSelect"
                class="w-full px-3 py-2 rounded-xl bg-[hsl(222,47%,14%)] border border-white/10 text-gray-200 text-sm focus:outline-none focus:border-purple-500">
          <option value="pending">Pending Review</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
          <option value="picked_up">Picked Up</option>
          <option value="refunded">Refunded</option>
        </select>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5 block">Admin Notes (sent to customer)</label>
        <textarea id="returnAdminNotes" rows="2"
                  class="w-full px-3 py-2 rounded-xl bg-[hsl(222,47%,14%)] border border-white/10 text-gray-200 text-sm placeholder-gray-600 focus:outline-none focus:border-purple-500 resize-none"
                  placeholder="Optional note for the customer…"></textarea>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5 block">Refund Amount (₹)</label>
        <input id="returnRefundAmount" type="number" min="0" step="0.01"
               class="w-full px-3 py-2 rounded-xl bg-[hsl(222,47%,14%)] border border-white/10 text-gray-200 text-sm placeholder-gray-600 focus:outline-none focus:border-purple-500"
               placeholder="0.00">
      </div>
    </div>

    <div class="flex gap-3 mt-5">
      <button onclick="closeReturnModal()"
              class="flex-1 py-2.5 rounded-xl border border-white/10 text-gray-400 text-sm font-medium hover:bg-white/5 transition-colors">
        Close
      </button>
      <button onclick="saveReturnStatus()"
              class="flex-1 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold transition-colors">
        Save Changes
      </button>
    </div>
  </div>
</div>

<script>
let activeReturnId = null;

function openReturnModal(id, dataJson) {
  activeReturnId = id;
  const r = JSON.parse(dataJson);
  document.getElementById('returnStatusSelect').value  = r.status || 'pending';
  document.getElementById('returnAdminNotes').value    = r.admin_notes  || '';
  document.getElementById('returnRefundAmount').value  = r.refund_amount || '';

  const statusColors = {pending:'#f59e0b',approved:'#3b82f6',rejected:'#ef4444',picked_up:'#8b5cf6',refunded:'#10b981'};
  const col = statusColors[r.status] || '#6b7280';
  document.getElementById('returnModalContent').innerHTML = `
    <div class="grid grid-cols-2 gap-3 text-sm">
      <div class="bg-white/5 rounded-xl p-3">
        <p class="text-gray-500 text-xs mb-0.5">Order</p>
        <p class="text-white font-mono font-semibold">${r.order_number || '—'}</p>
      </div>
      <div class="bg-white/5 rounded-xl p-3">
        <p class="text-gray-500 text-xs mb-0.5">Customer</p>
        <p class="text-white font-semibold">${r.user_name || '—'}</p>
        <p class="text-gray-500 text-xs">${r.user_email || ''}</p>
      </div>
      <div class="bg-white/5 rounded-xl p-3">
        <p class="text-gray-500 text-xs mb-0.5">Current Status</p>
        <p class="font-semibold capitalize" style="color:${col}">${r.status}</p>
      </div>
      <div class="bg-white/5 rounded-xl p-3">
        <p class="text-gray-500 text-xs mb-0.5">Order Total</p>
        <p class="text-white font-semibold">₹${parseFloat(r.order_total||0).toLocaleString('en-IN')}</p>
      </div>
    </div>
    <div class="bg-white/5 rounded-xl p-3">
      <p class="text-gray-500 text-xs mb-1">Reason</p>
      <p class="text-gray-200 text-sm font-medium">${r.reason}</p>
      ${r.description ? `<p class="text-gray-500 text-xs mt-1">${r.description}</p>` : ''}
    </div>
    ${r.admin_notes ? `<div class="bg-white/5 rounded-xl p-3"><p class="text-gray-500 text-xs mb-1">Previous Notes</p><p class="text-gray-300 text-sm">${r.admin_notes}</p></div>` : ''}
  `;
  document.getElementById('returnModal').classList.remove('hidden');
}

function closeReturnModal() {
  document.getElementById('returnModal').classList.add('hidden');
  activeReturnId = null;
}

async function saveReturnStatus() {
  if (!activeReturnId) return;
  const status       = document.getElementById('returnStatusSelect').value;
  const adminNotes   = document.getElementById('returnAdminNotes').value.trim();
  const refundAmount = parseFloat(document.getElementById('returnRefundAmount').value) || null;
  try {
    await apiFetch(`/api/admin/returns/${activeReturnId}`, {
      method: 'PATCH',
      body: JSON.stringify({ status, admin_notes: adminNotes || null, refund_amount: refundAmount })
    });
    showToast('Return updated successfully', 'success');
    setTimeout(() => location.reload(), 800);
  } catch(e) {
    showToast(e.message, 'error');
  }
}

document.getElementById('returnModal').addEventListener('click', function(e) {
  if (e.target === this) closeReturnModal();
});
</script>
