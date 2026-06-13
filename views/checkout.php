<?php
$pageTitle = 'Checkout';
$cartModel = new CartModel();
$items     = $cartModel->getItems(getCurrentUserId());
if (empty($items)) { redirect('/cart'); }
$subtotal = array_sum(array_map(fn($i) => (float)(($i['is_flash_sale']&&$i['flash_sale_price'])?$i['flash_sale_price']:$i['price']) * (int)$i['quantity'], $items));
$shipping = $subtotal >= FREE_SHIPPING_ABOVE ? 0 : SHIPPING_CHARGE;
$user     = getCurrentUser();
?>
<div class="max-w-5xl mx-auto px-4 py-8" x-data="checkoutPage()">
  <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-2">
    <i class="fa-solid fa-lock text-blue-600 dark:text-blue-400 text-lg"></i> Checkout
  </h1>

  <!-- Steps indicator -->
  <div class="flex items-center mb-8">
    <?php foreach(['Shipping','Payment','Confirm'] as $si => $step): ?>
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
           :class="step >= <?= $si+1 ?> ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-slate-100 dark:bg-white/8 text-slate-400 dark:text-slate-500'">
        <span x-show="step > <?= $si+1 ?>"><i class="fa-solid fa-check text-[10px]"></i></span>
        <span x-show="step <= <?= $si+1 ?>"><?= $si+1 ?></span>
      </div>
      <span class="text-sm font-medium transition-colors"
            :class="step >= <?= $si+1 ?> ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500'"><?= $step ?></span>
    </div>
    <?php if($si < 2): ?>
    <div class="flex-1 h-px mx-3 transition-colors" :class="step > <?= $si+1 ?> ? 'bg-blue-500' : 'bg-slate-200 dark:bg-white/8'"></div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">
    <div class="lg:col-span-2 space-y-5">

      <!-- Step 1: Shipping -->
      <div x-show="step===1" class="space-y-5">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-200 flex items-center gap-2">
          <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400 text-sm"></i> Shipping Address
        </h2>
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 p-5 shadow-sm">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Full Name <span class="text-red-500">*</span></label>
              <input type="text" x-model="form.name" placeholder="Your full name"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Phone <span class="text-red-500">*</span></label>
              <input type="tel" x-model="form.phone" placeholder="+91 98765 43210"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Address Line 1 <span class="text-red-500">*</span></label>
              <input type="text" x-model="form.address1" placeholder="House/Flat no., Street"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Address Line 2 <span class="text-slate-400 dark:text-slate-600 font-normal normal-case">(optional)</span></label>
              <input type="text" x-model="form.address2" placeholder="Area, Landmark"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">City <span class="text-red-500">*</span></label>
              <input type="text" x-model="form.city" placeholder="Mumbai"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">State <span class="text-red-500">*</span></label>
              <input type="text" x-model="form.state" placeholder="Maharashtra"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">PIN Code <span class="text-red-500">*</span></label>
              <input type="text" x-model="form.pincode" placeholder="400001"
                     class="input-base w-full px-4 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Country</label>
              <input type="text" value="India" readonly
                     class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,12%)] border border-slate-200 dark:border-white/6 text-slate-400 text-sm cursor-default">
            </div>
          </div>
        </div>
        <button @click="nextStep()"
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
          Continue to Payment <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
      </div>

      <!-- Step 2: Payment -->
      <div x-show="step===2" class="space-y-5">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-200 flex items-center gap-2">
          <i class="fa-solid fa-credit-card text-blue-600 dark:text-blue-400 text-sm"></i> Payment Method
        </h2>
        <div class="space-y-2.5">
          <?php foreach([
            ['cod','fa-money-bill-wave','green','Cash on Delivery','Pay when your order arrives'],
            ['upi','fa-mobile-screen','purple','UPI / QR','Google Pay, PhonePe, BHIM'],
            ['card','fa-credit-card','blue','Credit / Debit Card','Visa, Mastercard, RuPay'],
            ['netbanking','fa-building-columns','amber','Net Banking','All major banks supported'],
          ] as [$val,$icon,$color,$label,$sub]): ?>
          <label class="flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all"
                 :class="paymentMethod==='<?= $val ?>'
                   ? 'border-blue-500 bg-blue-50 dark:bg-blue-500/8 shadow-sm'
                   : 'border-slate-200 dark:border-white/8 bg-white dark:bg-[hsl(222,47%,10%)] hover:border-blue-300 dark:hover:border-blue-500/30'">
            <input type="radio" x-model="paymentMethod" value="<?= $val ?>" class="accent-blue-600">
            <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
              <?= match($color) {
                'green'  => 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400',
                'purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400',
                'blue'   => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400',
                'amber'  => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
                default  => 'bg-slate-100 dark:bg-white/8 text-slate-500',
              } ?>">
              <i class="fa-solid fa-<?= $icon ?> text-sm"></i>
            </span>
            <div>
              <p class="font-semibold text-slate-800 dark:text-slate-200 text-sm"><?= $label ?></p>
              <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5"><?= $sub ?></p>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <div class="flex gap-3">
          <button @click="step=1"
                  class="flex-1 py-3 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,12%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-medium text-sm transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
          </button>
          <button @click="nextStep()"
                  class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
            Review Order <i class="fa-solid fa-arrow-right text-xs"></i>
          </button>
        </div>
      </div>

      <!-- Step 3: Confirm -->
      <div x-show="step===3" class="space-y-5">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-200 flex items-center gap-2">
          <i class="fa-solid fa-clipboard-check text-blue-600 dark:text-blue-400 text-sm"></i> Review Your Order
        </h2>
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 p-5 space-y-3 shadow-sm">
          <?php foreach([['fa-user','Name','form.name'],['fa-phone','Phone','form.phone'],['fa-credit-card','Payment','paymentMethod']] as [$icon,$label,$val]): ?>
          <div class="flex justify-between items-center text-sm pb-3 border-b border-slate-100 dark:border-white/5 last:pb-0 last:border-0">
            <span class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
              <i class="fa-solid fa-<?= $icon ?> text-[10px] w-3"></i> <?= $label ?>
            </span>
            <span class="text-slate-800 dark:text-slate-200 font-medium capitalize" x-text="<?= $val ?>"></span>
          </div>
          <?php endforeach; ?>
          <div class="flex justify-between items-start text-sm">
            <span class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
              <i class="fa-solid fa-location-dot text-[10px] w-3"></i> Address
            </span>
            <span class="text-slate-800 dark:text-slate-200 font-medium text-right max-w-[220px]"
                  x-text="`${form.address1}, ${form.address2 ? form.address2+', ' : ''}${form.city}, ${form.state} – ${form.pincode}`"></span>
          </div>
          <div x-show="couponDiscount>0" class="flex justify-between items-center text-sm text-green-600 dark:text-green-400 pt-1">
            <span class="flex items-center gap-2">
              <i class="fa-solid fa-tag text-[10px] w-3"></i> Coupon (<span x-text="couponCode"></span>)
            </span>
            <span>−₹<span x-text="couponDiscount.toFixed(0)"></span></span>
          </div>
        </div>
        <div class="flex gap-3">
          <button @click="step=2"
                  class="flex-1 py-3 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,12%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-medium text-sm transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
          </button>
          <button @click="placeOrder()" :disabled="loading"
                  class="flex-1 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold text-sm transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">
            <span x-show="!loading" class="flex items-center gap-2">
              <i class="fa-solid fa-check text-xs"></i> Place Order
            </span>
            <span x-show="loading" x-cloak class="flex items-center gap-2">
              <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              Placing Order...
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 p-5 shadow-sm h-fit sticky top-20">
      <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 text-sm">
        <i class="fa-solid fa-receipt text-blue-600 dark:text-blue-400 text-xs"></i>
        Order Items (<?= count($items) ?>)
      </h3>

      <!-- Items list -->
      <div class="space-y-3 max-h-52 overflow-y-auto mb-4 pr-1">
        <?php foreach($items as $item):
          $p = (float)(($item['is_flash_sale']&&$item['flash_sale_price'])?$item['flash_sale_price']:$item['price']);
        ?>
        <div class="flex items-center gap-3">
          <?php if($item['thumbnail']): ?>
          <img src="<?= clean($item['thumbnail']) ?>" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 bg-slate-100 dark:bg-[hsl(222,47%,14%)]">
          <?php else: ?>
          <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,14%)] flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-box text-slate-300 dark:text-slate-600 text-base"></i>
          </div>
          <?php endif; ?>
          <div class="flex-1 min-w-0">
            <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-1 font-medium"><?= clean($item['name']) ?></p>
            <p class="text-xs text-slate-400 dark:text-slate-500">×<?= $item['quantity'] ?></p>
          </div>
          <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 flex-shrink-0">₹<?= number_format($p*(int)$item['quantity'],0) ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Coupon -->
      <div class="mb-4">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Coupon Code</p>
        <div class="flex gap-2">
          <input type="text" x-model="couponCode" placeholder="Enter code"
                 :disabled="couponApplied"
                 class="flex-1 px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 uppercase transition-colors disabled:opacity-60">
          <button @click="couponApplied ? removeCoupon() : applyCoupon()" :disabled="couponLoading"
                  :class="couponApplied ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-600 hover:bg-blue-700'"
                  class="px-3 py-2 rounded-xl text-white text-xs font-medium transition-colors disabled:opacity-50 flex-shrink-0">
            <span x-show="!couponLoading" x-text="couponApplied ? 'Remove' : 'Apply'"></span>
            <span x-show="couponLoading" class="w-3 h-3 border border-white/30 border-t-white rounded-full animate-spin inline-block" x-cloak></span>
          </button>
        </div>
        <p x-show="couponMsg" x-text="couponMsg"
           :class="couponApplied ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
           class="text-xs mt-1.5"></p>
      </div>

      <!-- Totals -->
      <div class="space-y-2.5 border-t border-slate-100 dark:border-white/8 pt-4 text-sm">
        <div class="flex justify-between">
          <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
          <span class="text-slate-700 dark:text-slate-300">₹<?= number_format($subtotal,0) ?></span>
        </div>
        <div x-show="couponDiscount>0" class="flex justify-between text-green-600 dark:text-green-400 font-medium">
          <span class="flex items-center gap-1"><i class="fa-solid fa-tag text-xs"></i> Discount</span>
          <span>−₹<span x-text="couponDiscount.toFixed(0)"></span></span>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1">
            <i class="fa-solid fa-truck text-xs"></i> Shipping
          </span>
          <span class="<?= $shipping===0?'text-green-600 dark:text-green-400 font-semibold':'text-slate-700 dark:text-slate-300' ?>">
            <?= $shipping===0?'FREE':'₹'.$shipping ?>
          </span>
        </div>
        <div class="flex justify-between font-bold text-base border-t border-slate-200 dark:border-white/8 pt-3 mt-1">
          <span class="text-slate-900 dark:text-white">Total</span>
          <span class="text-blue-600 dark:text-blue-400 text-lg">₹<span x-text="(<?= $subtotal ?> - couponDiscount + <?= $shipping ?>).toLocaleString('en-IN',{minimumFractionDigits:0})"></span></span>
        </div>
      </div>

      <!-- Trust -->
      <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/8 flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
        <i class="fa-solid fa-shield-halved text-green-500 text-sm"></i>
        Secured with 256-bit SSL encryption
      </div>
    </div>
  </div>
