<?php
$pageTitle = 'Categories';
$cats = (new CategoryModel())->all();
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">Categories (<?= count($cats) ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Category</button>

  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-md bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{name:'',slug:'',description:'',image:'',icon:'',loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">Add Category</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Name *</label><input type="text" x-model="name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Icon (emoji)</label><input type="text" x-model="icon" placeholder="📦" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Image URL</label><input type="url" x-model="image" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Description</label><textarea x-model="description" rows="2" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/categories',{method:'POST',body:JSON.stringify({name,icon,image,description})}).then(()=>{showToast('Created!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50">
          <span x-show="!loading">Save</span><span x-show="loading">Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
  <?php foreach($cats as $c): ?>
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 p-4 flex items-center gap-3" id="cat-<?= $c['id'] ?>">
    <?php if($c['image']): ?><img src="<?= clean($c['image']) ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
    <?php else: ?><div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-2xl flex-shrink-0"><?= $c['icon']?clean($c['icon']):'📦' ?></div><?php endif; ?>
    <div class="flex-1 min-w-0">
      <p class="text-white font-medium text-sm truncate"><?= clean($c['name']) ?></p>
      <p class="text-gray-500 text-xs">/<?= clean($c['slug']) ?></p>
    </div>
    <button onclick="deleteCat(<?= $c['id'] ?>)" class="text-gray-500 hover:text-red-400 transition-colors text-xs">✕</button>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteCat(id) {
  if(!confirm('Delete this category? Products in it will lose their category.')) return;
  try { await apiFetch(`/api/categories/${id}`, {method:'DELETE'}); document.getElementById(`cat-${id}`)?.remove(); showToast('Deleted','success'); } catch(e) { showToast(e.message,'error'); }
}
</script>
