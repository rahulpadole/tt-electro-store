<?php
$pageTitle = 'Offers & Coupons';
$offers  = (new OfferModel())->all();
$coupons = (new CouponModel())->all();
?>

<!-- ── Offers Section ─────────────────────────────────────────────────── -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

  <!-- Offers -->
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-white font-semibold">Offers (<?= count($offers) ?>)</h2>
      <button onclick="openOfferModal()" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Offer</button>
    </div>

    <div class="space-y-2" id="offer-list">
      <?php foreach($offers as $o): ?>
      <div class="flex items-center justify-between p-3 rounded-xl bg-[hsl(222,47%,10%)] border border-white/5" id="offer-<?= $o['id'] ?>">
        <div>
          <p class="text-white font-medium text-sm"><?= clean($o['title']) ?></p>
          <div class="flex gap-2 mt-0.5 text-xs text-gray-500">
            <?php if($o['badge']): ?><span class="text-orange-400"><?= clean($o['badge']) ?></span><?php endif; ?>
            <?php if($o['discount']): ?><span><?= rtrim($o['discount'],'%') ?>% off</span><?php endif; ?>
            <?php if($o['ends_at']): ?><span>· ends <?= date('M j, Y', strtotime($o['ends_at'])) ?></span><?php endif; ?>
          </div>
        </div>
        <button onclick="deleteOffer(<?= $o['id'] ?>)" class="text-gray-500 hover:text-red-400 text-xs transition-colors px-2 py-1 rounded hover:bg-red-500/10">✕ Delete</button>
      </div>
      <?php endforeach; ?>
      <?php if(empty($offers)): ?>
      <p class="text-gray-500 text-sm text-center py-6">No offers yet. Add your first offer.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Coupons -->
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-white font-semibold">Coupons (<?= count($coupons) ?>)</h2>
      <button onclick="openCouponModal()" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm transition-colors">+ Add Coupon</button>
    </div>

    <div class="space-y-2" id="coupon-list">
      <?php foreach($coupons as $c): ?>
      <div class="flex items-center justify-between p-3 rounded-xl bg-[hsl(222,47%,10%)] border border-white/5" id="coupon-<?= $c['id'] ?>">
        <div>
          <span class="font-mono font-bold text-green-400"><?= clean($c['code']) ?></span>
          <div class="flex gap-2 mt-0.5 text-xs text-gray-500">
            <span><?= $c['discount_type']==='percent' ? $c['discount'].'%' : '₹'.number_format((float)$c['discount'],2) ?> off</span>
            <?php if($c['min_order_amount']): ?><span>· min ₹<?= number_format((float)$c['min_order_amount'],0) ?></span><?php endif; ?>
            <?php if($c['expires_at']): ?><span>· exp <?= date('M j, Y', strtotime($c['expires_at'])) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button onclick="toggleCoupon(<?= $c['id'] ?>, <?= $c['is_active'] ? 0 : 1 ?>)"
                  id="toggle-<?= $c['id'] ?>"
                  class="text-xs px-2 py-1 rounded transition-colors <?= $c['is_active'] ? 'bg-green-500/20 text-green-400 hover:bg-red-500/20 hover:text-red-400' : 'bg-red-500/20 text-red-400 hover:bg-green-500/20 hover:text-green-400' ?>">
            <?= $c['is_active'] ? 'Active' : 'Inactive' ?>
          </button>
          <button onclick="deleteCoupon(<?= $c['id'] ?>)" class="text-gray-500 hover:text-red-400 text-xs transition-colors px-2 py-1 rounded hover:bg-red-500/10">✕</button>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($coupons)): ?>
      <p class="text-gray-500 text-sm text-center py-6">No coupons yet. Add your first coupon.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ── Add Offer Modal ─────────────────────────────────────────────────── -->
<div id="offer-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60">
  <div class="w-full max-w-md bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6">
    <h3 class="font-bold text-white mb-4">Add Offer</h3>
    <div class="space-y-3">
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Title *</label>
        <input type="text" id="offer-title" placeholder="Flash Sale – Sensors 40% Off"
               class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
      </div>
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Description</label>
        <textarea id="offer-description" rows="2" placeholder="Tell customers about this offer..."
                  class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Type</label>
          <select id="offer-type"
                  class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
            <option value="flash">Flash Sale</option>
            <option value="coupon">Coupon Offer</option>
            <option value="clearance">Clearance</option>
            <option value="seasonal">Seasonal</option>
          </select>
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Discount %</label>
          <input type="number" id="offer-discount" min="0" max="100" placeholder="20"
                 class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Badge Label</label>
          <input type="text" id="offer-badge" placeholder="HOT DEAL"
                 class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Ends At</label>
          <input type="datetime-local" id="offer-ends-at"
                 class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
      </div>
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Image URL (optional)</label>
        <input type="url" id="offer-image" placeholder="https://..."
               class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
      </div>
      <p id="offer-error" class="text-red-400 text-xs hidden"></p>
    </div>
    <div class="flex gap-3 mt-5">
      <button onclick="closeOfferModal()" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5 transition-colors">Cancel</button>
      <button id="offer-save-btn" onclick="saveOffer()" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors disabled:opacity-50">Save Offer</button>
    </div>
  </div>
</div>

