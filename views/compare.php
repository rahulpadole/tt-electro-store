<?php
$pageTitle = 'Compare Products';
?>
<div class="max-w-7xl mx-auto px-4 py-8" x-data="compareView()" x-init="init()">
  <div class="mb-7 flex items-center justify-between flex-wrap gap-3">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Compare Products</h1>
    <button x-show="products.length" @click="clearAll()" class="text-xs text-slate-500 hover:text-red-500 border border-slate-200 dark:border-white/8 hover:border-red-300 dark:hover:border-red-500/30 px-3 py-2 rounded-lg transition-colors">
      <i class="fa-solid fa-trash-can mr-1"></i> Clear All
    </button>
  </div>

  <div x-show="loading" class="text-center py-24 text-slate-400">
    <i class="fa-solid fa-spinner fa-spin text-3xl"></i>
  </div>

  <div x-show="!loading && products.length === 0" x-cloak class="text-center py-24 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
    <div class="w-20 h-20 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center mx-auto mb-5">
      <i class="fa-solid fa-scale-balanced text-3xl text-blue-400"></i>
    </div>
    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No products to compare</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-7 max-w-xs mx-auto">Add at least 2 products from the shop to compare their specs side by side.</p>
    <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all shadow-lg shadow-blue-500/20 text-sm">
      <i class="fa-solid fa-arrow-left text-xs"></i> Browse Products
    </a>
  </div>

  <div x-show="!loading && products.length > 0" x-cloak class="overflow-x-auto">
    <table class="w-full border-collapse min-w-[720px]">
      <thead>
        <tr>
          <th class="text-left align-bottom pb-4 pr-4 w-40 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Product</th>
          <template x-for="p in products" :key="p.id">
            <th class="align-bottom pb-4 px-3 min-w-[220px]">
              <div class="relative bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 rounded-2xl p-4">
                <button @click="remove(p.id)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-slate-100 dark:bg-white/10 text-slate-500 hover:bg-red-100 hover:text-red-500 dark:hover:bg-red-500/20 flex items-center justify-center transition-all">
                  <i class="fa-solid fa-xmark text-xs"></i>
                </button>
                <a :href="'/products/' + p.id" class="block">
                  <img :src="p.thumbnail || ''" x-show="p.thumbnail" class="w-full h-32 object-cover rounded-xl mb-3 bg-slate-50 dark:bg-[hsl(222,47%,13%)]">
                  <div x-show="!p.thumbnail" class="w-full h-32 rounded-xl mb-3 bg-slate-50 dark:bg-[hsl(222,47%,13%)] flex items-center justify-center">
                    <i class="fa-solid fa-box text-3xl text-slate-300 dark:text-slate-600"></i>
                  </div>
                  <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-2 leading-snug" x-text="p.name"></p>
                </a>
                <p class="text-base font-bold text-slate-900 dark:text-white mt-2" x-text="'₹' + Number(p.price).toLocaleString('en-IN')"></p>
                <button @click="addToCart(p.id)" :disabled="p.stock <= 0"
                        class="mt-2 w-full py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 dark:disabled:bg-slate-700 disabled:text-slate-400 text-white text-[11px] font-semibold transition-all">
                  <span x-text="p.stock <= 0 ? 'Out of Stock' : 'Add to Cart'"></span>
                </button>
              </div>
            </th>
          </template>
        </tr>
      </thead>
      <tbody>
        <tr class="border-t border-slate-200 dark:border-white/6">
          <td class="py-3 pr-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Brand</td>
          <template x-for="p in products" :key="'brand'+p.id">
            <td class="py-3 px-3 text-sm text-slate-700 dark:text-slate-300" x-text="p.brand_name || '—'"></td>
          </template>
        </tr>
        <tr class="border-t border-slate-200 dark:border-white/6">
          <td class="py-3 pr-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Category</td>
          <template x-for="p in products" :key="'cat'+p.id">
            <td class="py-3 px-3 text-sm text-slate-700 dark:text-slate-300" x-text="p.category_name || '—'"></td>
          </template>
        </tr>
        <tr class="border-t border-slate-200 dark:border-white/6">
          <td class="py-3 pr-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Rating</td>
          <template x-for="p in products" :key="'rating'+p.id">
            <td class="py-3 px-3 text-sm text-slate-700 dark:text-slate-300">
              <span x-show="p.avg_rating > 0"><i class="fa-solid fa-star text-amber-400 text-xs mr-1"></i><span x-text="Number(p.avg_rating).toFixed(1)"></span> <span class="text-slate-400">(<span x-text="p.review_count || 0"></span>)</span></span>
              <span x-show="!p.avg_rating">No ratings yet</span>
            </td>
          </template>
        </tr>
        <tr class="border-t border-slate-200 dark:border-white/6">
          <td class="py-3 pr-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Stock</td>
          <template x-for="p in products" :key="'stock'+p.id">
            <td class="py-3 px-3 text-sm" :class="p.stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500'" x-text="p.stock > 0 ? p.stock + ' in stock' : 'Out of stock'"></td>
          </template>
        </tr>
        <template x-for="key in specKeys" :key="key">
          <tr class="border-t border-slate-200 dark:border-white/6">
            <td class="py-3 pr-4 text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="key"></td>
            <template x-for="p in products" :key="key+p.id">
              <td class="py-3 px-3 text-sm text-slate-700 dark:text-slate-300" x-text="specValue(p, key)"></td>
            </template>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</div>
<script>
function compareView() {
  return {
    products: [],
    loading: true,
    specKeys: [],
    async init() {
      const ids = Alpine.store('compare').ids;
      if (!ids.length) { this.loading = false; return; }
      try {
        const r = await apiFetch('/api/compare?ids=' + ids.join(','));
        this.products = r.data;
        const keys = new Set();
        this.products.forEach(p => {
          if (Array.isArray(p.specifications)) {
            p.specifications.forEach(s => { if (s && s.key) keys.add(s.key); });
          }
        });
        this.specKeys = Array.from(keys);
      } catch (e) {
        showToast(e.message, 'error');
      } finally {
        this.loading = false;
      }
    },
    remove(id) {
      Alpine.store('compare').toggle(id);
      this.products = this.products.filter(p => p.id !== id);
    },
    specValue(p, key) {
      if (!Array.isArray(p.specifications)) return '—';
      const found = p.specifications.find(s => s.key === key);
      return found ? found.value : '—';
    },
    clearAll() {
      Alpine.store('compare').clear();
      this.products = [];
    },
    async addToCart(id) {
      await window.addToCart(id);
    }
  };
}
</script>
