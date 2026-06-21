<?php
$pageTitle = 'Brands';
$brands = (new BrandModel())->all();
?>
<div x-data="brandsManager()" x-init="init()">

  <div class="flex items-center justify-between mb-5">
    <h2 class="text-white font-semibold">Brands (<span x-text="brands.length"></span>)</h2>
    <button @click="openAdd()" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Add Brand</button>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    <template x-for="b in brands.filter(x=>x&&x.id)" :key="String(b.id)">
      <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 p-4 text-center group relative">
        <template x-if="b.logo">
          <img :src="b.logo" class="w-12 h-12 object-contain mx-auto rounded-lg mb-2">
        </template>
        <template x-if="!b.logo">
          <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center text-xl mx-auto mb-2">🏷️</div>
        </template>
        <p class="text-white text-sm font-medium truncate" x-text="b.name"></p>
        <div class="flex justify-center gap-2 mt-2">
          <button @click="openEdit(b)" class="text-gray-500 hover:text-blue-400 text-xs transition-colors">Edit</button>
          <span class="text-gray-600 text-xs">·</span>
          <button @click="deleteBrand(b.id)" class="text-gray-500 hover:text-red-400 text-xs transition-colors">Remove</button>
        </div>
      </div>
    </template>
    <template x-if="brands.length === 0">
      <div class="col-span-full text-center text-gray-500 py-12">No brands yet.</div>
    </template>
  </div>

  <!-- Add / Edit Modal -->
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-sm bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6">
      <h3 class="font-bold text-white mb-4" x-text="editId ? 'Edit Brand' : 'Add Brand'"></h3>
      <div class="space-y-3">
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Name *</label>
          <input type="text" x-model="form.name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label class="text-xs text-gray-400 mb-1 block">Logo URL</label>
          <input type="url" x-model="form.logo" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500">
        </div>
        <template x-if="form.logo">
          <img :src="form.logo" class="w-16 h-16 object-contain rounded-lg border border-white/10 mx-auto mt-1">
        </template>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-5">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="save()" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium disabled:opacity-50">
          <span x-show="!loading" x-text="editId ? 'Update' : 'Save'"></span>
          <span x-show="loading">Saving…</span>
        </button>
      </div>
    </div>
  </div>

</div>

<script>
function brandsManager() {
  return {
    brands: <?= json_encode(array_values((new BrandModel())->all()), JSON_UNESCAPED_UNICODE) ?>,
    showModal: false,
    editId: null,
    form: { name: '', logo: '' },
    loading: false,
    error: '',

    init() {},

    openAdd() {
      this.editId = null;
      this.form = { name: '', logo: '' };
      this.error = '';
      this.loading = false;
      this.showModal = true;
    },

    openEdit(b) {
      this.editId = b.id;
      this.form = { name: b.name, logo: b.logo || '' };
      this.error = '';
      this.loading = false;
      this.showModal = true;
    },

    async save() {
      if (!this.form.name.trim()) { this.error = 'Name is required.'; return; }
      this.loading = true;
      this.error = '';
      try {
        const url    = this.editId ? `/api/brands/${this.editId}` : '/api/brands';
        const method = this.editId ? 'PATCH' : 'POST';
        const result = await apiFetch(url, { method, body: JSON.stringify({ name: this.form.name.trim(), logo: this.form.logo.trim() || null }) });
        const brand  = result.data;
        if (!brand || !brand.id) throw new Error('Invalid response from server — please refresh.');
        const bid = Number(brand.id);
        if (this.editId) {
          const idx = this.brands.findIndex(b => Number(b.id) === Number(this.editId));
          if (idx !== -1) this.brands[idx] = brand; else this.brands.push(brand);
        } else {
          this.brands.push(brand);
        }
        showToast(this.editId ? 'Brand updated!' : 'Brand created!', 'success');
        this.showModal = false;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },

    async deleteBrand(id) {
      if (!id || id === 'undefined') { showToast('Invalid brand ID', 'error'); return; }
      if (!confirm('Delete this brand?')) return;
      try {
        await apiFetch(`/api/brands/${id}`, { method: 'DELETE' });
        this.brands = this.brands.filter(b => Number(b.id) !== Number(id));
        showToast('Deleted', 'success');
      } catch (e) {
        showToast(e.message, 'error');
      }
    }
  };
}
</script>
