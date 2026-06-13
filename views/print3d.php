<?php
$pageTitle = '3D Printing Service';
$myRequests = isLoggedIn() ? (new Print3DModel())->forUser(getCurrentUserId()) : [];
$statusColors = ['pending'=>'yellow','reviewing'=>'blue','printing'=>'purple','ready'=>'green','delivered'=>'green','cancelled'=>'red'];
?>
<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="text-center mb-10">
    <div class="text-5xl mb-4">🖨️</div>
    <h1 class="text-3xl font-bold text-white mb-2">Custom 3D Printing Service</h1>
    <p class="text-gray-400 max-w-xl mx-auto">Upload your design and we'll bring it to life. Professional-grade FDM printing with multiple material options and pan-India delivery.</p>
  </div>

  <!-- Materials Grid -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
    <?php foreach([['PLA','🟢','Biodegradable, easy to print','₹299/100g'],['ABS','🔵','Strong, heat resistant','₹349/100g'],['PETG','🟡','Flexible, transparent','₹379/100g'],['TPU','🔴','Rubber-like, flexible','₹449/100g']] as [$mat,$color,$desc,$price]): ?>
    <div class="rounded-xl bg-[hsl(222,47%,10%)] border border-white/5 p-4 text-center">
      <div class="text-2xl mb-2"><?= $color ?></div>
      <h3 class="font-bold text-white"><?= $mat ?></h3>
      <p class="text-gray-400 text-xs mt-1"><?= $desc ?></p>
      <p class="text-blue-400 font-semibold text-sm mt-2"><?= $price ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Request Form -->
    <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/10 p-6" x-data="printForm()">
      <h2 class="text-xl font-bold text-white mb-5">Submit Print Request</h2>
      <div class="space-y-4">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Material *</label>
          <select x-model="form.material" class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
            <option value="">Select material</option>
            <?php foreach(['PLA','ABS','PETG','TPU','Nylon','ASA'] as $m): ?><option value="<?= $m ?>"><?= $m ?></option><?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Quantity *</label>
          <input type="number" x-model="form.quantity" min="1" placeholder="1" class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">File URL (STL/OBJ)</label>
          <input type="url" x-model="form.file_url" placeholder="https://drive.google.com/..." class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Reference Image URL</label>
          <input type="url" x-model="form.image_url" placeholder="https://..." class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Description / Requirements</label>
          <textarea x-model="form.description" rows="3" placeholder="Dimensions, color, infill percentage, special requirements..." class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 resize-none"></textarea>
        </div>
        <p x-show="error" x-text="error" class="text-red-400 text-sm bg-red-500/10 px-3 py-2 rounded-lg border border-red-500/20"></p>
        <button @click="submit()" :disabled="loading" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all hover:scale-[1.02] disabled:opacity-50">
          <span x-show="!loading">🖨️ Submit Request</span>
          <span x-show="loading">Submitting...</span>
        </button>
        <?php if(!isLoggedIn()): ?><p class="text-xs text-gray-500 text-center">You'll need to <a href="/login" class="text-blue-400">login</a> to track your request</p><?php endif; ?>
      </div>
    </div>

    <!-- My Requests -->
    <div>
      <h2 class="text-xl font-bold text-white mb-5">My Requests</h2>
      <?php if(!isLoggedIn()): ?>
      <div class="rounded-xl bg-[hsl(222,47%,10%)] border border-white/10 p-8 text-center">
        <p class="text-gray-400 text-sm">Please <a href="/login" class="text-blue-400">login</a> to view your print requests</p>
      </div>
      <?php elseif(empty($myRequests)): ?>
      <div class="rounded-xl bg-[hsl(222,47%,10%)] border border-white/10 p-8 text-center">
        <div class="text-4xl mb-3">🖨️</div>
        <p class="text-gray-400 text-sm">No requests yet. Submit one to get started!</p>
      </div>
      <?php else: ?>
      <div class="space-y-3">
        <?php foreach($myRequests as $req):
          $sc = $statusColors[$req['status']] ?? 'gray';
        ?>
        <div class="rounded-xl bg-[hsl(222,47%,10%)] border border-white/5 p-4">
          <div class="flex items-center justify-between mb-2">
            <span class="font-semibold text-white text-sm"><?= clean($req['material']) ?> × <?= $req['quantity'] ?></span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-<?= $sc ?>-500/20 text-<?= $sc ?>-400 capitalize"><?= clean($req['status']) ?></span>
          </div>
          <?php if($req['description']): ?><p class="text-gray-400 text-xs line-clamp-2"><?= clean($req['description']) ?></p><?php endif; ?>
          <?php if($req['estimated_price']): ?><p class="text-blue-400 text-sm font-semibold mt-1">Est. ₹<?= number_format((float)$req['estimated_price'],2) ?></p><?php endif; ?>
          <?php if($req['admin_note']): ?><p class="text-gray-300 text-xs mt-1 italic">"<?= clean($req['admin_note']) ?>"</p><?php endif; ?>
          <p class="text-gray-600 text-xs mt-1.5"><?= date('M j, Y',strtotime($req['created_at'])) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
function printForm() {
  return {
    form: { material:'', quantity:1, file_url:'', image_url:'', description:'' },
    loading: false, error: '',
    async submit() {
      if(!this.form.material||!this.form.quantity) { this.error='Please select material and quantity'; return; }
      this.loading = true; this.error = '';
      try {
        await apiFetch('/api/print3d', { method:'POST', body:JSON.stringify(this.form) });
        showToast('Request submitted! We\'ll get back to you within 24 hours.','success');
        this.form = { material:'', quantity:1, file_url:'', image_url:'', description:'' };
        setTimeout(()=>location.reload(),2000);
      } catch(e) { this.error=e.message; }
      this.loading=false;
    }
  }
}
</script>
