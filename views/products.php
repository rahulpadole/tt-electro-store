<?php
$pageTitle = 'Products';
$pm = new ProductModel();
$cm = new CategoryModel();
$bm = new BrandModel();

$filters = [
  'q'           => trim($_GET['q']   ?? ''),
  'category_id' => $_GET['category_id'] ?? '',
  'brand_id'    => $_GET['brand_id']    ?? '',
  'min_price'   => $_GET['min_price']   ?? '',
  'max_price'   => $_GET['max_price']   ?? '',
  'sort'        => $_GET['sort']        ?? 'newest',
];
$page   = max(1,(int)($_GET['page'] ?? 1));
$result = $pm->all($filters, $page);
$cats   = $cm->all();
$brands = $bm->all();

$queryString = http_build_query(array_filter([
  'q'           => $filters['q'],
  'category_id' => $filters['category_id'],
  'brand_id'    => $filters['brand_id'],
  'min_price'   => $filters['min_price'],
  'max_price'   => $filters['max_price'],
  'sort'        => $filters['sort'] !== 'newest' ? $filters['sort'] : '',
]));
?>

<div class="max-w-7xl mx-auto px-4 py-8">

  <!-- Page header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
      <?= $filters['q'] ? 'Results for "<span class="gradient-text">'.clean($filters['q']).'</span>"' : 'All Products' ?>
    </h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1"><?= number_format($result['total']) ?> products found</p>
  </div>

  <div class="flex flex-col lg:flex-row gap-6">

    <!-- ── Sidebar Filters ────────────────────────────────── -->
    <aside class="lg:w-60 flex-shrink-0" x-data="{filtersOpen:false}">
      <!-- Mobile filter toggle -->
      <button @click="filtersOpen=!filtersOpen" class="lg:hidden w-full mb-4 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 text-sm font-medium shadow-sm">
        <i class="fa-solid fa-sliders text-xs"></i>
        Filters & Sort
        <i class="fa-solid fa-chevron-down text-[9px]" :class="filtersOpen && 'rotate-180'" style="transition:.15s"></i>
      </button>

      <div :class="{'hidden lg:block': !filtersOpen, 'block': filtersOpen}" class="space-y-4">
        <form action="/products" method="GET" id="filterForm">
          <?php if($filters['q']): ?>
          <input type="hidden" name="q" value="<?= clean($filters['q']) ?>">
          <?php endif; ?>

          <!-- Sort -->
          <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-4">
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <i class="fa-solid fa-arrow-up-wide-short text-[10px]"></i> Sort By
            </h3>
            <select name="sort" onchange="this.form.submit()"
                    class="w-full text-sm rounded-xl px-3 py-2.5 bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 focus:outline-none focus:border-blue-500 transition-colors">
              <?php foreach(['newest'=>'Newest First','price_asc'=>'Price: Low → High','price_desc'=>'Price: High → Low','popular'=>'Most Popular'] as $val => $label): ?>
              <option value="<?= $val ?>" <?= $filters['sort']===$val?'selected':'' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Categories -->
          <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-4">
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <i class="fa-solid fa-grid-2 text-[10px]"></i> Category
            </h3>
            <div class="space-y-1.5 max-h-52 overflow-y-auto pr-1">
              <label class="flex items-center gap-2.5 cursor-pointer py-1 group">
                <input type="radio" name="category_id" value="" <?= empty($filters['category_id'])?'checked':'' ?> class="accent-blue-600 w-3.5 h-3.5" onchange="this.form.submit()">
                <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">All Categories</span>
              </label>
              <?php foreach($cats as $cat): ?>
              <label class="flex items-center gap-2.5 cursor-pointer py-1 group">
                <input type="radio" name="category_id" value="<?= $cat['id'] ?>" <?= $filters['category_id']==$cat['id']?'checked':'' ?> class="accent-blue-600 w-3.5 h-3.5" onchange="this.form.submit()">
                <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors"><?= clean($cat['name']) ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Brands -->
          <?php if(!empty($brands)): ?>
          <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-4">
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <i class="fa-solid fa-tag text-[10px]"></i> Brand
            </h3>
            <div class="space-y-1.5 max-h-44 overflow-y-auto pr-1">
              <label class="flex items-center gap-2.5 cursor-pointer py-1 group">
                <input type="radio" name="brand_id" value="" <?= empty($filters['brand_id'])?'checked':'' ?> class="accent-blue-600 w-3.5 h-3.5" onchange="this.form.submit()">
                <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">All Brands</span>
              </label>
              <?php foreach($brands as $b): ?>
              <label class="flex items-center gap-2.5 cursor-pointer py-1 group">
                <input type="radio" name="brand_id" value="<?= $b['id'] ?>" <?= $filters['brand_id']==$b['id']?'checked':'' ?> class="accent-blue-600 w-3.5 h-3.5" onchange="this.form.submit()">
                <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors"><?= clean($b['name']) ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Price Range -->
          <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-4">
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <i class="fa-solid fa-indian-rupee-sign text-[10px]"></i> Price Range
            </h3>
            <div class="flex gap-2 items-center mb-3">
              <input type="number" name="min_price" placeholder="Min"
                     value="<?= clean($filters['min_price']) ?>"
                     class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-blue-500">
              <span class="text-slate-300 dark:text-slate-600 text-xs font-bold">—</span>
              <input type="number" name="max_price" placeholder="Max"
                     value="<?= clean($filters['max_price']) ?>"
                     class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/8 text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors">Apply Price</button>
          </div>

          <?php if(array_filter(array_values($filters),fn($v)=>$v!==''&&$v!=='newest')): ?>
          <a href="/products" class="flex items-center justify-center gap-1.5 w-full py-2.5 text-xs font-medium text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 border border-red-200 dark:border-red-500/20 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/8 transition-all">
            <i class="fa-solid fa-xmark"></i> Clear All Filters
          </a>
          <?php endif; ?>
        </form>
      </div>
    </aside>

    <!-- ── Products Grid ──────────────────────────────────── -->
    <div class="flex-1 min-w-0">

      <!-- Toolbar -->
      <div class="flex items-center justify-between gap-4 mb-5 bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 rounded-2xl px-4 py-3 shadow-sm">
        <p class="text-sm text-slate-500 dark:text-slate-400 hidden sm:block">
          Showing <span class="font-semibold text-slate-800 dark:text-slate-200"><?= count($result['items']) ?></span> of <span class="font-semibold text-slate-800 dark:text-slate-200"><?= number_format($result['total']) ?></span> products
        </p>
        <form action="/products" method="GET" class="flex gap-2 flex-1 max-w-sm ml-auto">
          <?php foreach($filters as $k=>$v): if($k!=='q'&&$v!='') echo "<input type='hidden' name='{$k}' value='".clean($v)."'>"; endforeach; ?>
          <div class="relative flex-1">
            <input type="text" name="q" placeholder="Search within results..." value="<?= clean($filters['q']) ?>"
                   class="input-base w-full pl-8 pr-4 py-2 text-sm">
            <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          </div>
          <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors flex-shrink-0">Search</button>
        </form>
      </div>

      <?php if(empty($result['items'])): ?>
      <!-- Empty state -->
      <div class="text-center py-24 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
        <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-5">
          <i class="fa-solid fa-magnifying-glass text-3xl text-slate-300 dark:text-slate-600"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300">No products found</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5 mb-5">Try adjusting your filters or search terms</p>
        <a href="/products" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
          <i class="fa-solid fa-rotate-left text-xs"></i> Clear Filters
        </a>
      </div>
      <?php else: ?>

      <!-- Product Grid -->
      <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php foreach($result['items'] as $p): include __DIR__ . '/partials/product-card.php'; endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if($result['total_pages'] > 1): ?>
      <div class="flex items-center justify-center gap-2 mt-10">
        <?php if($result['page'] > 1): ?>
        <a href="?<?= $queryString ?>&page=<?= $result['page']-1 ?>"
           class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-blue-400 dark:hover:border-blue-500/40 text-sm font-medium transition-all shadow-sm">
          <i class="fa-solid fa-chevron-left text-xs"></i> Prev
        </a>
        <?php endif; ?>
        <?php
        $start = max(1,$result['page']-2);
        $end   = min($result['total_pages'],$result['page']+2);
        for($i=$start;$i<=$end;$i++): ?>
        <a href="?<?= $queryString ?>&page=<?= $i ?>"
           class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm
           <?= $i===$result['page']
             ? 'bg-blue-600 text-white border border-blue-600 shadow-blue-500/20'
             : 'bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-blue-400 dark:hover:border-blue-500/40' ?>">
          <?= $i ?>
        </a>
        <?php endfor; ?>
        <?php if($result['page'] < $result['total_pages']): ?>
        <a href="?<?= $queryString ?>&page=<?= $result['page']+1 ?>"
           class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-blue-400 dark:hover:border-blue-500/40 text-sm font-medium transition-all shadow-sm">
          Next <i class="fa-solid fa-chevron-right text-xs"></i>
        </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
</div>
