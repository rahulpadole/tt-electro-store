<?php
$pageTitle = 'Offers & Coupons';
$offers = (new OfferModel())->all();
$coupons = (new CouponModel())->all();
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <!-- Offers -->
  <div>
    <div class="flex items-center justify-between mb-4" x-data="{showModal:false}">
      <h2 class="text-white font-semibold">Offers (<?= count($offers) ?>)</h2>
      <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Offer</button>
      <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
        <div class="w-full max-w-md bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{form:{title:'',description:'',discount:0,type:'flash',ends_at:'',badge:''},loading:false,error:''}">
          <h3 class="font-bold text-white mb-4">Add Offer</h3>
          <div class="space-y-3">
            <div><label class="text-xs text-gray-400 mb-1 block">Title *</label><input type="text" x-model="form.title" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
            <div><label class="text-xs text-gray-400 mb-1 block">Discount %</label><input type="number" x-model="form.discount" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
            <div><label class="text-xs text-gray-400 mb-1 block">Ends At</label><input type="datetime-local" x-model="form.ends_at" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
            <div><label class="text-xs text-gray-400 mb-1 block">Badge</label><input type="text" x-model="form.badge" placeholder="HOT DEAL" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          </div>
          <div class="flex gap-3 mt-4">
            <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
            <button @click="loading=true;apiFetch('/api/offers',{method:'POST',body:JSON.stringify(form)}).then(()=>{showToast('Created!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm disabled:opacity-50"><span x-show="!loading">Save</span><span x-show="loading">...</span></button>
          </div>
        </div>
      </div>
    </div>
    <div class="space-y-2">
      <?php foreach($offers as $o): ?>
      <div class="flex items-center justify-between p-3 rounded-xl bg-[hsl(222,47%,10%)] border border-white/5" id="offer-<?= $o['id'] ?>">
        <div><p class="text-white font-medium text-sm"><?= clean($o['title']) ?></p>
        <div class="flex gap-2 mt-0.5 text-xs text-gray-500"><?php if($o['discount']): ?><span><?= $o['discount'] ?>% off</span><?php endif; ?><?php if($o['ends_at']): ?><span>· ends <?= date('M j',strtotime($o['ends_at'])) ?></span><?php endif; ?></div></div>
        <button onclick="deleteOffer(<?= $o['id'] ?>)" class="text-gray-500 hover:text-red-400 text-xs transition-colors">✕</button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Coupons -->
  <div>
    <div class="flex items-center justify-between mb-4" x-data="{showModal:false}">
      <h2 class="text-white font-semibold">Coupons (<?= count($coupons) ?>)</h2>
      <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm transition-colors">+ Add Coupon</button>
      <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
        <div class="w-full max-w-sm bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{form:{code:'',discount_type:'percent',discount:10,min_order_amount:'',expires_at:''},loading:false,error:''}">
          <h3 class="font-bold text-white mb-4">Add Coupon</h3>
          <div class="space-y-3">
            <div><label class="text-xs text-gray-400 mb-1 block">Code *</label><input type="text" x-model="form.code" placeholder="SAVE20" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 uppercase font-mono"></div>
            <div class="grid grid-cols-2 gap-2">
              <div><label class="text-xs text-gray-400 mb-1 block">Type</label>
              <select x-model="form.discount_type" class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500"><option value="percent">Percent</option><option value="flat">Flat</option></select></div>
              <div><label class="text-xs text-gray-400 mb-1 block">Discount</label><input type="number" x-model="form.discount" class="w-full px-2 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
            </div>
            <div><label class="text-xs text-gray-400 mb-1 block">Min Order Amount</label><input type="number" x-model="form.min_order_amount" placeholder="499" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          </div>
          <div class="flex gap-3 mt-4">
            <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
            <button @click="loading=true;apiFetch('/api/coupons',{method:'POST',body:JSON.stringify(form)}).then(()=>{showToast('Created!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm disabled:opacity-50"><span x-show="!loading">Save</span><span x-show="loading">...</span></button>
          </div>
        </div>
      </div>
    </div>
    <div class="space-y-2">
      <?php foreach($coupons as $c): ?>
      <div class="flex items-center justify-between p-3 rounded-xl bg-[hsl(222,47%,10%)] border border-white/5" id="coupon-<?= $c['id'] ?>">
        <div>
          <span class="font-mono font-bold text-green-400"><?= clean($c['code']) ?></span>
          <div class="flex gap-2 mt-0.5 text-xs text-gray-500">
            <span><?= $c['discount_type']==='percent'?$c['discount'].'%':'₹'.$c['discount'] ?> off</span>
            <?php if($c['min_order_amount']): ?><span>· min ₹<?= $c['min_order_amount'] ?></span><?php endif; ?>
            <span class="<?= $c['is_active']?'text-green-400':'text-red-400' ?>"><?= $c['is_active']?'Active':'Inactive' ?></span>
          </div>
        </div>
        <button onclick="deleteCoupon(<?= $c['id'] ?>)" class="text-gray-500 hover:text-red-400 text-xs transition-colors">✕</button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script>
async function deleteOffer(id){if(!confirm('Delete?'))return;try{await apiFetch(`/api/offers/${id}`,{method:'DELETE'});document.getElementById(`offer-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
async function deleteCoupon(id){if(!confirm('Delete?'))return;try{await apiFetch(`/api/coupons/${id}`,{method:'DELETE'});document.getElementById(`coupon-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