<!-- ── Add Coupon Modal ────────────────────────────────────────────────── -->
<div id="coupon-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60">
  <div class="w-full max-w-sm bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6">
    <h3 class="font-bold text-white mb-4">Add Coupon</h3>
    <div class="space-y-3">
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Coupon Code *</label>
        <input type="text" id="coupon-code" placeholder="SAVE20"
               class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 uppercase font-mono tracking-widest"
               oninput="this.value=this.value.toUpperCase()">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Type</label>
          <select id="coupon-type"
                  class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
            <option value="percent">Percent (%)</option>
            <option value="fixed">Fixed (₹)</option>
          </select>
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Discount *</label>
          <input type="number" id="coupon-discount" min="0" placeholder="10"
                 class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Min Order (₹)</label>
          <input type="number" id="coupon-min" placeholder="499"
                 class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Max Discount (₹)</label>
          <input type="number" id="coupon-max" placeholder="500"
                 class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
      </div>
      <div>
        <label class="text-xs text-gray-400 mb-1 block">Expires At (optional)</label>
        <input type="datetime-local" id="coupon-expires"
               class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
      </div>
      <p id="coupon-error" class="text-red-400 text-xs hidden"></p>
    </div>
    <div class="flex gap-3 mt-5">
      <button onclick="closeCouponModal()" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5 transition-colors">Cancel</button>
      <button id="coupon-save-btn" onclick="saveCoupon()" class="flex-1 py-2.5 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm transition-colors disabled:opacity-50">Save Coupon</button>
    </div>
  </div>
</div>

<script>
/* ── Offer Modal ─────────────────────────────────────── */
function openOfferModal() {
  document.getElementById('offer-modal').classList.remove('hidden');
  document.getElementById('offer-modal').classList.add('flex');
}
function closeOfferModal() {
  document.getElementById('offer-modal').classList.add('hidden');
  document.getElementById('offer-modal').classList.remove('flex');
  document.getElementById('offer-error').classList.add('hidden');
  ['offer-title','offer-description','offer-discount','offer-badge','offer-ends-at','offer-image'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
}
async function saveOffer() {
  const title = document.getElementById('offer-title').value.trim();
  if (!title) { showOfferError('Title is required.'); return; }
  const btn = document.getElementById('offer-save-btn');
  btn.disabled = true; btn.textContent = 'Saving…';
  try {
    await apiFetch('/api/offers', {
      method: 'POST',
      body: JSON.stringify({
        title,
        description: document.getElementById('offer-description').value.trim() || null,
        type:        document.getElementById('offer-type').value,
        discount:    document.getElementById('offer-discount').value || null,
        badge:       document.getElementById('offer-badge').value.trim() || null,
        ends_at:     document.getElementById('offer-ends-at').value || null,
        image:       document.getElementById('offer-image').value.trim() || null,
      })
    });
    showToast('Offer created!', 'success');
    setTimeout(() => location.reload(), 600);
  } catch(e) {
    showOfferError(e.message || 'Failed to create offer.');
    btn.disabled = false; btn.textContent = 'Save Offer';
  }
}
function showOfferError(msg) {
  const el = document.getElementById('offer-error');
  el.textContent = msg;
  el.classList.remove('hidden');
}

/* ── Coupon Modal ────────────────────────────────────── */
function openCouponModal() {
  document.getElementById('coupon-modal').classList.remove('hidden');
  document.getElementById('coupon-modal').classList.add('flex');
}
function closeCouponModal() {
  document.getElementById('coupon-modal').classList.add('hidden');
  document.getElementById('coupon-modal').classList.remove('flex');
  document.getElementById('coupon-error').classList.add('hidden');
  ['coupon-code','coupon-discount','coupon-min','coupon-max','coupon-expires'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
}
async function saveCoupon() {
  const code     = document.getElementById('coupon-code').value.trim();
  const discount = document.getElementById('coupon-discount').value;
  if (!code)     { showCouponError('Coupon code is required.'); return; }
  if (!discount) { showCouponError('Discount amount is required.'); return; }
  const btn = document.getElementById('coupon-save-btn');
  btn.disabled = true; btn.textContent = 'Saving…';
  try {
    await apiFetch('/api/coupons', {
      method: 'POST',
      body: JSON.stringify({
        code,
        discount_type:     document.getElementById('coupon-type').value,
        discount:          parseFloat(discount),
        min_order_amount:  document.getElementById('coupon-min').value || null,
        max_discount:      document.getElementById('coupon-max').value || null,
        expires_at:        document.getElementById('coupon-expires').value || null,
        is_active:         true,
      })
    });
    showToast('Coupon created!', 'success');
    setTimeout(() => location.reload(), 600);
  } catch(e) {
    showCouponError(e.message || 'Failed to create coupon.');
    btn.disabled = false; btn.textContent = 'Save Coupon';
  }
}
function showCouponError(msg) {
  const el = document.getElementById('coupon-error');
  el.textContent = msg;
  el.classList.remove('hidden');
}

/* ── Delete & Toggle ─────────────────────────────────── */
async function deleteOffer(id) {
  if (!confirm('Delete this offer?')) return;
  try {
    await apiFetch(`/api/offers/${id}`, { method: 'DELETE' });
    document.getElementById(`offer-${id}`)?.remove();
    showToast('Offer deleted', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}

async function deleteCoupon(id) {
  if (!confirm('Delete this coupon?')) return;
  try {
    await apiFetch(`/api/coupons/${id}`, { method: 'DELETE' });
    document.getElementById(`coupon-${id}`)?.remove();
    showToast('Coupon deleted', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}

async function toggleCoupon(id, newState) {
  try {
    await apiFetch(`/api/coupons/${id}`, {
      method: 'PATCH',
      body: JSON.stringify({ is_active: newState })
    });
    showToast(newState ? 'Coupon activated' : 'Coupon deactivated', 'success');
    setTimeout(() => location.reload(), 400);
  } catch(e) { showToast(e.message, 'error'); }
}

/* ── Backdrop click closes modals ────────────────────── */
document.getElementById('offer-modal').addEventListener('click', function(e) {
  if (e.target === this) closeOfferModal();
});
document.getElementById('coupon-modal').addEventListener('click', function(e) {
  if (e.target === this) closeCouponModal();
});
</script>
