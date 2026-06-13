<?php
$pageTitle = 'Wishlist';
$wm   = new WishlistModel();
$items = $wm->getForUser(getCurrentUserId());
?>
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="mb-7">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
      My Wishlist
      <span class="text-slate-400 dark:text-slate-500 text-lg font-normal ml-2">(<?= count($items) ?> item<?= count($items)!==1?'s':'' ?>)</span>
    </h1>
  </div>

  <?php if(empty($items)): ?>
  <div class="text-center py-24 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
    <div class="w-20 h-20 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center mx-auto mb-5">
      <i class="fa-regular fa-heart text-3xl text-rose-400"></i>
    </div>
    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">Your wishlist is empty</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-7 max-w-xs mx-auto">Save products you love to find them later.</p>
    <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all shadow-lg shadow-blue-500/20 text-sm">
      <i class="fa-solid fa-arrow-left text-xs"></i> Browse Products
    </a>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    <?php foreach($items as $item):
      $itemPrice = (float)(($item['is_flash_sale']&&$item['flash_sale_price'])?$item['flash_sale_price']:$item['price']);
    ?>
    <div class="group relative rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-rose-300 dark:hover:border-rose-500/30 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-100 dark:hover:shadow-rose-500/8 flex flex-col">
      <a href="/products/<?= $item['product_id'] ?>" class="block relative overflow-hidden bg-slate-50 dark:bg-[hsl(222,47%,13%)]">
        <?php if($item['thumbnail']): ?>
        <img src="<?= clean($item['thumbnail']) ?>" alt="<?= clean($item['name']) ?>"
             class="w-full h-44 object-cover group-hover:scale-[1.04] transition-transform duration-500">
        <?php else: ?>
        <div class="w-full h-44 flex items-center justify-center">
          <i class="fa-solid fa-box text-5xl text-slate-300 dark:text-slate-600"></i>
        </div>
        <?php endif; ?>
      </a>
      <div class="p-3.5 flex flex-col flex-1">
        <a href="/products/<?= $item['product_id'] ?>"
           class="text-sm font-medium text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white line-clamp-2 flex-1 transition-colors leading-snug">
          <?= clean($item['name']) ?>
        </a>
        <div class="mt-2.5 flex items-baseline gap-1.5 flex-wrap">
          <span class="text-base font-bold text-slate-900 dark:text-white">₹<?= number_format($itemPrice,0) ?></span>
          <?php if($item['original_price']&&(float)$item['original_price']>(float)$item['price']): ?>
          <span class="text-xs text-slate-400 dark:text-slate-500 line-through">₹<?= number_format((float)$item['original_price'],0) ?></span>
          <?php endif; ?>
        </div>
        <div class="flex gap-2 mt-2.5">
          <button onclick="addToCart(<?= $item['product_id'] ?>)"
                  class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all flex items-center justify-center gap-1">
            <i class="fa-solid fa-cart-plus text-[10px]"></i> Add to Cart
          </button>
          <button onclick="removeWishlist(<?= $item['product_id'] ?>,this)"
                  title="Remove from wishlist"
                  class="p-2 rounded-xl border border-slate-200 dark:border-white/8 hover:border-red-300 dark:hover:border-red-500/30 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition-all">
            <i class="fa-solid fa-trash-can text-xs"></i>
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<script>
async function removeWishlist(productId, btn) {
  try {
    await apiFetch(`/api/wishlist/${productId}`, { method: 'DELETE' });
    btn.closest('.group').remove();
    showToast('Removed from wishlist', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}
</script>