</div>

<script>
function checkoutPage() {
  return {
    step: 1,
    loading: false,
    paymentMethod: 'cod',
    couponCode: '',
    couponApplied: false,
    couponDiscount: 0,
    couponMsg: '',
    couponLoading: false,
    form: {
      name: '<?= clean($user['name']??'') ?>',
      phone: '<?= clean($user['phone']??'') ?>',
      address1:'', address2:'', city:'', state:'', pincode:''
    },
    nextStep() {
      if (this.step === 1) {
        if (!this.form.name || !this.form.phone || !this.form.address1 || !this.form.city || !this.form.state || !this.form.pincode) {
          showToast('Please fill all required fields', 'error');
          return;
        }
      }
      this.step++;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    async applyCoupon() {
      if (!this.couponCode.trim()) return;
      this.couponLoading = true;
      try {
        const subtotal = <?= $subtotal ?>;
        const d = await apiFetch('/api/coupons/validate', {
          method: 'POST',
          body: JSON.stringify({ code: this.couponCode.trim(), order_amount: subtotal })
        });
        this.couponDiscount = d.data.discount;
        this.couponApplied  = true;
        this.couponMsg      = `Coupon applied! You save ₹${d.data.discount.toFixed(0)}`;
        showToast('Coupon applied!', 'success');
      } catch(e) {
        this.couponMsg     = e.message;
        this.couponApplied = false;
        this.couponDiscount = 0;
      }
      this.couponLoading = false;
    },
    removeCoupon() {
      this.couponCode     = '';
      this.couponApplied  = false;
      this.couponDiscount = 0;
      this.couponMsg      = '';
    },
    async placeOrder() {
      this.loading = true;
      const subtotal = <?= $subtotal ?>;
      const shipping = <?= $shipping ?>;
      const discount = this.couponDiscount;
      const total    = subtotal - discount + shipping;

      const addr = {
        name: this.form.name, phone: this.form.phone,
        address_line1: this.form.address1, address_line2: this.form.address2,
        city: this.form.city, state: this.form.state,
        pincode: this.form.pincode, country: 'India'
      };

      const items = <?= json_encode(array_map(fn($i) => [
        'product_id'   => $i['product_id'],
        'product_name' => $i['name'],
        'thumbnail'    => $i['thumbnail'],
        'quantity'     => (int)$i['quantity'],
        'price'        => (float)(($i['is_flash_sale']&&$i['flash_sale_price'])?$i['flash_sale_price']:$i['price']),
      ], $items)) ?>;

      try {
        const d = await apiFetch('/api/orders', {
          method: 'POST',
          body: JSON.stringify({
            items,
            shipping_address: addr,
            payment_method: this.paymentMethod,
            subtotal,
            discount,
            shipping,
            total,
            coupon_code: this.couponApplied ? this.couponCode.trim() : null,
          })
        });
        showToast('Order placed successfully!', 'success');
        setTimeout(() => location.href = '/orders/' + d.data.id, 1000);
      } catch(e) {
        showToast(e.message, 'error');
        this.loading = false;
      }
    }
  }
}
</script>
