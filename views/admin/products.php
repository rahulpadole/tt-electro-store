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
                  'id'            => (int)$p['id'],
                  'name'          => $p['name'],
                  'price'         => $p['price'],
                  'original_price'=> $p['original_price'],
                  'stock'         => (int)$p['stock'],
                  'category_id'   => $p['category_id'],
                  'brand_id'      => $p['brand_id'],
                  'thumbnail'     => $p['thumbnail'],
                  'description'   => $p['description'],
                  'is_featured'   => (bool)$p['is_featured'],
                  'is_trending'   => (bool)$p['is_trending'],
                  'is_best_seller'=> (bool)$p['is_best_seller'],
                  'is_active'     => (bool)$p['is_active'],
                  'images'        => is_array($p['images']??null) ? $p['images'] : (is_string($p['images']??null) ? json_decode($p['images'],true) : []),
                  'tags'          => $p['tags'] ?? '',
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
    <div class="w-full max-w-2xl bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6 max-h-[92vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-white text-lg" x-text="editId ? 'Edit Product' : 'Add New Product'"></h3>
        <button @click="showModal=false" class="text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="space-y-4">

        <!-- Name -->
        <div><label class="text-xs text-gray-400 mb-1 block font-medium">Product Name *</label>
          <input type="text" x-model="form.name" placeholder="e.g. Samsung Galaxy S24 Ultra"
                 class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 placeholder-gray-600"></div>

        <!-- Pricing -->
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block font-medium">Sale Price (₹) *</label>
            <input type="number" step="0.01" x-model="form.price"
                   class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block font-medium">MRP / Original Price (₹)</label>
            <input type="number" step="0.01" x-model="form.original_price" placeholder="0"
                   class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        </div>

        <!-- Stock + Category -->
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 mb-1 block font-medium">Stock Quantity</label>
            <input type="number" x-model="form.stock"
                   class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
          <div><label class="text-xs text-gray-400 mb-1 block font-medium">Category *</label>
            <select x-model="form.category_id" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
              <option value="">Select category</option>
              <template x-for="c in cats" :key="c.id"><option :value="c.id" x-text="c.name"></option></template>
            </select></div>
        </div>

        <!-- Brand -->
        <div><label class="text-xs text-gray-400 mb-1 block font-medium">Brand</label>
          <select x-model="form.brand_id" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500">
            <option value="">No brand</option>
            <template x-for="b in brands" :key="b.id"><option :value="b.id" x-text="b.name"></option></template>
          </select></div>

        <!-- Thumbnail -->
        <div x-data="imageUploader('thumbnail')" @upload-done.window="if($event.detail.field==='thumbnail') form.thumbnail=$event.detail.url">
          <label class="text-xs text-gray-400 mb-1.5 block font-medium">Thumbnail Image</label>

          <!-- Tab switcher -->
          <div class="flex gap-1 mb-2 bg-[hsl(222,47%,15%)] rounded-lg p-1 w-fit">
            <button type="button" @click="mode='url'" :class="mode==='url'?'bg-[hsl(222,47%,10%)] text-white shadow':'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all">Paste URL</button>
            <button type="button" @click="mode='upload'" :class="mode==='upload'?'bg-[hsl(222,47%,10%)] text-white shadow':'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all">Upload File</button>
          </div>

          <!-- URL mode -->
          <div x-show="mode==='url'">
            <input type="url" x-model="form.thumbnail" placeholder="https://example.com/image.jpg"
                   class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 placeholder-gray-600">
          </div>

          <!-- Upload mode -->
          <div x-show="mode==='upload'">
            <label class="flex flex-col items-center gap-2 w-full py-5 rounded-lg border-2 border-dashed cursor-pointer transition-all"
                   :class="dragging?'border-blue-400 bg-blue-500/8':'border-white/10 hover:border-white/20'"
                   @dragover.prevent="dragging=true" @dragleave="dragging=false"
                   @drop.prevent="dragging=false; handleDrop($event, 'thumbnail')">
              <input type="file" accept="image/*" class="hidden" @change="uploadFile($event.target.files[0], 'thumbnail')">
              <template x-if="!uploading">
                <div class="flex flex-col items-center gap-1.5 pointer-events-none">
                  <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-500"></i>
                  <span class="text-xs text-gray-500">Drop image here or <span class="text-blue-400">click to browse</span></span>
                  <span class="text-[10px] text-gray-600">JPEG, PNG, WebP, GIF — max 10 MB</span>
                </div>
              </template>
              <template x-if="uploading">
                <div class="flex items-center gap-2 pointer-events-none">
                  <span class="w-4 h-4 border-2 border-white/20 border-t-blue-400 rounded-full animate-spin"></span>
                  <span class="text-xs text-gray-400">Uploading...</span>
                </div>
              </template>
            </label>
          </div>

          <!-- Preview -->
          <div x-show="form.thumbnail" class="mt-2 flex items-center gap-3">
            <img :src="form.thumbnail" class="w-14 h-14 rounded-lg object-cover border border-white/10 flex-shrink-0" @error="$el.style.display='none'">
            <div class="flex-1 min-w-0">
              <p class="text-xs text-green-400 flex items-center gap-1"><i class="fa-solid fa-circle-check text-[10px]"></i> Thumbnail set</p>
              <p class="text-[10px] text-gray-600 truncate" x-text="form.thumbnail"></p>
            </div>
            <button type="button" @click="form.thumbnail=''" class="text-gray-600 hover:text-red-400 transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
          </div>
        </div>

        <!-- Gallery Images -->
        <div x-data="galleryUploader()" @upload-done.window="if($event.detail.field==='gallery') addGalleryUrl($event.detail.url)">
          <label class="text-xs text-gray-400 mb-1.5 block font-medium">Gallery Images</label>

          <!-- Tab switcher -->
          <div class="flex gap-1 mb-2 bg-[hsl(222,47%,15%)] rounded-lg p-1 w-fit">
            <button type="button" @click="mode='url'" :class="mode==='url'?'bg-[hsl(222,47%,10%)] text-white shadow':'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all">Paste URLs</button>
            <button type="button" @click="mode='upload'" :class="mode==='upload'?'bg-[hsl(222,47%,10%)] text-white shadow':'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition-all">Upload Files</button>
          </div>

          <!-- URL mode -->
          <div x-show="mode==='url'">
            <textarea x-model="form.imagesText" rows="3"
                      placeholder="https://example.com/img1.jpg&#10;https://example.com/img2.jpg&#10;https://example.com/img3.jpg"
                      class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none placeholder-gray-600 font-mono text-xs leading-relaxed"></textarea>
          </div>

          <!-- Upload mode -->
          <div x-show="mode==='upload'">
            <label class="flex flex-col items-center gap-2 w-full py-5 rounded-lg border-2 border-dashed cursor-pointer transition-all"
                   :class="dragging?'border-blue-400 bg-blue-500/8':'border-white/10 hover:border-white/20'"
                   @dragover.prevent="dragging=true" @dragleave="dragging=false"
                   @drop.prevent="dragging=false; handleDropMulti($event)">
              <input type="file" accept="image/*" multiple class="hidden" @change="uploadMultiple($event.target.files)">
              <template x-if="!uploading">
                <div class="flex flex-col items-center gap-1.5 pointer-events-none">
                  <i class="fa-solid fa-images text-2xl text-gray-500"></i>
                  <span class="text-xs text-gray-500">Drop images here or <span class="text-blue-400">click to browse</span></span>
                  <span class="text-[10px] text-gray-600">Select multiple files — each max 10 MB</span>
                </div>
              </template>
              <template x-if="uploading">
                <div class="flex items-center gap-2 pointer-events-none">
                  <span class="w-4 h-4 border-2 border-white/20 border-t-blue-400 rounded-full animate-spin"></span>
                  <span class="text-xs text-gray-400" x-text="uploadStatus"></span>
                </div>
              </template>
            </label>
          </div>

          <!-- Gallery preview grid -->
          <template x-if="galleryPreviews().length > 0">
            <div class="mt-2 flex flex-wrap gap-2">
              <template x-for="(url, i) in galleryPreviews()" :key="url">
                <div class="relative group">
                  <img :src="url" class="w-16 h-16 rounded-lg object-cover border border-white/10" @error="$el.style.opacity='0.3'">
                  <button type="button" @click="removeGalleryUrl(i)"
                          class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-red-600 text-white text-[9px] items-center justify-center hidden group-hover:flex">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </div>
              </template>
            </div>
          </template>
          <p class="text-gray-600 text-xs mt-1.5">Gallery images appear in the product slideshow. Thumbnail is always shown first.</p>
        </div>

        <!-- Tags -->
        <div><label class="text-xs text-gray-400 mb-1 block font-medium">Tags <span class="text-gray-600 font-normal">(comma-separated)</span></label>
          <input type="text" x-model="form.tags" placeholder="e.g. wireless, bluetooth, premium"
                 class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 placeholder-gray-600"></div>

        <!-- Description -->
        <div><label class="text-xs text-gray-400 mb-1 block font-medium">Description</label>
          <textarea x-model="form.description" rows="4"
                    class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>

        <!-- Flags grid -->
        <div>
          <label class="text-xs text-gray-400 mb-2 block font-medium">Visibility & Labels</label>
          <div class="grid grid-cols-3 gap-2">
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-green-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_active" class="accent-green-500 rounded">
              <span class="text-sm text-gray-300">Active</span>
            </label>
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-yellow-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_featured" class="accent-yellow-500 rounded">
              <span class="text-sm text-gray-300">Featured</span>
            </label>
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-purple-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_trending" class="accent-purple-500 rounded">
              <span class="text-sm text-gray-300">Trending</span>
            </label>
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-orange-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_best_seller" class="accent-orange-500 rounded">
              <span class="text-sm text-gray-300">Best Seller</span>
            </label>
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-blue-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_new_arrival" class="accent-blue-500 rounded">
              <span class="text-sm text-gray-300">New Arrival</span>
            </label>
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-[hsl(222,47%,13%)] border border-white/8 cursor-pointer hover:border-red-500/30 transition-colors">
              <input type="checkbox" x-model="form.is_offer" class="accent-red-500 rounded">
              <span class="text-sm text-gray-300">On Offer</span>
            </label>
          </div>
        </div>

        <p x-show="error" x-text="error" class="text-red-400 text-xs bg-red-500/10 border border-red-500/20 rounded-lg px-3 py-2"></p>
      </div>
      <div class="flex gap-3 mt-5">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5 transition-colors">Cancel</button>
        <button @click="save()" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
          <span x-show="!loading" x-text="editId ? 'Save Changes' : 'Add Product'"></span>
          <span x-show="loading" class="flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span> Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
