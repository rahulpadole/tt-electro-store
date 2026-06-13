<?php
$pageTitle = 'Products';
$pm = new ProductModel();
$cm = new CategoryModel();
$bm = new BrandModel();
$page = max(1,(int)($_GET['page']??1));
$q = $_GET['q']??'';
$result = $pm->all(['q'=>$q], $page, 20);
$cats = $cm->all(); $brands = $bm->all();
?>
<div x-data="productManager()" x-init="cats=<?= htmlspecialchars(json_encode($cats),ENT_QUOTES) ?>;brands=<?= htmlspecialchars(json_encode($brands),ENT_QUOTES) ?>">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <form action="/admin/products" method="GET" class="flex gap-2">
      <input type="text" name="q" value="<?= clean($q) ?>" placeholder="Search products..."
             class="px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 w-56">
      <button type="submit" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm">Search</button>
    </form>
    <button @click="openAdd()" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">+ Add Product</button>
  </div>

  <!-- Products Table -->
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead><tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
          <th class="px-4 py-3 text-left">Product</th>
          <th class="px-4 py-3 text-left">Category</th>
          <th class="px-4 py-3 text-right">Price</th>
          <th class="px-4 py-3 text-right">Stock</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach($result['items'] as $p): ?>
          <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="product-row-<?= $p['id'] ?>">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <?php if($p['thumbnail']): ?><img src="<?= clean($p['thumbnail']) ?>" class="w-9 h-9 rounded-lg object-cover flex-shrink-0"><?php else: ?><div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-base flex-shrink-0">📦</div><?php endif; ?>
                <div><p class="text-gray-200 font-medium line-clamp-1"><?= clean($p['name']) ?></p>
                <div class="flex gap-1 mt-0.5">
                  <?php if($p['is_featured']): ?><span class="px-1.5 py-0.5 rounded text-xs bg-yellow-500/20 text-yellow-400">Featured</span><?php endif; ?>
                  <?php if($p['is_trending']): ?><span class="px-1.5 py-0.5 rounded text-xs bg-purple-500/20 text-purple-400">Trending</span><?php endif; ?>
                  <?php if($p['is_flash_sale']): ?><span class="px-1.5 py-0.5 rounded text-xs bg-red-500/20 text-red-400">⚡ Sale</span><?php endif; ?>
                </div></div>
              </div>
            </td>
            <td class="px-4 py-3 text-gray-400 text-xs"><?= clean($p['category_name']??'—') ?></td>
            <td class="px-4 py-3 text-right">
              <p class="text-white font-semibold">₹<?= number_format((float)$p['price'],2) ?></p>
              <?php if($p['original_price']&&(float)$p['original_price']>(float)$p['price']): ?><p class="text-gray-500 line-through text-xs">₹<?= number_format((float)$p['original_price'],2) ?></p><?php endif; ?>
            </td>
            <td class="px-4 py-3 text-right"><span class="font-bold <?= $p['stock']<=0?'text-red-400':($p['stock']<=10?'text-yellow-400':'text-green-400') ?>"><?= $p['stock'] ?></span></td>
            <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs <?= $p['is_active']?'bg-green-500/20 text-green-400':'bg-red-500/20 text-red-400' ?>"><?= $p['is_active']?'Active':'Inactive' ?></span></td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="/products/<?= $p['id'] ?>" target="_blank" class="text-gray-400 hover:text-white text-xs px-2 py-1 rounded border border-white/10 hover:bg-white/5 transition-all">View</a>
                <button @click="openEdit(<?= htmlspecialchars(json_encode([
                  'id'           => (int)$p['id'],
                  'name'         => $p['name'],
                  'price'        => $p['price'],
                  'original_price'=> $p['original_price'],
                  'stock'        => (int)$p['stock'],
                  'category_id'  => $p['category_id'],
                  'brand_id'     => $p['brand_id'],
                  'thumbnail'    => $p['thumbnail'],
                  'description'  => $p['description'],
                  'is_featured'  => (bool)$p['is_featured'],
                  'is_trending'  => (bool)$p['is_trending'],
                  'is_best_seller'=> (bool)$p['is_best_seller'],
                ]), ENT_QUOTES) ?>)"
                        class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded border border-blue-500/20 hover:bg-blue-500/10 transition-all">Edit</button>
                <button @click="removeProduct(<?= $p['id'] ?>, $el)"
                        class="text-gray-400 hover:text-red-400 text-xs px-2 py-1 rounded border border-white/10 hover:border-red-500/20 transition-all">Delete</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if($result['total_pages']>1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-white/10">
      <p class="text-xs text-gray-500">Showing <?= (($result['page']-1)*20)+1 ?>–<?= min($result['page']*20,$result['total']) ?> of <?= $result['total'] ?></p>
      <div class="flex gap-2">
        <?php if($result['page']>1): ?><a href="?q=<?= urlencode($q) ?>&page=<?= $result['page']-1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">‹ Prev</a><?php endif; ?>
        <?php if($result['page']<$result['total_pages']): ?><a href="?q=<?= urlencode($q) ?>&page=<?= $result['page']+1 ?>" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 hover:text-white text-xs">Next ›</a><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Add / Edit Modal -->
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false" x-cloak>
    <div class="w-full max-w-xl bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[90vh] overflow-y-auto">
      <h3 class="font-bold text-white text-lg mb-5" x-text="editId ? 'Edit Product' : 'Add New Product'"></h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Name *</label><input type="text" x-model="form.name" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block">Sale Price *</label><input type="number" step="0.01" x-model="form.price" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block">Original Price</label><input type="number" step="0.01" x-model="form.original_price" placeholder="MRP" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block">Stock</label><input type="number" x-model="form.stock" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block">Category *</label>
          <select x-model="form.category_id" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
            <option value="">Select category</option>
            <template x-for="c in cats" :key="c.id"><option :value="c.id" x-text="c.name"></option></template>
          </select></div>
        </div>
        <div><label class="text-xs text-gray-400 mb-1 block">Brand</label>
        <select x-model="form.brand_id" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
          <option value="">No brand</option>
          <template x-for="b in brands" :key="b.id"><option :value="b.id" x-text="b.name"></option></template>
        </select></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Thumbnail URL</label><input type="url" x-model="form.thumbnail" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Description</label><textarea x-model="form.description" rows="3" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <div class="flex gap-4">
          <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer"><input type="checkbox" x-model="form.is_featured" class="accent-blue-500"> Featured</label>
          <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer"><input type="checkbox" x-model="form.is_trending" class="accent-blue-500"> Trending</label>
          <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer"><input type="checkbox" x-model="form.is_best_seller" class="accent-blue-500"> Best Seller</label>
        </div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-5">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5 transition-colors">Cancel</button>
        <button @click="save()" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors disabled:opacity-50">
          <span x-show="!loading" x-text="editId ? 'Save Changes' : 'Save Product'"></span>
          <span x-show="loading">Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function productManager() {
  return {
    showModal: false,
    editId: null,
    loading: false,
    error: '',
    cats: [],
    brands: [],
    form: { name:'', price:'', original_price:'', stock:0, category_id:'', brand_id:'', thumbnail:'', description:'', is_featured:false, is_trending:false, is_best_seller:false },

    openAdd() {
      this.editId = null;
      this.form = { name:'', price:'', original_price:'', stock:0, category_id:'', brand_id:'', thumbnail:'', description:'', is_featured:false, is_trending:false, is_best_seller:false };
      this.error = '';
      this.showModal = true;
    },

    openEdit(p) {
      this.editId = p.id;
      this.form = {
        name: p.name || '',
        price: p.price || '',
        original_price: p.original_price || '',
        stock: p.stock || 0,
        category_id: p.category_id || '',
        brand_id: p.brand_id || '',
        thumbnail: p.thumbnail || '',
        description: p.description || '',
        is_featured: !!p.is_featured,
        is_trending: !!p.is_trending,
        is_best_seller: !!p.is_best_seller,
      };
      this.error = '';
      this.showModal = true;
    },

    async save() {
      if (!this.form.name || !this.form.price || !this.form.category_id) {
        this.error = 'Name, price and category are required'; return;
      }
      this.loading = true; this.error = '';
      try {
        if (this.editId) {
          await apiFetch(`/api/products/${this.editId}`, { method:'PATCH', body: JSON.stringify(this.form) });
          showToast('Product updated!', 'success');
        } else {
          await apiFetch('/api/products', { method:'POST', body: JSON.stringify(this.form) });
          showToast('Product created!', 'success');
        }
        setTimeout(() => location.reload(), 800);
      } catch(e) { this.error = e.message; }
      this.loading = false;
    },

    async removeProduct(id, btn) {
      if (!confirm('Delete this product?')) return;
      try {
        await apiFetch(`/api/products/${id}`, { method:'DELETE' });
        btn.closest('tr').remove();
        showToast('Product deleted', 'success');
      } catch(e) { showToast(e.message, 'error'); }
    }
  };
}
</script>
