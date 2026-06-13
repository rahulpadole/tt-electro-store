<?php
$pageTitle = 'DIY Kits';
$kits = (new DiyKitModel())->all();
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">DIY Kits (<?= count($kits) ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-sm transition-colors">+ Add Kit</button>
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-lg bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[90vh] overflow-y-auto" x-data="{form:{name:'',description:'',price:0,thumbnail:'',difficulty:'beginner',stock:10,pdf_url:'',video_url:''},loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">Add DIY Kit</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Name *</label><input type="text" x-model="form.name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block">Price *</label><input type="number" x-model="form.price" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block">Stock</label><input type="number" x-model="form.stock" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        </div>
        <div><label class="text-xs text-gray-400 mb-1 block">Difficulty</label>
        <select x-model="form.difficulty" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500"><option value="beginner">Beginner</option><option value="intermediate">Intermediate</option><option value="advanced">Advanced</option></select></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Thumbnail URL</label><input type="url" x-model="form.thumbnail" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Description</label><textarea x-model="form.description" rows="3" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/diy-kits',{method:'POST',body:JSON.stringify(form)}).then(()=>{showToast('Created!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-sm disabled:opacity-50"><span x-show="!loading">Save</span><span x-show="loading">...</span></button>
      </div>
    </div>
  </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <?php foreach($kits as $k): ?>
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden" id="kit-<?= $k['id'] ?>">
    <?php if($k['thumbnail']): ?><img src="<?= clean($k['thumbnail']) ?>" class="w-full h-32 object-cover"><?php else: ?><div class="h-32 bg-gradient-to-br from-purple-900/20 to-blue-900/20 flex items-center justify-center text-4xl">🔧</div><?php endif; ?>
    <div class="p-4">
      <div class="flex items-center gap-2 mb-1"><span class="text-xs text-purple-400 font-medium capitalize"><?= clean($k['difficulty']??'') ?></span></div>
      <p class="font-semibold text-white"><?= clean($k['name']) ?></p>
      <p class="text-gray-300 font-bold mt-1">₹<?= number_format((float)$k['price'],2) ?></p>
      <div class="flex items-center justify-between mt-3">
        <span class="text-xs text-gray-500">Stock: <?= $k['stock'] ?></span>
        <button onclick="deleteKit(<?= $k['id'] ?>)" class="text-xs text-red-400 hover:text-red-300 transition-colors">Delete</button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteKit(id){if(!confirm('Delete this kit?'))return;try{await apiFetch(`/api/diy-kits/${id}`,{method:'DELETE'});document.getElementById(`kit-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