/* ── Single-image uploader (thumbnail) ──────────────────── */
function imageUploader(field) {
  return {
    mode: 'url',
    uploading: false,
    dragging: false,
    async uploadFile(file, field) {
      if (!file) return;
      this.uploading = true;
      try {
        const fd = new FormData();
        fd.append('image', file);
        const res = await fetch('/api/upload/image', { method: 'POST', body: fd });
        const r   = await res.json();
        if (!r.success) throw new Error(r.message || 'Upload failed');
        window.dispatchEvent(new CustomEvent('upload-done', { detail: { field, url: r.data.url } }));
        showToast('Image uploaded!', 'success');
        this.mode = 'url';
      } catch(e) { showToast(e.message, 'error'); }
      this.uploading = false;
    },
    handleDrop(e, field) {
      const file = e.dataTransfer.files?.[0];
      if (file) this.uploadFile(file, field);
    }
  };
}

/* ── Multi-image uploader (gallery) ─────────────────────── */
function galleryUploader() {
  return {
    mode: 'url',
    uploading: false,
    dragging: false,
    uploadStatus: '',
    galleryPreviews() {
      return (this.form.imagesText || '').split('\n').map(u => u.trim()).filter(Boolean);
    },
    addGalleryUrl(url) {
      const lines = this.galleryPreviews();
      if (!lines.includes(url)) {
        this.form.imagesText = [...lines, url].join('\n');
      }
    },
    removeGalleryUrl(i) {
      const lines = this.galleryPreviews();
      lines.splice(i, 1);
      this.form.imagesText = lines.join('\n');
    },
    async uploadMultiple(files) {
      if (!files || !files.length) return;
      this.uploading = true;
      let done = 0, total = files.length;
      for (const file of files) {
        this.uploadStatus = `Uploading ${done+1} of ${total}…`;
        try {
          const fd = new FormData();
          fd.append('image', file);
          const res = await fetch('/api/upload/image', { method: 'POST', body: fd });
          const r   = await res.json();
          if (!r.success) { showToast(r.message || 'Upload failed', 'error'); continue; }
          this.addGalleryUrl(r.data.url);
        } catch(e) { showToast(e.message, 'error'); }
        done++;
      }
      this.uploading = false;
      showToast(`${done} image(s) uploaded!`, 'success');
    },
    handleDropMulti(e) { this.uploadMultiple(e.dataTransfer.files); }
  };
}

