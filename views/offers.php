<?php
$pageTitle = 'Offers & Deals';
$offers = (new OfferModel())->active();
$flashSale = (new ProductModel())->flashSale(8);
$coupons = [
  ['TTFIRST','10% off','For first-time buyers – 10% discount on all orders','First Order','min ₹499'],
  ['MAKER20','20% off','For makers and engineers – 20% off on electronics','Makers Deal','min ₹999'],
  ['FLAT150','₹150 off','Flat ₹150 off on orders above ₹1,499','Flat Discount','min ₹1,499'],
];
?>
<div class="max-w-7xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-white mb-2">Offers & Deals</h1>
  <p class="text-gray-400 mb-8">Exclusive discounts, flash sales, and coupon codes for our maker community</p>

  <!-- Coupon Codes -->
  <section class="mb-12">
    <h2 class="text-xl font-semibold text-white mb-4">🎟️ Coupon Codes</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <?php foreach($coupons as [$code,$discount,$desc,$badge,$min]): ?>
      <div class="rounded-2xl bg-gradient-to-br from-blue-900/30 to-[hsl(222,47%,12%)] border border-blue-500/20 p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3">
          <span class="px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-semibold border border-yellow-500/30"><?= $badge ?></span>
        </div>
        <p class="text-gray-400 text-sm mb-3"><?= $desc ?></p>
        <div class="flex items-center gap-3 mb-3">
          <div class="flex-1 bg-[hsl(222,47%,6%)] border border-dashed border-blue-500/40 rounded-lg px-3 py-2">
            <span class="text-blue-400 font-bold font-mono tracking-widest"><?= $code ?></span>
          </div>
          <button onclick="navigator.clipboard.writeText('<?= $code ?>').then(()=>showToast('Code copied!','success'))" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium transition-colors">Copy</button>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <span class="text-2xl font-bold text-white"><?= $discount ?></span>
          <span><?= $min ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Flash Sale Products -->
  <?php if(!empty($flashSale)): ?>
  <?php
  $_fsEnds    = array_filter(array_column($flashSale, 'flash_sale_ends'));
  $_fsEndTime = !empty($_fsEnds) ? (int)min(array_map('strtotime', $_fsEnds)) : 0;
  ?>
  <section class="mb-12"
    <?php if($_fsEndTime > 0): ?>x-data="countdown(<?= $_fsEndTime ?>)" x-show="!expired" x-cloak<?php endif; ?>>
    <div class="flex items-center gap-4 mb-6">
      <h2 class="text-xl font-semibold text-white">⚡ Flash Sale</h2>
      <?php if($_fsEndTime > 0): ?>
      <div class="flex items-center gap-2 text-sm">
        <span class="text-gray-400">Ends in:</span>
        <div class="flex items-center gap-1 font-mono">
          <span class="text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded" x-show="parseInt(days) > 0" x-text="days+'d'"></span>
          <span class="text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded" x-text="hours"></span>
          <span class="text-red-300 text-xs font-bold">:</span>
          <span class="text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded" x-text="minutes"></span>
          <span class="text-red-300 text-xs font-bold">:</span>
          <span class="text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded" x-text="seconds"></span>
        </div>
      </div>
      <?php else: ?>
      <span class="text-xs text-gray-500 bg-red-500/10 text-red-400 px-2 py-0.5 rounded">Limited time</span>
      <?php endif; ?>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php foreach($flashSale as $p): include __DIR__.'/partials/product-card.php'; endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Active Offers -->
  <?php if(!empty($offers)): ?>
  <section>
    <h2 class="text-xl font-semibold text-white mb-4">🏷️ Active Offers</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach($offers as $offer): ?>
      <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
        <?php if($offer['image']): ?><img src="<?= clean($offer['image']) ?>" class="w-full h-36 object-cover"><?php endif; ?>
        <div class="p-4">
          <div class="flex items-center gap-2 mb-2">
            <?php if($offer['badge']): ?><span class="px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-400 text-xs font-semibold"><?= clean($offer['badge']) ?></span><?php endif; ?>
            <?php if($offer['discount']): ?><span class="text-white font-bold text-lg"><?= $offer['discount'] ?>% OFF</span><?php endif; ?>
          </div>
          <h3 class="font-bold text-white"><?= clean($offer['title']) ?></h3>
          <?php if($offer['description']): ?><p class="text-gray-400 text-sm mt-1"><?= clean($offer['description']) ?></p><?php endif; ?>
          <?php if($offer['ends_at']): ?><p class="text-xs text-gray-500 mt-2">Ends: <?= date('M j, Y',strtotime($offer['ends_at'])) ?></p><?php endif; ?>
          <a href="/products" class="mt-3 block text-center py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">Shop Now →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
</div>
<script>
function countdown(endTime) {
  return {
    days:'00', hours:'00', minutes:'00', seconds:'00',
    expired: false,
    _int: null,
    init(){ this.tick(); this._int = setInterval(() => this.tick(), 1000); },
    tick(){
      const diff = endTime > 0 ? endTime * 1000 - Date.now() : 0;
      if (diff <= 0) {
        this.days='00'; this.hours='00'; this.minutes='00'; this.seconds='00';
        if (!this.expired) { this.expired = true; clearInterval(this._int); }
        return;
      }
      this.days    = String(Math.floor(diff / 86400000)).padStart(2,'0');
      this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
      this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
      this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
    }
  }
}
</script>
