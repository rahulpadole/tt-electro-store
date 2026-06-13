<?php
$pageTitle = 'Shopping Cart';
$cartModel = new CartModel();
$items     = $cartModel->getItems(getCurrentUserId());
$subtotal  = array_sum(array_map(fn($i) => (float)(($i['is_flash_sale']&&$i['flash_sale_price'])?$i['flash_sale_price']:$i['price']) * (int)$i['quantity'], $items));
$shipping  = $subtotal >= FREE_SHIPPING_ABOVE ? 0 : SHIPPING_CHARGE;
?>
<div class="max-w-7xl mx-auto px-4 py-8" x-data="cartPage()">

  <!-- Page header -->
  <div class="mb-7">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
      Shopping Cart
      <span class="text-slate-400 dark:text-slate-500 text-lg font-normal ml-2">(<?= count($items) ?> item<?= count($items)!==1?'s':'' ?>)</span>
    </h1>
  </div>

  <?php if(empty($items)): ?>
  <!-- Empty cart -->
  <div class="text-center py-24 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
    <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-5">
      <i class="fa-solid fa-bag-shopping text-3xl text-slate-300 dark:text-slate-600"></i>
    </div>
    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">Your cart is empty</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-7 max-w-xs mx-auto">Looks like you haven't added anything yet. Let's fix that!</p>
    <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all shadow-lg shadow-blue-500/20">
      <i class="fa-solid fa-arrow-left text-xs"></i> Browse Products
    </a>
  </div>

  <?php else: ?>
  <div class="flex flex-col lg:flex-row gap-6">

    <!-- Cart Items -->
    <div class="flex-1 space-y-3">
      <?php foreach($items as $item):
        $price = (float)(($item['is_flash_sale']&&$item['flash_sale_price'])?$item['flash_sale_price']:$item['price']);
      ?>
      <div class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:shadow-md dark:hover:shadow-none transition-all" id="cart-item-<?= $item['id'] ?>">
        <!-- Image -->
        <a href="/products/<?= $item['product_id'] ?>" class="flex-shrink-0">
          <?php if($item['thumbnail']): ?>
          <img src="<?= clean($item['thumbnail']) ?>" alt="<?= clean($item['name']) ?>"
               class="w-20 h-20 rounded-xl object-cover bg-slate-100 dark:bg-[hsl(222,47%,14%)]">
          <?php else: ?>
          <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,14%)] flex items-center justify-center">
            <i class="fa-solid fa-box text-2xl text-slate-300 dark:text-slate-600"></i>
          </div>
          <?php endif; ?>
        </a>

        <!-- Info -->
        <div class="flex-1 min-w-0">
          <a href="/products/<?= $item['product_id'] ?>"
             class="font-medium text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 text-sm line-clamp-2 transition-colors"><?= clean($item['name']) ?></a>
          <p class="text-blue-600 dark:text-blue-400 font-bold text-sm mt-1">₹<?= number_format($price,0) ?></p>
          <?php if($item['is_flash_sale']&&$item['flash_sale_price']): ?>
          <span class="text-xs text-amber-500 dark:text-amber-400 flex items-center gap-1 mt-0.5">
            <i class="fa-solid fa-bolt text-[9px]"></i> Flash sale price
          </span>
          <?php endif; ?>
        </div>

        <!-- Qty control -->
        <div class="flex items-center rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8">
          <button onclick="updateQty(<?= $item['id'] ?>, <?= max(1,(int)$item['quantity']-1) ?>)"
                  class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-l-xl text-base font-bold transition-colors">−</button>
          <span id="qty-<?= $item['id'] ?>" class="w-8 text-center text-slate-900 dark:text-white text-sm font-semibold select-none"><?= $item['quantity'] ?></span>
          <button onclick="updateQty(<?= $item['id'] ?>, <?= (int)$item['quantity']+1 ?>)"
                  class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-r-xl text-base font-bold transition-colors">+</button>
        </div>

        <!-- Line total + remove -->
        <div class="text-right flex-shrink-0 min-w-[90px]">
          <p class="font-bold text-slate-900 dark:text-white">₹<?= number_format($price*(int)$item['quantity'],0) ?></p>
          <button onclick="removeItem(<?= $item['id'] ?>)"
                  class="text-xs text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 mt-1.5 transition-colors flex items-center gap-1 ml-auto">
            <i class="fa-solid fa-trash-can text-[10px]"></i> Remove
          </button>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Continue shopping -->
      <div class="pt-2">
        <a href="/products" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
          <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
        </a>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="lg:w-80 flex-shrink-0">
      <div class="sticky top-20 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-sm p-5 space-y-4">
        <h2 class="font-bold text-slate-900 dark:text-white text-lg">Order Summary</h2>

        <!-- Coupon -->
        <div>
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Coupon Code</p>
          <div class="flex gap-2">
            <input type="text" x-model="couponCode" placeholder="Enter code"
                   class="flex-1 px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 uppercase transition-colors">
            <button @click="applyCoupon()" :disabled="couponLoading"
                    class="px-3.5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors disabled:opacity-50 flex-shrink-0">
              Apply
            </button>
          </div>
          <p x-show="couponMsg" x-text="couponMsg"
             :class="couponValid?'text-green-600 dark:text-green-400':'text-red-600 dark:text-red-400'"
             class="text-xs mt-1.5 flex items-center gap-1"></p>
        </div>

        <!-- Totals -->
        <div class="space-y-2.5 border-t border-slate-100 dark:border-white/8 pt-4">
          <div class="flex justify-between text-sm">
            <span class="text-slate-500 dark:text-slate-400">Subtotal (<?= count($items) ?> items)</span>
            <span class="text-slate-700 dark:text-slate-300" x-text="'₹'+subtotal.toLocaleString('en-IN',{minimumFractionDigits:0})"></span>
          </div>
          <div x-show="discount>0" class="flex justify-between text-sm text-green-600 dark:text-green-400 font-medium">
            <span class="flex items-center gap-1"><i class="fa-solid fa-tag text-xs"></i> Discount</span>
            <span>−₹<span x-text="discount.toFixed(0)"></span></span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1"><i class="fa-solid fa-truck text-xs"></i> Shipping</span>
            <span x-text="shipping===0?'FREE':'₹'+shipping" :class="shipping===0?'text-green-600 dark:text-green-400 font-semibold':'text-slate-700 dark:text-slate-300'"></span>
          </div>
          <?php if($subtotal < FREE_SHIPPING_ABOVE): ?>
          <p class="text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-3 py-2 rounded-lg">
            <i class="fa-solid fa-info-circle mr-1"></i>
            Add ₹<?= number_format(FREE_SHIPPING_ABOVE - $subtotal, 0) ?> more for free shipping
          </p>
          <?php endif; ?>
          <div class="flex justify-between font-bold text-base border-t border-slate-200 dark:border-white/8 pt-3 mt-1">
            <span class="text-slate-900 dark:text-white">Total</span>
            <span class="text-blue-600 dark:text-blue-400 text-lg">₹<span x-text="(subtotal-discount+shipping).toLocaleString('en-IN',{minimumFractionDigits:0})"></span></span>
          </div>
        </div>

        <!-- CTA -->
        <a href="/checkout"
           class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all hover:scale-[1.01] shadow-lg shadow-blue-500/20">
          <i class="fa-solid fa-lock text-xs"></i>
          Proceed to Checkout
        </a>

        <!-- Payment icons -->
        <div class="flex items-center gap-2 pt-1 border-t border-slate-100 dark:border-white/8">
          <i class="fa-solid fa-shield-halved text-green-500 text-xs"></i>
          <span class="text-xs text-slate-400 dark:text-slate-500">Secure checkout · COD · UPI · Card</span>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function cartPage() {
  return {
    subtotal: <?= $subtotal ?>,
    discount: 0,
    shipping: <?= $subtotal >= FREE_SHIPPING_ABOVE ? 0 : SHIPPING_CHARGE ?>,
    couponCode: '',
    couponMsg: '',
    couponValid: false,
    couponLoading: false,
    async applyCoupon() {
      if(!this.couponCode.trim()) return;
      this.couponLoading = true;
      try {
        const d = await apiFetch('/api/coupons/validate', { method: 'POST', body: JSON.stringify({ code: this.couponCode, order_amount: this.subtotal }) });
        this.discount = d.data.discount;
        this.couponMsg = `Coupon applied! You save ₹${d.data.discount.toFixed(0)}`;
        this.couponValid = true;
        showToast('Coupon applied!', 'success');
      } catch(e) { this.couponMsg = e.message; this.couponValid = false; }
      this.couponLoading = false;
    }
  }
}

async function updateQty(itemId, qty) {
  if(qty < 1) { removeItem(itemId); return; }
  try {
    await apiFetch(`/api/cart/${itemId}`, { method: 'PATCH', body: JSON.stringify({ quantity: qty }) });
    document.getElementById(`qty-${itemId}`).textContent = qty;
  } catch(e) { showToast(e.message, 'error'); }
}

async function removeItem(itemId) {
  try {
    await apiFetch(`/api/cart/${itemId}`, { method: 'DELETE' });
    document.getElementById(`cart-item-${itemId}`)?.remove();
    showToast('Removed from cart', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}
</script>
