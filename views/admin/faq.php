<?php
$pageTitle = 'FAQ';
$faqs = (new FaqModel())->all();
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">FAQ Items (<?= count($faqs) ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add FAQ</button>
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-lg bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{question:'',answer:'',category:'',loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">Add FAQ</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Category</label><input type="text" x-model="category" placeholder="Shipping, Returns, Products..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Question *</label><input type="text" x-model="question" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Answer *</label><textarea x-model="answer" rows="4" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/faq',{method:'POST',body:JSON.stringify({question,answer,category})}).then(()=>{showToast('Added!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50"><span x-show="!loading">Save</span><span x-show="loading">Saving...</span></button>
      </div>
    </div>
  </div>
</div>
<div class="space-y-3">
  <?php foreach($faqs as $f): ?>
  <div class="p-4 rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 flex gap-4" id="faq-<?= $f['id'] ?>">
    <div class="flex-1">
      <?php if($f['category']): ?><span class="text-xs text-blue-400 font-medium"><?= clean($f['category']) ?></span><?php endif; ?>
      <p class="font-medium text-white text-sm mt-0.5"><?= clean($f['question']) ?></p>
      <p class="text-gray-400 text-sm mt-1 line-clamp-2"><?= clean($f['answer']) ?></p>
    </div>
    <button onclick="deleteFaq(<?= $f['id'] ?>)" class="text-gray-500 hover:text-red-400 transition-colors flex-shrink-0 text-sm">✕</button>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteFaq(id){if(!confirm('Delete this FAQ?'))return;try{await apiFetch(`/api/faq/${id}`,{method:'DELETE'});document.getElementById(`faq-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
