<?php
$pageTitle = 'Premium Electronics for Makers & Engineers';
$pageDesc  = 'TT Electro Store – Premium electronics, DIY kits, and 3D printing for makers and engineers in India.';
$banners   = (new BannerModel())->active();
$categories= (new CategoryModel())->all();
$featured  = (new ProductModel())->featured(8);
$trending  = (new ProductModel())->trending(8);
$bestSellers=(new ProductModel())->bestSellers(8);
$flashSale = (new ProductModel())->flashSale(6);
$diyKits   = (new DiyKitModel())->all();
$blogs     = (new BlogModel())->all(1,3);
$faqs      = (new FaqModel())->all();
$offers    = (new OfferModel())->active();
?>

<!-- ── Hero Slider ─────────────────────────────────────── -->
<section class="relative overflow-hidden hero-gradient -mt-16" x-data="heroSlider()">
  <div class="relative h-[calc(500px+4rem)] md:h-[calc(600px+4rem)]">
    <?php foreach($banners as $i => $banner): ?>
    <div class="absolute inset-0 transition-opacity duration-700"
         :class="current === <?= $i ?> ? 'opacity-100 z-10' : 'opacity-0 z-0'">
      <?php if($banner['image']): ?>
      <img src="<?= clean($banner['image']) ?>" alt="<?= clean($banner['title']) ?>"
           class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-r from-[hsl(222,47%,5%)]/95 via-[hsl(222,47%,6%)]/70 to-transparent"></div>
      <?php else: ?>
      <div class="w-full h-full hero-gradient"></div>
      <?php endif; ?>

      <div class="absolute inset-0 flex items-end pb-14 md:pb-20 md:items-center md:pb-0 pt-16">
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full">
          <div class="max-w-2xl fade-up">
            <?php if($banner['badge']): ?>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-500/15 text-blue-300 text-xs font-semibold mb-5 border border-blue-400/20 tracking-wide uppercase">
              <span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block animate-pulse"></span>
              <?= clean($banner['badge']) ?>
            </span>
            <?php endif; ?>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-4 tracking-tight">
              <?= clean($banner['title']) ?>
            </h1>
            <?php if($banner['subtitle']): ?>
            <p class="text-slate-300 text-lg md:text-xl mb-8 leading-relaxed"><?= clean($banner['subtitle']) ?></p>
            <?php endif; ?>
            <div class="flex gap-3 flex-wrap">
              <a href="<?= clean($banner['link'] ?? '/products') ?>"
                 class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all hover:scale-[1.02] shadow-xl shadow-blue-500/25 text-sm">
                Shop Now <i class="fa-solid fa-arrow-right text-xs"></i>
              </a>
              <a href="/3d-printing"
                 class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl border border-white/20 hover:bg-white/10 text-white font-semibold transition-all text-sm backdrop-blur-sm">
                <i class="fa-solid fa-cube text-xs"></i> 3D Printing
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($banners)): ?>
    <div class="w-full h-full hero-gradient flex items-center relative overflow-hidden pt-16">
      <!-- Decorative blobs -->
      <div class="absolute top-10 right-20 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute bottom-0 right-40 w-64 h-64 bg-cyan-500/8 rounded-full blur-3xl pointer-events-none"></div>
      <div class="max-w-7xl mx-auto px-6 md:px-8 w-full">
        <div class="max-w-2xl fade-up">
          <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-500/15 text-blue-300 text-xs font-semibold mb-5 border border-blue-400/20 tracking-wide uppercase">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block animate-pulse"></span>
            New Arrivals
          </span>
          <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight mb-4 tracking-tight">
            Power Your <span class="gradient-text">Next Idea</span>
          </h1>
          <p class="text-slate-300 text-lg md:text-xl mb-8 leading-relaxed">
            Premium electronics for makers and engineers in India.<br class="hidden md:block"> Components, kits, and 3D printing — all in one place.
          </p>
          <div class="flex gap-3 flex-wrap">
            <a href="/products" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all hover:scale-[1.02] shadow-xl shadow-blue-500/25 text-sm">
              Shop Now <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <a href="/3d-printing" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl border border-white/20 hover:bg-white/10 text-white font-semibold transition-all text-sm backdrop-blur-sm">
              <i class="fa-solid fa-cube text-xs"></i> 3D Printing
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Slider controls -->
    <?php if(count($banners) > 1): ?>
    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white flex items-center justify-center transition-all backdrop-blur-sm">
      <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>
    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white flex items-center justify-center transition-all backdrop-blur-sm">
      <i class="fa-solid fa-chevron-right text-sm"></i>
    </button>
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex gap-2">
      <?php foreach($banners as $i => $b): ?>
      <button @click="current=<?= $i ?>" :class="current===<?= $i ?> ? 'bg-blue-400 w-6' : 'bg-white/30 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Trust badges strip -->
  <div class="trust-strip">
    <div class="max-w-7xl mx-auto px-4 py-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <?php
        $trustItems = [
          ['fa-truck-fast','Fast Delivery','5–7 business days','blue'],
          ['fa-shield-check','Genuine Products','100% authentic','green'],
          ['fa-headset','Expert Support','Mon–Sat 9am–6pm','purple'],
          ['fa-rotate-left','Easy Returns','1-day return policy','amber'],
        ];
        foreach($trustItems as [$icon,$title,$sub,$color]): ?>
        <div class="flex items-center gap-3">
          <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
            <?= match($color) {
              'blue'   => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400',
              'green'  => 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400',
              'purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400',
              'amber'  => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
              default  => 'bg-slate-100 dark:bg-white/8 text-slate-500',
            } ?>">
            <i class="fa-solid fa-<?= $icon ?> text-sm"></i>
          </span>
          <div>
            <p class="text-slate-800 dark:text-slate-200 text-sm font-semibold"><?= $title ?></p>
            <p class="text-slate-500 dark:text-slate-500 text-xs"><?= $sub ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ── Browse Categories ────────────────────────────────── -->
