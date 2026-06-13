<?php
$pageTitle = 'Banners';
$banners = (new BannerModel())->all();
?>
<div x-data="bannerManager()" x-init="init()">

  <!-- Header -->
  <div class="flex items-center justify-between mb-5">
    <h2 class="text-white font-semibold">Hero Banners (<?= count($banners) ?>)</h2>
    <button @click="openAdd()" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Banner</button>
  </div>

  <!-- Banner List -->
  <div class="space-y-4" id="banner-list">
    <?php foreach($banners as $b): ?>
    <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 p-4 flex items-center gap-4" id="banner-<?= $b['id'] ?>">
      <?php if($b['image']): ?>
        <img src="<?= clean($b['image']) ?>" class="w-24 h-14 rounded-xl object-cover flex-shrink-0">
      <?php else: ?>
        <div class="w-24 h-14 rounded-xl bg-white/5 flex items-center justify-center text-2xl flex-shrink-0">🖼️</div>
      <?php endif; ?>
      <div class="flex-1 min-w-0">
        <p class="text-white font-semibold"><?= clean($b['title']) ?></p>
        <?php if($b['subtitle']): ?><p class="text-gray-400 text-sm truncate"><?= clean($b['subtitle']) ?></p><?php endif; ?>
        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
          <?php if($b['badge']): ?><span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400"><?= clean($b['badge']) ?></span><?php endif; ?>
          <span><?= $b['is_active'] ? '✓ Active' : '✗ Inactive' ?></span>
          <?php if($b['link']): ?><span><?= clean($b['link']) ?></span><?php endif; ?>
        </div>
      </div>
      <div class="flex gap-2">
        <button @click="openEdit(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)"
                class="px-3 py-1.5 rounded-lg border border-blue-500/30 text-blue-400 hover:bg-blue-500/10 text-xs transition-all">Edit</button>
        <button onclick="toggleBanner(<?= $b['id'] ?>,<?= $b['is_active'] ? 'false' : 'true' ?>)"
                class="px-3 py-1.5 rounded-lg border border-white/10 text-gray-400 hover:text-white text-xs transition-all"><?= $b['is_active'] ? 'Disable' : 'Enable' ?></button>
        <button onclick="deleteBanner(<?= $b['id'] ?>)"
                class="px-3 py-1.5 rounded-lg border border-red-500/20 text-red-400 hover:bg-red-500/10 text-xs transition-all">Delete</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Add / Edit Modal -->
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false" x-cloak>
    <div class="w-full max-w-lg bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[90vh] overflow-y-auto">
      <h3 class="font-bold text-white mb-5" x-text="editId ? 'Edit Banner' : 'Add Banner'"></h3>
      <div class="space-y-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Title *</label>
          <input type="text" x-model="form.title" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Subtitle</label>
          <input type="text" x-model="form.subtitle" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Image URL</label>
          <input type="url" x-model="form.image" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
          <div x-show="form.image" class="mt-2 rounded-lg overflow-hidden h-28 bg-white/5">
            <img :src="form.image" class="w-full h-full object-cover" @error="$el.style.display='none'">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-xs text-gray-400 mb-1 block">Link (URL path)</label>
            <input type="text" x-model="form.link" placeholder="/products" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
          </div>
          <div>
            <label class="text-xs text-gray-400 mb-1 block">Badge label</label>
            <input type="text" x-model="form.badge" placeholder="New Arrivals" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-xs text-gray-400 mb-1 block">Position (sort order)</label>
            <input type="number" x-model="form.position" min="0" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
          </div>
          <div class="flex items-end pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
              <input type="checkbox" x-model="form.is_active" class="accent-blue-500 w-4 h-4"> Active
            </label>
          </div>
        </div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-5">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="save()" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50">
          <span x-show="!loading" x-text="editId ? 'Save Changes' : 'Add Banner'"></span>
          <span x-show="loading">Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function bannerManager() {
  return {
    showModal: false,
    editId: null,
    loading: false,
    error: '',
    form: { title:'', subtitle:'', image:'', link:'', badge:'', position:0, is_active:true },

    init() {},

    openAdd() {
      this.editId = null;
      this.form = { title:'', subtitle:'', image:'', link:'', badge:'', position:0, is_active:true };
      this.error = '';
      this.showModal = true;
    },

    openEdit(banner) {
      this.editId = banner.id;
      this.form = {
        title:    banner.title    || '',
        subtitle: banner.subtitle || '',
        image:    banner.image    || '',
        link:     banner.link     || '',
        badge:    banner.badge    || '',
        position: banner.position || 0,
        is_active: !!banner.is_active,
      };
      this.error = '';
      this.showModal = true;
    },

    async save() {
      if (!this.form.title) { this.error = 'Title is required'; return; }
      this.loading = true; this.error = '';
      try {
        if (this.editId) {
          await apiFetch(`/api/banners/${this.editId}`, { method:'PATCH', body: JSON.stringify(this.form) });
          showToast('Banner updated!', 'success');
        } else {
          if (!this.form.image) { this.error = 'Image URL is required'; this.loading = false; return; }
          await apiFetch('/api/banners', { method:'POST', body: JSON.stringify(this.form) });
          showToast('Banner created!', 'success');
        }
        setTimeout(() => location.reload(), 600);
      } catch(e) {
        this.error = e.message;
        this.loading = false;
      }
    }
  };
}

async function toggleBanner(id, val) {
  try {
    await apiFetch(`/api/banners/${id}`, { method:'PATCH', body: JSON.stringify({ is_active: val }) });
    showToast('Updated!', 'success');
    setTimeout(() => location.reload(), 600);
  } catch(e) { showToast(e.message, 'error'); }
}

async function deleteBanner(id) {
  if (!confirm('Delete this banner?')) return;
  try {
    await apiFetch(`/api/banners/${id}`, { method:'DELETE' });
    document.getElementById(`banner-${id}`)?.remove();
    showToast('Deleted', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}
</script>
