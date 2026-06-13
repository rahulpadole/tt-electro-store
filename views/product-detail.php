<?php
$pm = new ProductModel();
$rm = new ReviewModel();
$product = $pm->findById($productId);
if(!$product){ http_response_code(404); include __DIR__.'/not-found.php'; return; }
$reviews    = $rm->forProduct($productId);
$avgRating  = $rm->avgRating($productId);
$related    = $pm->all(['category_id'=>$product['category_id']],1,6);
$related['items'] = array_filter($related['items'],fn($r)=>$r['id']!=$productId);
$pageTitle  = $product['name'];
$pageDesc   = $product['description']??$product['name'];
$images     = is_array($product['images']) ? $product['images'] : [];
if($product['thumbnail'] && !in_array($product['thumbnail'],$images)) array_unshift($images,$product['thumbnail']);
if(empty($images)) $images = [''];
$salePrice    = ($product['is_flash_sale'] && $product['flash_sale_price']) ? (float)$product['flash_sale_price'] : null;
$displayPrice = $salePrice ?? (float)$product['price'];
$specs        = is_array($product['specifications']) ? $product['specifications'] : [];
?>
<div class="max-w-7xl mx-auto px-4 py-8" x-data="productDetail()">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-1.5 text-sm text-slate-500 dark:text-slate-500 mb-7 flex-wrap">
    <a href="/" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Home</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 dark:text-slate-700"></i>
    <a href="/products" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Products</a>
    <?php if($product['category_name']): ?>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 dark:text-slate-700"></i>
    <a href="/products?category_id=<?= $product['category_id'] ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors"><?= clean($product['category_name']) ?></a>
    <?php endif; ?>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 dark:text-slate-700"></i>
    <span class="text-slate-700 dark:text-slate-300 truncate max-w-[200px]"><?= clean($product['name']) ?></span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">

    <!-- ── Image Gallery ──────────────────────────────────── -->
    <div x-data="{current:0, images:<?= htmlspecialchars(json_encode($images),ENT_QUOTES) ?>}">
      <!-- Main image -->
      <div class="rounded-2xl overflow-hidden bg-slate-50 dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 mb-3 aspect-square flex items-center justify-center shadow-sm">
        <template x-if="images[current]">
          <img :src="images[current]" alt="<?= clean($product['name']) ?>" class="w-full h-full object-cover gallery-img">
        </template>
        <template x-if="!images[current]">
          <div class="flex flex-col items-center gap-3 text-slate-300 dark:text-slate-600">
            <i class="fa-solid fa-box text-7xl"></i>
            <span class="text-sm">No image available</span>
          </div>
        </template>
      </div>
      <!-- Thumbnails -->
      <?php if(count($images) > 1): ?>
      <div class="flex gap-2.5 overflow-x-auto pb-1">
        <?php foreach($images as $i => $img): ?>
        <button @click="current=<?= $i ?>"
                :class="current===<?= $i ?>?'border-blue-500 dark:border-blue-400 shadow-md shadow-blue-500/20':'border-slate-200 dark:border-white/10 hover:border-blue-400 dark:hover:border-blue-500/50'"
                class="flex-shrink-0 w-16 h-16 rounded-xl border-2 overflow-hidden transition-all">
          <?php if($img): ?>
          <img src="<?= clean($img) ?>" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full bg-slate-100 dark:bg-[hsl(222,47%,13%)] flex items-center justify-center">
            <i class="fa-solid fa-box text-slate-300 dark:text-slate-600 text-xl"></i>
          </div>
          <?php endif; ?>
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- ── Product Info ───────────────────────────────────── -->
    <div class="space-y-5">
      <!-- Brand -->
      <?php if($product['brand_name']): ?>
      <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest"><?= clean($product['brand_name']) ?></span>
      <?php endif; ?>

      <!-- Title -->
      <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white leading-tight"><?= clean($product['name']) ?></h1>

      <!-- Rating -->
      <div class="flex items-center gap-3">
        <div class="flex gap-0.5">
          <?php for($s=1;$s<=5;$s++): ?>
          <svg class="w-4 h-4 <?= $s<=$avgRating?'text-amber-400':'text-slate-200 dark:text-slate-700' ?>" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <?php endfor; ?>
        </div>
        <span class="text-slate-500 dark:text-slate-400 text-sm"><?= $avgRating ?>/5 · <?= count($reviews) ?> reviews</span>
      </div>

      <!-- Price -->
      <div class="flex items-end gap-3 flex-wrap">
        <span class="text-3xl font-extrabold text-slate-900 dark:text-white">₹<?= number_format($displayPrice,0) ?></span>
        <?php if($salePrice && (float)$product['price'] > $displayPrice): ?>
        <span class="text-lg text-slate-400 dark:text-slate-500 line-through font-normal">₹<?= number_format((float)$product['price'],0) ?></span>
        <span class="chip chip-red"><?= round((1-$displayPrice/(float)$product['price'])*100) ?>% OFF</span>
        <?php elseif($product['original_price'] && (float)$product['original_price'] > (float)$product['price']): ?>
        <span class="text-lg text-slate-400 dark:text-slate-500 line-through font-normal">₹<?= number_format((float)$product['original_price'],0) ?></span>
        <span class="chip chip-green"><?= round((1-(float)$product['price']/(float)$product['original_price'])*100) ?>% OFF</span>
        <?php endif; ?>
      </div>

      <!-- Stock indicator -->
      <?php $stock=(int)$product['stock']; ?>
      <div>
        <?php if($stock > 10): ?>
        <span class="inline-flex items-center gap-1.5 text-sm text-green-600 dark:text-green-400 font-medium">
          <span class="w-2 h-2 rounded-full bg-green-500 inline-block animate-pulse"></span>
          In Stock (<?= $stock ?> units available)
        </span>
        <?php elseif($stock > 0): ?>
        <span class="inline-flex items-center gap-1.5 text-sm text-amber-600 dark:text-amber-400 font-medium">
          <span class="w-2 h-2 rounded-full bg-amber-500 inline-block animate-pulse"></span>
          Low Stock — Only <?= $stock ?> left!
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1.5 text-sm text-red-500 font-medium">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          Out of Stock
        </span>
        <?php endif; ?>
      </div>

      <!-- Description -->
      <?php if($product['description']): ?>
      <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm border-t border-slate-100 dark:border-white/5 pt-5"><?= nl2br(clean($product['description'])) ?></p>
      <?php endif; ?>

      <!-- Tags -->
      <?php if(!empty($product['tags'])): ?>
      <div class="flex flex-wrap gap-2">
        <?php foreach($product['tags'] as $tag): ?>
        <span class="chip chip-blue"><?= clean($tag) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Quantity & Cart -->
      <div class="flex items-center gap-3 pt-2">
        <div class="flex items-center rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,12%)] border border-slate-200 dark:border-white/8">
          <button @click="qty=Math.max(1,qty-1)" class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white text-lg font-bold rounded-l-xl transition-colors">−</button>
          <span x-text="qty" class="w-10 text-center text-slate-900 dark:text-white font-semibold text-sm select-none"></span>
          <button @click="qty=Math.min(<?= max(1,$stock) ?>,qty+1)" class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white text-lg font-bold rounded-r-xl transition-colors">+</button>
        </div>
        <button @click="addToCartQty(<?= $product['id'] ?>,qty)" <?= $stock<=0?'disabled':'' ?>
                class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 dark:disabled:bg-slate-700 disabled:text-slate-400 dark:disabled:text-slate-500 text-white font-semibold text-sm transition-all hover:scale-[1.01] disabled:cursor-not-allowed shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
          <i class="fa-solid fa-cart-plus text-sm"></i>
          <?= $stock<=0 ? 'Out of Stock' : 'Add to Cart' ?>
        </button>
        <button onclick="addToWishlist(<?= $product['id'] ?>)"
                class="w-11 h-11 rounded-xl border border-slate-200 dark:border-white/8 hover:border-rose-400 dark:hover:border-rose-500/40 bg-white dark:bg-transparent text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all flex items-center justify-center flex-shrink-0"
                title="Add to Wishlist">
          <i class="fa-regular fa-heart text-base"></i>
        </button>
      </div>

      <!-- Delivery info chips -->
      <div class="grid grid-cols-3 gap-3 pt-2">
        <?php foreach([
          ['fa-truck-fast','Fast Delivery','5–7 days','blue'],
          ['fa-shield-check','Genuine','100% authentic','green'],
          ['fa-rotate-left','Returns','1-day policy','amber'],
        ] as [$icon,$t,$s,$c]): ?>
        <div class="text-center p-3 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/5">
          <i class="fa-solid fa-<?= $icon ?> text-<?= $c ?>-500 dark:text-<?= $c ?>-400 text-base mb-1.5 block"></i>
          <div class="text-xs font-semibold text-slate-700 dark:text-slate-300"><?= $t ?></div>
          <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5"><?= $s ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ── Specifications ────────────────────────────────────── -->
  <?php if(!empty($specs)): ?>
  <div class="mt-14">
    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
      <i class="fa-solid fa-list-ul text-blue-600 dark:text-blue-400 text-base"></i>
      Specifications
    </h2>
    <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 overflow-hidden shadow-sm">
      <?php $i=0; foreach((is_array($specs)?$specs:[]) as $k=>$v): ?>
      <div class="flex <?= $i%2==0?'bg-slate-50/60 dark:bg-white/[0.02]':'' ?> border-b border-slate-100 dark:border-white/5 last:border-0">
        <div class="w-2/5 lg:w-1/3 px-5 py-3.5 text-sm text-slate-500 dark:text-slate-400 font-medium border-r border-slate-100 dark:border-white/5"><?= clean(is_string($k)?$k:'') ?></div>
        <div class="flex-1 px-5 py-3.5 text-sm text-slate-800 dark:text-slate-200"><?= clean(is_string($v)?$v:json_encode($v)) ?></div>
      </div>
      <?php $i++; endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Reviews ───────────────────────────────────────────── -->
  <div class="mt-14">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i class="fa-solid fa-star text-amber-400 text-base"></i>
        Customer Reviews <span class="text-slate-400 dark:text-slate-500 font-normal text-lg">(<?= count($reviews) ?>)</span>
      </h2>
      <?php if(isLoggedIn()): ?>
      <button @click="reviewOpen=!reviewOpen"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
        <i class="fa-solid fa-pen text-xs"></i> Write a Review
      </button>
      <?php endif; ?>
    </div>

    <!-- Write Review Form -->
    <?php if(isLoggedIn()): ?>
    <div x-show="reviewOpen" x-transition class="mb-7 p-6 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-sm">
      <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Your Review</h3>
      <div x-data="reviewForm(<?= $product['id'] ?>)">
        <div class="flex gap-1 mb-4">
          <?php for($s=1;$s<=5;$s++): ?>
          <button @click="rating=<?= $s ?>"
                  :class="rating>=<?= $s ?>?'text-amber-400':'text-slate-200 dark:text-slate-700'"
                  class="text-3xl transition-colors hover:text-amber-400">★</button>
          <?php endfor; ?>
        </div>
        <input type="text" x-model="title" placeholder="Review headline"
               class="input-base w-full px-4 py-2.5 text-sm mb-3">
        <textarea x-model="body" rows="4" placeholder="Share your experience with this product..."
                  class="input-base w-full px-4 py-2.5 text-sm mb-4 resize-none"></textarea>
        <button @click="submit()" :disabled="loading"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors disabled:opacity-50">
          <span x-show="!loading">Submit Review</span>
          <span x-show="loading" class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            Submitting...
          </span>
        </button>
      </div>
    </div>
    <?php endif; ?>

    <!-- Reviews List -->
    <?php if(empty($reviews)): ?>
    <div class="text-center py-14 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
      <i class="fa-regular fa-comment-dots text-4xl text-slate-300 dark:text-slate-600 mb-3 block"></i>
      <p class="text-slate-500 dark:text-slate-400 text-sm">No reviews yet. Be the first to review this product!</p>
    </div>
    <?php else: ?>
    <div class="space-y-4">
      <?php foreach($reviews as $r): ?>
      <div class="p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
              <?= strtoupper(substr($r['user_name']??'U',0,1)) ?>
            </div>
            <div>
              <p class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= clean($r['user_name']??'User') ?></p>
              <p class="text-xs text-slate-400 dark:text-slate-500"><?= date('M j, Y',strtotime($r['created_at'])) ?></p>
            </div>
          </div>
          <div class="flex gap-0.5">
            <?php for($s=1;$s<=5;$s++): ?>
            <svg class="w-3.5 h-3.5 <?= $s<=(int)$r['rating']?'text-amber-400':'text-slate-200 dark:text-slate-700' ?>" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <?php endfor; ?>
          </div>
        </div>
        <?php if($r['title']): ?><p class="font-semibold text-slate-800 dark:text-slate-200 text-sm mt-3"><?= clean($r['title']) ?></p><?php endif; ?>
        <?php if($r['body']): ?><p class="text-slate-600 dark:text-slate-400 text-sm mt-1.5 leading-relaxed"><?= clean($r['body']) ?></p><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Related Products ─────────────────────────────────── -->
  <?php if(!empty($related['items'])): ?>
  <div class="mt-14">
    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
      <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400 text-base"></i>
      Related Products
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
      <?php foreach(array_slice($related['items'],0,6) as $p): include __DIR__ . '/partials/product-card.php'; endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function productDetail() {
  return {
    qty: 1,
    reviewOpen: false,
    async addToCartQty(id, qty) {
      try {
        await apiFetch('/api/cart', { method: 'POST', body: JSON.stringify({ product_id: id, quantity: qty }) });
        showToast('Added to cart!', 'success');
      } catch(e) { showToast(e.message, 'error'); }
    }
  }
}
function reviewForm(productId) {
  return {
    rating: 5, title: '', body: '', loading: false,
    async submit() {
      if(!this.rating) { showToast('Please select a rating', 'error'); return; }
      this.loading = true;
      try {
        await apiFetch(`/api/products/${productId}/reviews`, { method: 'POST', body: JSON.stringify({ rating: this.rating, title: this.title, body: this.body }) });
        showToast('Review submitted!', 'success');
        setTimeout(() => location.reload(), 1000);
      } catch(e) { showToast(e.message, 'error'); }
      this.loading = false;
    }
  }
}
</script>