<?php if(!empty($categories)): ?>
<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <p class="section-label"><i class="fa-solid fa-grid-2"></i> Categories</p>
      <h2 class="section-title">Browse by Category</h2>
      <p class="section-subtitle">Find exactly what you need</p>
    </div>
    <a href="/products" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
      View All <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
  <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-3 md:gap-4">
    <?php foreach(array_slice($categories,0,12) as $cat): ?>
    <a href="/products?category_id=<?= $cat['id'] ?>"
       class="group flex flex-col items-center gap-2.5 p-4 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-400 dark:hover:border-blue-500/40 hover:shadow-lg hover:shadow-blue-100 dark:hover:shadow-blue-500/8 transition-all duration-200 hover:-translate-y-0.5">
      <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-500/10 group-hover:bg-blue-100 dark:group-hover:bg-blue-500/20 flex items-center justify-center text-xl transition-all duration-200">
        <?= $cat['icon'] ? clean($cat['icon']) : '<i class="fa-solid fa-box text-blue-500 text-base"></i>' ?>
      </div>
      <span class="text-xs font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white text-center leading-tight transition-colors"><?= clean($cat['name']) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ── Flash Sale ───────────────────────────────────────── -->
<?php if(!empty($flashSale)): ?>
<section class="py-14 bg-gradient-to-r from-red-50 via-white to-orange-50 dark:from-red-900/15 dark:via-[hsl(222,47%,8%)] dark:to-orange-900/15 border-y border-red-100 dark:border-red-500/10">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
      <div class="flex items-center gap-4">
        <div>
          <p class="section-label text-red-600 dark:text-red-400"><i class="fa-solid fa-bolt"></i> Limited Time</p>
          <h2 class="section-title">Flash Sale</h2>
          <p class="section-subtitle">Deals ending soon — grab them before they're gone!</p>
        </div>
        <div class="flex gap-2" x-data="countdown(<?= strtotime('+24 hours') ?>)">
          <?php foreach(['hours'=>'HRS','minutes'=>'MIN','seconds'=>'SEC'] as $unit=>$label): ?>
          <div class="text-center">
            <div class="countdown-digit" x-text="<?= $unit ?>"></div>
            <div class="text-[9px] font-bold tracking-widest text-slate-400 dark:text-slate-500 mt-1"><?= $label ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="/offers" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold transition-colors flex items-center gap-1 flex-shrink-0">
        See All Deals <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <?php foreach($flashSale as $p): ?>
      <?php include __DIR__ . '/../partials/product-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── Featured Products ────────────────────────────────── -->
