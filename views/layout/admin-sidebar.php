<aside class="flex-shrink-0 bg-[hsl(222,47%,8%)] border-r border-white/[0.06] flex flex-col transition-all duration-300 relative z-50"
       :class="sidebarOpen ? 'w-56' : 'w-14'">

  <!-- Logo -->
  <div class="h-14 flex items-center border-b border-white/[0.06] overflow-hidden px-3 flex-shrink-0">
    <a href="/admin" class="flex items-center gap-2.5 min-w-0">
      <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/20">
        <i class="fa-solid fa-bolt text-white text-sm"></i>
      </div>
      <div class="overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0'">
        <p class="text-white font-bold text-sm whitespace-nowrap leading-none">TT Electro</p>
        <p class="text-slate-500 text-[10px] whitespace-nowrap">Admin Panel</p>
      </div>
    </a>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 py-3 overflow-y-auto overflow-x-hidden space-y-0.5">
    <?php
    $current = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');
    $groups = [
      'Overview' => [
        ['/admin',               'Dashboard',     'fa-solid fa-gauge-high',       false],
      ],
      'Catalogue' => [
        ['/admin/products',      'Products',      'fa-solid fa-box',              false],
        ['/admin/categories',    'Categories',    'fa-solid fa-tags',             false],
        ['/admin/brands',        'Brands',        'fa-solid fa-tag',              false],
        ['/admin/inventory',     'Inventory',     'fa-solid fa-warehouse',        false],
      ],
      'Sales' => [
        ['/admin/orders',        'Orders',        'fa-solid fa-clipboard-list',   false],
        ['/admin/offers',        'Offers',        'fa-solid fa-percent',          false],
      ],
      'Content' => [
        ['/admin/banners',       'Banners',       'fa-solid fa-image',            false],
        ['/admin/blogs',         'Blog Posts',    'fa-solid fa-blog',             false],
        ['/admin/diy-kits',      'DIY Kits',      'fa-solid fa-screwdriver-wrench',false],
        ['/admin/print3d',       '3D Printing',   'fa-solid fa-print',            false],
        ['/admin/faq',           'FAQ',           'fa-solid fa-circle-question',  false],
      ],
      'Community' => [
        ['/admin/reviews',       'Reviews',       'fa-solid fa-star',             false],
        ['/admin/newsletter',    'Newsletter',    'fa-solid fa-envelope',         false],
        ['/admin/notifications', 'Notifications', 'fa-solid fa-bell',            false],
      ],
    ];

    foreach($groups as $groupLabel => $items):
    ?>
    <div class="overflow-hidden transition-all duration-300" :class="sidebarOpen?'px-1':'px-0'">
      <p class="px-3 pt-3 pb-1 text-[9px] font-bold uppercase tracking-[.1em] text-slate-600 whitespace-nowrap transition-all duration-300"
         x-show="sidebarOpen"><?= $groupLabel ?></p>
    </div>
    <?php foreach($items as [$href,$label,$icon,$badge]):
      $active = $current === $href || ($href !== '/admin' && str_starts_with($current, $href));
    ?>
    <div class="px-1">
      <a href="<?= $href ?>"
         title="<?= $label ?>"
         class="admin-nav-link <?= $active ? 'active' : '' ?>">
        <i class="<?= $icon ?> nav-icon" style="width:1.125rem;text-align:center;flex-shrink:0"></i>
        <span class="whitespace-nowrap transition-all duration-300 text-sm"
              x-show="sidebarOpen"><?= $label ?></span>
        <?php if($badge): ?>
        <span class="ml-auto bg-blue-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0"
              x-show="sidebarOpen"><?= $badge ?></span>
        <?php endif; ?>
      </a>
    </div>
    <?php endforeach; endforeach; ?>
  </nav>

  <!-- Bottom: User info -->
  <div class="border-t border-white/[0.06] p-2 flex-shrink-0">
    <a href="#" onclick="fetch('/api/auth/logout',{method:'POST'}).then(()=>location.href='/admin/login')"
       class="admin-nav-link text-red-400 hover:text-red-300 hover:bg-red-500/10 w-full">
      <i class="fa-solid fa-right-from-bracket nav-icon"></i>
      <span class="whitespace-nowrap text-sm" x-show="sidebarOpen">Sign Out</span>
    </a>
  </div>
</aside>