/* ── Product manager ─────────────────────────────────────── */
function productManager() {
  const blank = () => ({
    name:'', price:'', original_price:'', stock:0,
    category_id:'', brand_id:'', thumbnail:'', description:'',
    is_active:true, is_featured:false, is_trending:false,
    is_best_seller:false, is_new_arrival:false, is_offer:false,
    imagesText:'', tags:''
  });
  return {
    showModal: false,
    editId: null,
    loading: false,
    error: '',
    cats: [],
    brands: [],
    form: blank(),

    openAdd() {
      this.editId = null;
      this.form = blank();
      this.error = '';
      this.showModal = true;
    },

    openEdit(p) {
      this.editId = p.id;
      const imgs = Array.isArray(p.images) ? p.images : [];
      this.form = {
        name:           p.name || '',
        price:          p.price || '',
        original_price: p.original_price || '',
        stock:          p.stock || 0,
        category_id:    p.category_id || '',
        brand_id:       p.brand_id || '',
        thumbnail:      p.thumbnail || '',
        description:    p.description || '',
        is_active:      p.is_active !== undefined ? !!p.is_active : true,
        is_featured:    !!p.is_featured,
        is_trending:    !!p.is_trending,
        is_best_seller: !!p.is_best_seller,
        is_new_arrival: !!p.is_new_arrival,
        is_offer:       !!p.is_offer,
        imagesText:     imgs.join('\n'),
        tags:           p.tags || '',
      };
      this.error = '';
      this.showModal = true;
    },

    buildPayload() {
      const p = { ...this.form };
      p.images = p.imagesText.split('\n').map(u => u.trim()).filter(Boolean);
      delete p.imagesText;
      return p;
    },

    async save() {
      if (!this.form.name || !this.form.price || !this.form.category_id) {
        this.error = 'Name, price and category are required'; return;
      }
      this.loading = true; this.error = '';
      try {
        const payload = this.buildPayload();
        if (this.editId) {
          await apiFetch(`/api/products/${this.editId}`, { method:'PATCH', body: JSON.stringify(payload) });
          showToast('Product updated!', 'success');
        } else {
          await apiFetch('/api/products', { method:'POST', body: JSON.stringify(payload) });
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