<?php if(!empty($featured)): ?>
<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <p class="section-label"><i class="fa-solid fa-star"></i> Hand-picked</p>
      <h2 class="section-title">Featured Products</h2>
      <p class="section-subtitle">Curated by our experts</p>
    </div>
    <a href="/products?featured=1" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
      View All <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach($featured as $p): include __DIR__ . '/../partials/product-card.php'; endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ── Promo Banner ─────────────────────────────────────── -->
<section class="max-w-7xl mx-auto px-4 pb-10">
  <div class="rounded-3xl overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 dark:from-blue-700 dark:via-blue-800 dark:to-indigo-800 p-8 md:p-10 relative">
    <!-- Decorative circles -->
    <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute top-4 right-20 w-24 h-24 bg-white/5 rounded-full"></div>
    <div class="absolute -bottom-8 right-4 w-36 h-36 bg-white/5 rounded-full"></div>
    <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-200 uppercase tracking-widest mb-3">
          <i class="fa-solid fa-percent"></i> Exclusive Offer
        </span>
        <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-2">Get 15% Off Your First Order</h3>
        <p class="text-blue-200 text-sm">Use code <span class="font-bold text-white bg-white/15 px-2 py-0.5 rounded-lg">TTFIRST15</span> at checkout</p>
      </div>
      <a href="/products" class="flex-shrink-0 inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-white text-blue-700 font-bold hover:bg-blue-50 transition-all text-sm shadow-lg">
        Shop Now <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
  </div>
</section>

<!-- ── Trending Products ─────────────────────────────────── -->
<?php if(!empty($trending)): ?>
<section class="max-w-7xl mx-auto px-4 pb-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <p class="section-label"><i class="fa-solid fa-fire"></i> Trending</p>
      <h2 class="section-title">Trending Now</h2>
      <p class="section-subtitle">What the maker community is buying</p>
    </div>
    <a href="/products?trending=1" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
      View All <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach($trending as $p): include __DIR__ . '/../partials/product-card.php'; endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ── Best Sellers ──────────────────────────────────────── -->
<?php if(!empty($bestSellers)): ?>
<section class="bg-slate-50 dark:bg-[hsl(222,47%,7%)] border-y border-slate-100 dark:border-white/5 py-16">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="section-label"><i class="fa-solid fa-trophy"></i> Popular</p>
        <h2 class="section-title">Best Sellers</h2>
        <p class="section-subtitle">Most loved products</p>
      </div>
      <a href="/products" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
        View All <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php foreach($bestSellers as $p): include __DIR__ . '/../partials/product-card.php'; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── DIY Vision Kits ───────────────────────────────────── -->
<?php if(!empty($diyKits)): ?>
<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <p class="section-label"><i class="fa-solid fa-screwdriver-wrench"></i> Build Something</p>
      <h2 class="section-title">DIY Vision Kits</h2>
      <p class="section-subtitle">Everything you need to build amazing projects</p>
    </div>
    <a href="/diy-kits" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
      View All Kits <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach(array_slice($diyKits,0,3) as $kit): ?>
    <a href="/diy-kits" class="group flex flex-col rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-purple-400 dark:hover:border-purple-500/40 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-100 dark:hover:shadow-purple-500/8">
      <?php if($kit['thumbnail']): ?>
      <div class="h-48 overflow-hidden bg-slate-50 dark:bg-[hsl(222,47%,13%)]">
        <img src="<?= clean($kit['thumbnail']) ?>" alt="<?= clean($kit['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
      </div>
      <?php else: ?>
      <div class="h-48 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 flex items-center justify-center">
        <i class="fa-solid fa-screwdriver-wrench text-5xl text-purple-300 dark:text-purple-600"></i>
      </div>
      <?php endif; ?>
      <div class="p-5 flex flex-col flex-1">
        <div class="flex items-center gap-2 mb-2.5">
          <?php
          $diffMap = ['beginner'=>['green','Beginner'],'intermediate'=>['amber','Intermediate'],'advanced'=>['red','Advanced']];
          $diff = $diffMap[$kit['difficulty'] ?? 'beginner'] ?? ['blue','Beginner'];
          ?>
          <span class="chip chip-<?= $diff[0] ?>"><?= $diff[1] ?></span>
          <span class="text-xs text-slate-400 dark:text-slate-500"><?= count($kit['components'] ?? []) ?> components</span>
        </div>
        <h3 class="font-semibold text-slate-900 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors text-sm leading-snug"><?= clean($kit['name']) ?></h3>
        <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5 line-clamp-2 leading-relaxed flex-1"><?= clean($kit['description']??'') ?></p>
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-white/5">
          <span class="text-lg font-bold text-slate-900 dark:text-white">₹<?= number_format((float)$kit['price'],0) ?></span>
          <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 flex items-center gap-1">View Kit <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ── 3D Printing CTA ───────────────────────────────────── -->
