<?php
$pageTitle = '3D Print Requests';
$pm = new Print3DModel();
$page = max(1,(int)($_GET['page']??1));
$limit = 20; $offset = ($page-1)*$limit;
$requests = $pm->all($limit,$offset);
$statusColors = [
    'pending'  => 'yellow',
    'reviewing'=> 'blue',
    'quoted'   => 'purple',
    'printing' => 'cyan',
    'completed'=> 'green',
    'cancelled'=> 'red',
];
?>
<div class="flex items-center justify-between mb-5">
  <h2 class="text-white font-semibold">3D Print Requests <span class="text-gray-500 font-normal text-sm">(<?= count($requests) ?>)</span></h2>
</div>

<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
          <th class="px-4 py-3 text-left">#</th>
          <th class="px-4 py-3 text-left">Customer</th>
          <th class="px-4 py-3 text-left">Material</th>
          <th class="px-4 py-3 text-center">Qty</th>
          <th class="px-4 py-3 text-right">Est. Price</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-left">Submitted</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($requests)): ?>
        <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">No 3D print requests yet.</td></tr>
        <?php endif; ?>
        <?php foreach($requests as $r):
          $sc = $statusColors[$r['status']] ?? 'gray';
        ?>
        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="req-row-<?= $r['id'] ?>">
          <td class="px-4 py-3 text-gray-500 font-mono text-xs">#<?= $r['id'] ?></td>
          <td class="px-4 py-3">
            <p class="text-gray-200 text-sm"><?= clean($r['user_name']??'—') ?></p>
            <p class="text-gray-500 text-xs"><?= clean($r['user_email']??'') ?></p>
          </td>
          <td class="px-4 py-3 text-gray-300 capitalize"><?= clean($r['material']??'—') ?></td>
          <td class="px-4 py-3 text-center text-gray-300"><?= (int)($r['quantity']??1) ?></td>
          <td class="px-4 py-3 text-right">
            <?php if($r['estimated_price']): ?>
              <span class="text-green-400 font-semibold">₹<?= number_format((float)$r['estimated_price'],0) ?></span>
            <?php else: ?>
              <span class="text-gray-500 text-xs">Not quoted</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-2 py-0.5 rounded-full text-xs bg-<?= $sc ?>-500/20 text-<?= $sc ?>-400 capitalize"><?= clean($r['status']) ?></span>
          </td>
          <td class="px-4 py-3 text-gray-500 text-xs"><?= date('M j, Y', strtotime($r['created_at'])) ?></td>
          <td class="px-4 py-3 text-center">
            <button onclick="openRequestModal(<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>)"
                    class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded border border-blue-500/20 hover:bg-blue-500/10 transition-all">
              Manage
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Request Detail Modal -->
<div id="reqModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60">
  <div class="w-full max-w-lg bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-white text-lg">Manage Print Request #<span id="modalId"></span></h3>
      <button onclick="closeRequestModal()" class="text-gray-500 hover:text-white text-xl leading-none">×</button>
    </div>

    <div class="space-y-3 mb-5 text-sm">
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-white/5 rounded-lg p-3">
          <p class="text-gray-500 text-xs mb-1">Customer</p>
          <p class="text-white font-medium" id="modalCustomer"></p>
        </div>
        <div class="bg-white/5 rounded-lg p-3">
          <p class="text-gray-500 text-xs mb-1">Material</p>
          <p class="text-white font-medium capitalize" id="modalMaterial"></p>
        </div>
        <div class="bg-white/5 rounded-lg p-3">
          <p class="text-gray-500 text-xs mb-1">Quantity</p>
          <p class="text-white font-medium" id="modalQty"></p>
        </div>
        <div class="bg-white/5 rounded-lg p-3">
          <p class="text-gray-500 text-xs mb-1">Submitted</p>
          <p class="text-white font-medium" id="modalDate"></p>
        </div>
      </div>
      <div class="bg-white/5 rounded-lg p-3">
        <p class="text-gray-500 text-xs mb-1">Description</p>
        <p class="text-gray-300 text-sm" id="modalDesc"></p>
      </div>
      <div id="modalFileWrap" class="hidden bg-white/5 rounded-lg p-3">
        <p class="text-gray-500 text-xs mb-1">File / Image URL</p>
        <a id="modalFile" href="#" target="_blank" class="text-blue-400 hover:underline text-xs break-all"></a>
      </div>
    </div>

    <div class="space-y-3 border-t border-white/10 pt-4">
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Update Status</label>
        <select id="modalStatus" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
          <option value="pending">Pending</option>
          <option value="reviewing">Reviewing</option>
          <option value="quoted">Quoted</option>
          <option value="printing">Printing</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Estimated Price (₹)</label>
        <input type="number" id="modalPrice" placeholder="e.g. 1200" step="1"
               class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
      </div>
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Admin Note</label>
        <textarea id="modalNote" rows="2" placeholder="Internal note or message to customer..."
                  class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea>
      </div>
      <p id="modalError" class="text-red-400 text-xs hidden"></p>
    </div>

    <div class="flex gap-3 mt-5">
      <button onclick="closeRequestModal()" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5 transition-colors">Cancel</button>
      <button onclick="saveRequest()" id="modalSaveBtn" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">
        Save Changes
      </button>
    </div>
  </div>
</div>

<script>
let currentReqId = null;

function openRequestModal(r) {
  currentReqId = r.id;
  document.getElementById('modalId').textContent = r.id;
  document.getElementById('modalCustomer').textContent = (r.user_name||'—') + ' <' + (r.user_email||'') + '>';
  document.getElementById('modalMaterial').textContent = r.material || '—';
  document.getElementById('modalQty').textContent = r.quantity || 1;
  document.getElementById('modalDate').textContent = new Date(r.created_at).toLocaleDateString('en-IN');
  document.getElementById('modalDesc').textContent = r.description || 'No description provided.';
  const fileUrl = r.file_url || r.image_url;
  if (fileUrl) {
    document.getElementById('modalFileWrap').classList.remove('hidden');
    const a = document.getElementById('modalFile');
    a.href = fileUrl; a.textContent = fileUrl;
  } else {
    document.getElementById('modalFileWrap').classList.add('hidden');
  }
  document.getElementById('modalStatus').value = r.status || 'pending';
  document.getElementById('modalPrice').value = r.estimated_price || '';
  document.getElementById('modalNote').value = r.admin_note || '';
  document.getElementById('modalError').classList.add('hidden');
  document.getElementById('reqModal').classList.remove('hidden');
}

function closeRequestModal() {
  document.getElementById('reqModal').classList.add('hidden');
  currentReqId = null;
}

async function saveRequest() {
  if (!currentReqId) return;
  const btn = document.getElementById('modalSaveBtn');
  const errEl = document.getElementById('modalError');
  btn.disabled = true; btn.textContent = 'Saving...';
  errEl.classList.add('hidden');
  try {
    const body = {
      status: document.getElementById('modalStatus').value,
      estimated_price: document.getElementById('modalPrice').value || null,
      admin_note: document.getElementById('modalNote').value || null,
    };
    await apiFetch(`/api/print3d/${currentReqId}`, { method:'PATCH', body:JSON.stringify(body) });
    showToast('Request updated!', 'success');
    setTimeout(() => location.reload(), 700);
  } catch(e) {
    errEl.textContent = e.message;
    errEl.classList.remove('hidden');
  }
  btn.disabled = false; btn.textContent = 'Save Changes';
}

document.getElementById('reqModal').addEventListener('click', function(e) {
  if (e.target === this) closeRequestModal();
});
</script>
