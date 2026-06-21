<?php
$pageTitle = 'Blogs';
$bm = new BlogModel();
$result = $bm->all(1,50);
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">Blog Posts (<?= $result['total'] ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Write Post</button>
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-xl bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[90vh] overflow-y-auto" x-data="{form:{title:'',excerpt:'',content:'',thumbnail:'',author_name:'TT Electro',category:'',reading_time:5},loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">New Blog Post</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Title *</label><input type="text" x-model="form.title" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Category</label><input type="text" x-model="form.category" placeholder="Electronics, Tutorials..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Thumbnail URL</label><input type="url" x-model="form.thumbnail" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Excerpt</label><textarea x-model="form.excerpt" rows="2" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Content *</label><textarea x-model="form.content" rows="8" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none font-mono"></textarea></div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block">Author</label><input type="text" x-model="form.author_name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block">Reading Time (min)</label><input type="number" x-model="form.reading_time" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        </div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/blogs',{method:'POST',body:JSON.stringify(form)}).then(()=>{showToast('Published!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50"><span x-show="!loading">Publish</span><span x-show="loading">Publishing...</span></button>
      </div>
    </div>
  </div>
</div>
<div class="space-y-3">
  <?php foreach($result['items'] as $b): ?>
  <div class="flex items-center gap-4 p-4 rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5" id="blog-<?= $b['id'] ?>">
    <?php if($b['thumbnail']): ?><img src="<?= clean($b['thumbnail']) ?>" class="w-16 h-12 rounded-xl object-cover flex-shrink-0"><?php else: ?><div class="w-16 h-12 rounded-xl bg-white/5 flex items-center justify-center text-xl flex-shrink-0">📝</div><?php endif; ?>
    <div class="flex-1 min-w-0">
      <p class="text-white font-medium line-clamp-1"><?= clean($b['title']) ?></p>
      <div class="flex items-center gap-3 mt-0.5 text-xs text-gray-500">
        <?php if($b['category']): ?><span class="text-blue-400"><?= clean($b['category']) ?></span><?php endif; ?>
        <span>by <?= clean($b['author_name']??'') ?></span>
        <?php if($b['reading_time']): ?><span><?= $b['reading_time'] ?> min</span><?php endif; ?>
        <span><?= date('M j, Y',strtotime($b['created_at'])) ?></span>
        <span>👁 <?= number_format($b['view_count']) ?></span>
      </div>
    </div>
    <div class="flex gap-2">
      <a href="/blogs/<?= clean($b['slug']) ?>" target="_blank" class="px-2 py-1 rounded border border-white/10 text-gray-400 hover:text-white text-xs">View</a>
      <button onclick="deleteBlog(<?= $b['id'] ?>)" class="px-2 py-1 rounded border border-red-500/20 text-red-400 hover:bg-red-500/10 text-xs">Delete</button>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteBlog(id){if(!confirm('Delete this blog post?'))return;try{await apiFetch(`/api/blogs/${id}`,{method:'DELETE'});document.getElementById(`blog-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