<section class="max-w-7xl mx-auto px-4 pb-16">
  <div class="rounded-3xl border border-slate-200 dark:border-blue-500/15 overflow-hidden relative">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-cyan-500/5 dark:from-blue-600/20 dark:via-[hsl(222,47%,10%)] dark:to-cyan-500/10"></div>
    <div class="relative p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
      <div class="flex-1">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center mb-5">
          <i class="fa-solid fa-cube text-blue-600 dark:text-blue-400 text-2xl"></i>
        </div>
        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-3">Custom 3D Printing Service</h2>
        <p class="text-slate-600 dark:text-slate-400 max-w-lg text-sm leading-relaxed">Upload your design, choose your material, and we'll print and deliver to your door. Professional-grade printing with multiple material options.</p>
        <div class="flex flex-wrap gap-x-6 gap-y-2 mt-5 text-sm">
          <?php foreach(['PLA / ABS / PETG','Fast Turnaround','Pan-India Delivery','STL & Image Upload'] as $feat): ?>
          <span class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
            <i class="fa-solid fa-circle-check text-blue-600 dark:text-blue-400 text-xs"></i>
            <?= $feat ?>
          </span>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="/3d-printing"
         class="flex-shrink-0 inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all hover:scale-[1.02] shadow-xl shadow-blue-500/20 text-sm whitespace-nowrap">
        <i class="fa-solid fa-print text-sm"></i>
        Get a Quote
      </a>
    </div>
  </div>
</section>

<!-- ── Latest Blog Posts ─────────────────────────────────── -->
<?php if(!empty($blogs['items'])): ?>
<section class="bg-slate-50 dark:bg-[hsl(222,47%,7%)] border-t border-slate-100 dark:border-white/5 py-16">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="section-label"><i class="fa-solid fa-newspaper"></i> From the Blog</p>
        <h2 class="section-title">Latest Articles</h2>
        <p class="section-subtitle">Tips, tutorials, and maker stories</p>
      </div>
      <a href="/blogs" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center gap-1">
        View All <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <?php foreach($blogs['items'] as $b): ?>
      <a href="/blogs/<?= clean($b['slug']) ?>"
         class="group flex flex-col rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-300 dark:hover:border-blue-500/30 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-50 dark:hover:shadow-blue-500/8">
        <?php if($b['thumbnail']): ?>
        <div class="h-48 overflow-hidden bg-slate-100 dark:bg-[hsl(222,47%,13%)]">
          <img src="<?= clean($b['thumbnail']) ?>" alt="<?= clean($b['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
        <?php else: ?>
        <div class="h-48 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 flex items-center justify-center">
          <i class="fa-solid fa-newspaper text-5xl text-blue-200 dark:text-blue-700"></i>
        </div>
        <?php endif; ?>
        <div class="p-5 flex flex-col flex-1">
          <?php if($b['category']): ?>
          <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wide mb-2"><?= clean($b['category']) ?></span>
          <?php endif; ?>
          <h3 class="font-semibold text-slate-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors text-sm leading-snug line-clamp-2 flex-1"><?= clean($b['title']) ?></h3>
          <p class="text-slate-500 dark:text-slate-400 text-xs mt-2 line-clamp-2 leading-relaxed"><?= clean($b['excerpt']??'') ?></p>
          <div class="flex items-center gap-3 mt-4 pt-4 border-t border-slate-100 dark:border-white/5 text-xs text-slate-400 dark:text-slate-500">
            <span class="flex items-center gap-1.5">
              <i class="fa-regular fa-user text-[10px]"></i>
              <?= clean($b['author_name']??'TT Electro') ?>
            </span>
            <?php if($b['reading_time']): ?>
            <span class="flex items-center gap-1.5">
              <i class="fa-regular fa-clock text-[10px]"></i>
              <?= $b['reading_time'] ?> min read
            </span>
            <?php endif; ?>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── FAQ ──────────────────────────────────────────────── -->
