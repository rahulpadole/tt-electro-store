<?php
$salePrice    = ($p['is_flash_sale'] && $p['flash_sale_price']) ? (float)$p['flash_sale_price'] : null;
$displayPrice = $salePrice ?? (float)$p['price'];
$origPrice    = (float)($p['original_price'] ?: $p['price']);
$discPct      = ($salePrice && $origPrice > $displayPrice) ? round((1 - $displayPrice/$origPrice)*100) : 0;
?>
<div class="group relative rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-300 dark:hover:border-blue-500/30 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/80 dark:hover:shadow-blue-500/8 flex flex-col">

  <!-- Image area -->
  <a href="/products/<?= $p['id'] ?>" class="block relative overflow-hidden bg-slate-50 dark:bg-[hsl(222,47%,13%)]">
    <?php if($p['thumbnail']): ?>
    <img src="<?= clean($p['thumbnail']) ?>" alt="<?= clean($p['name']) ?>"
         class="w-full h-44 object-cover group-hover:scale-[1.04] transition-transform duration-500">
    <?php else: ?>
    <div class="w-full h-44 flex items-center justify-center text-slate-300 dark:text-slate-600">
      <i class="fa-solid fa-box text-5xl"></i>
    </div>
    <?php endif; ?>

    <!-- Badges -->
    <div class="absolute top-2 left-2 flex flex-col gap-1">
      <?php if($discPct > 0): ?>
      <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-[11px] font-bold leading-tight"><?= $discPct ?>% OFF</span>
      <?php endif; ?>
      <?php if($p['is_flash_sale']): ?>
      <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[11px] font-bold leading-tight flex items-center gap-1"><i class="fa-solid fa-bolt text-[8px]"></i> SALE</span>
      <?php endif; ?>
    </div>

    <!-- Out of stock overlay -->
    <?php if($p['stock'] <= 0): ?>
    <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center backdrop-blur-[1px]">
      <span class="bg-slate-800 text-white text-xs font-semibold px-3 py-1.5 rounded-full">Out of Stock</span>
    </div>
    <?php endif; ?>
  </a>

  <!-- Info -->
  <div class="p-3.5 flex flex-col flex-1">
    <?php if(!empty($p['brand_name'])): ?>
    <span class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide mb-1"><?= clean($p['brand_name']) ?></span>
    <?php endif; ?>

    <a href="/products/<?= $p['id'] ?>"
       class="text-sm font-medium text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white line-clamp-2 leading-snug flex-1 transition-colors">
      <?= clean($p['name']) ?>
    </a>

    <!-- Rating stars (if available) -->
    <?php if(!empty($p['avg_rating']) && $p['avg_rating'] > 0): ?>
    <div class="flex items-center gap-1 mt-1.5">
      <?php for($s=1;$s<=5;$s++): ?>
      <svg class="w-3 h-3 <?= $s<=$p['avg_rating']?'text-amber-400':'text-slate-200 dark:text-slate-700' ?>" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
      </svg>
      <?php endfor; ?>
      <span class="text-[11px] text-slate-400 dark:text-slate-500 ml-0.5">(<?= (int)($p['review_count'] ?? 0) ?>)</span>
    </div>
    <?php endif; ?>

    <!-- Price row -->
    <div class="mt-2.5 flex items-center justify-between gap-2">
      <div class="flex items-baseline gap-1.5 flex-wrap">
        <span class="text-base font-bold text-slate-900 dark:text-white">₹<?= number_format($displayPrice, 0) ?></span>
        <?php
        $showOrig = false;
        $origShow = 0;
        if($salePrice && (float)$p['price'] > $displayPrice) { $showOrig = true; $origShow = (float)$p['price']; }
        elseif($p['original_price'] && (float)$p['original_price'] > (float)$p['price']) { $showOrig = true; $origShow = (float)$p['original_price']; }
        if($showOrig): ?>
        <span class="text-xs text-slate-400 dark:text-slate-500 line-through font-normal">₹<?= number_format($origShow, 0) ?></span>
        <?php endif; ?>
      </div>
      <!-- Wishlist button -->
      <button onclick="addToWishlist(<?= $p['id'] ?>)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all flex-shrink-0"
              title="Add to Wishlist">
        <i class="fa-regular fa-heart text-sm"></i>
      </button>
    </div>

    <!-- Add to Cart -->
    <button onclick="addToCart(<?= $p['id'] ?>)"
            <?= $p['stock'] <= 0 ? 'disabled' : '' ?>
            class="mt-2.5 w-full py-2 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 dark:disabled:bg-slate-700 disabled:text-slate-400 dark:disabled:text-slate-500 disabled:cursor-not-allowed text-white text-xs font-semibold transition-all active:scale-[.97]">
      <?php if($p['stock'] <= 0): ?>
        Out of Stock
      <?php else: ?>
        <i class="fa-solid fa-cart-plus text-xs mr-1"></i> Add to Cart
      <?php endif; ?>
    </button>
  </div>
</div>
