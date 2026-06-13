<?php
$pageTitle = 'Brands';
$brands = (new BrandModel())->all();
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">Brands (<?= count($brands) ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Brand</button>
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-sm bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{name:'',logo:'',loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">Add Brand</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Name *</label><input type="text" x-model="name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Logo URL</label><input type="url" x-model="logo" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/brands',{method:'POST',body:JSON.stringify({name,logo})}).then(()=>{showToast('Created!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50"><span x-show="!loading">Save</span><span x-show="loading">Saving...</span></button>
      </div>
    </div>
  </div>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
  <?php foreach($brands as $b): ?>
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 p-4 text-center" id="brand-<?= $b['id'] ?>">
    <?php if($b['logo']): ?><img src="<?= clean($b['logo']) ?>" class="w-12 h-12 object-contain mx-auto rounded-lg mb-2"><?php else: ?><div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center text-xl mx-auto mb-2">🏷️</div><?php endif; ?>
    <p class="text-white text-sm font-medium"><?= clean($b['name']) ?></p>
    <button onclick="deleteBrand(<?= $b['id'] ?>)" class="mt-2 text-gray-500 hover:text-red-400 text-xs transition-colors">Remove</button>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteBrand(id){if(!confirm('Delete this brand?'))return;try{await apiFetch(`/api/brands/${id}`,{method:'DELETE'});document.getElementById(`brand-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