<?php if(!empty($faqs)): ?>
<section class="max-w-3xl mx-auto px-4 py-16">
  <div class="text-center mb-10">
    <p class="section-label inline-flex justify-center"><i class="fa-solid fa-circle-question"></i> FAQ</p>
    <h2 class="section-title">Frequently Asked Questions</h2>
    <p class="section-subtitle">Quick answers to common questions</p>
  </div>
  <div class="space-y-2.5">
    <?php foreach(array_slice($faqs,0,6) as $f): ?>
    <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 overflow-hidden hover:border-blue-300 dark:hover:border-blue-500/20 transition-colors" x-data="{open:false}">
      <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-4 text-left gap-4">
        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm leading-snug"><?= clean($f['question']) ?></span>
        <span class="w-6 h-6 rounded-lg flex-shrink-0 flex items-center justify-center bg-slate-100 dark:bg-white/8 text-slate-500 dark:text-slate-400 transition-transform duration-200" :class="open && 'rotate-180 bg-blue-50 dark:bg-blue-500/10 !text-blue-600 dark:!text-blue-400'">
          <i class="fa-solid fa-chevron-down text-[10px]"></i>
        </span>
      </button>
      <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
           class="px-5 pb-5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-white/5 pt-4">
        <?= clean($f['answer']) ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="text-center mt-6">
    <a href="/faq" class="inline-flex items-center gap-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors">
      View all FAQs <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
</section>
<?php endif; ?>

<!-- ── Newsletter CTA ────────────────────────────────────── -->
<section class="max-w-7xl mx-auto px-4 pb-16">
  <div class="rounded-3xl bg-gradient-to-r from-slate-900 to-slate-800 dark:from-[hsl(222,47%,12%)] dark:to-[hsl(222,47%,8%)] border border-slate-700 dark:border-white/8 p-8 md:p-12 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-30">
      <div class="absolute top-0 left-1/4 w-72 h-72 bg-blue-500 rounded-full blur-3xl opacity-10"></div>
      <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-cyan-500 rounded-full blur-3xl opacity-10"></div>
    </div>
    <div class="relative">
      <p class="section-label justify-center text-blue-400 mb-1"><i class="fa-solid fa-envelope"></i> Newsletter</p>
      <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Stay in the Loop</h2>
      <p class="text-slate-400 text-sm mb-7 max-w-md mx-auto">Get exclusive deals, new product alerts, and maker tutorials delivered to your inbox.</p>
      <form x-data="{email:'',loading:false}" @submit.prevent="
        loading=true;
        fetch('/api/newsletter/subscribe',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email})})
          .then(r=>r.json()).then(d=>{ showToast(d.message,'success'); email=''; }).catch(()=>showToast('Error','error')).finally(()=>loading=false)"
           class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
        <input type="email" x-model="email" required placeholder="Enter your email address"
               class="flex-1 px-4 py-3 rounded-xl bg-white/10 border border-white/15 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-blue-400 transition-all">
        <button type="submit" :disabled="loading"
                class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-all disabled:opacity-50 flex items-center gap-2 justify-center flex-shrink-0">
          <span x-show="!loading">Subscribe</span>
          <span x-show="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin inline-block"></span>
        </button>
      </form>
      <p class="text-slate-500 text-xs mt-3">No spam, unsubscribe at any time.</p>
    </div>
  </div>
</section>

<script>
function heroSlider() {
  return {
    current: 0,
    total: <?= count($banners) ?: 1 ?>,
    init() { if(this.total > 1) setInterval(() => this.next(), 5500); },
    next() { this.current = (this.current + 1) % this.total; },
    prev() { this.current = (this.current - 1 + this.total) % this.total; }
  }
}
function countdown(endTime) {
  return {
    hours: '00', minutes: '00', seconds: '00',
    init() { this.tick(); setInterval(() => this.tick(), 1000); },
    tick() {
      const diff = Math.max(0, endTime * 1000 - Date.now());
      this.hours   = String(Math.floor(diff / 3600000)).padStart(2,'0');
      this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
      this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
    }
  }
}
</script>
