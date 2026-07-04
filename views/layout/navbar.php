<?php
$isHome    = (rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') === '');
$curPath   = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
function navActive(string $href, string $cur): string {
    $h = rtrim($href, '/');
    return ($h === $cur || ($h !== '' && str_starts_with($cur, $h))) ? 'nav-link-active' : 'nav-link';
}
?>
<nav id="main-nav"
     class="sticky top-0 z-50 transition-all duration-300 backdrop-blur-md"
     :class="navTransparent ? 'bg-transparent border-b border-transparent shadow-none' : 'nav-scrolled border-b shadow-sm'"
     x-data="{
       mobileOpen: false,
       scrolled: false,
       isHome: <?= $isHome ? 'true' : 'false' ?>,
       get navTransparent() { return this.isHome && !this.scrolled; },
       init() {
         if (this.isHome) {
           const s = () => { this.scrolled = window.scrollY > 60; };
           window.addEventListener('scroll', s, { passive: true });
           s();
         }
       }
     }">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center gap-4">

    <!-- ═══ Logo ════════════════════════════════════════════════════ -->
    <a href="/" class="flex-shrink-0 logo-link group" aria-label="TT Electro Store">
      <img src="/assets/logo.png" alt="TT Electro Store"
           class="logo-full hidden sm:block h-9 w-auto"
           :class="(navTransparent || isDark) ? 'logo-invert' : ''">
      <img src="/assets/logo-icon.png" alt="TT"
           class="logo-icon block sm:hidden h-8 w-auto"
           :class="(navTransparent || isDark) ? 'logo-invert' : ''">
    </a>

    <!-- ═══ Desktop Nav ══════════════════════════════════════════════ -->
    <div class="hidden lg:flex items-center gap-0.5 text-sm flex-shrink-0">

      <a href="/products"
         class="<?= navActive('/products', $curPath) ?> nav-px py-2 rounded-lg font-medium"
         :class="navTransparent ? 'nav-link-transparent' : ''">
        Products
      </a>

      <a href="/diy-kits"
         class="<?= navActive('/diy-kits', $curPath) ?> nav-px py-2 rounded-lg font-medium"
         :class="navTransparent ? 'nav-link-transparent' : ''">
        DIY Kits
      </a>

      <a href="/3d-printing"
         class="<?= navActive('/3d-printing', $curPath) ?> nav-px py-2 rounded-lg font-medium"
         :class="navTransparent ? 'nav-link-transparent' : ''">
        3D Printing
      </a>

      <a href="/offers"
         class="<?= navActive('/offers', $curPath) === 'nav-link-active' ? 'nav-link-offer-active' : 'nav-link-offer' ?> nav-px py-2 rounded-lg font-semibold flex items-center gap-1.5"
         :class="navTransparent ? 'nav-link-offer-transparent' : ''">
        <i class="fa-solid fa-tag text-xs"></i> Offers
      </a>

      <a href="/blogs"
         class="<?= navActive('/blogs', $curPath) ?> nav-px py-2 rounded-lg font-medium"
         :class="navTransparent ? 'nav-link-transparent' : ''">
        Blog
      </a>

      <a href="/track-order"
         class="<?= navActive('/track-order', $curPath) ?> nav-px py-2 rounded-lg font-medium"
         :class="navTransparent ? 'nav-link-transparent' : ''">
        Track Order
      </a>

    </div>

    <!-- ═══ Search ═══════════════════════════════════════════════════ -->
    <form action="/products" method="GET"
          class="hidden md:flex flex-1 max-w-xs lg:max-w-sm">
      <div class="relative w-full">
        <input type="text" name="q" placeholder="Search products…"
               :class="navTransparent ? 'nav-search-transparent' : 'nav-search'"
               class="w-full pl-9 pr-4 py-2 text-sm border rounded-xl outline-none transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs transition-colors"
           :class="navTransparent ? 'text-white/50' : 'nav-search-icon'"></i>
      </div>
    </form>

    <!-- ═══ Right Actions ════════════════════════════════════════════ -->
    <div class="flex items-center gap-0.5 ml-auto flex-shrink-0">

      <!-- Theme toggle -->
      <button @click="toggleTheme()" title="Toggle theme"
              :class="navTransparent ? 'nav-icon-transparent' : 'nav-icon-btn'"
              class="p-2.5 rounded-xl transition-all duration-200">
        <i x-show="isDark"  class="fa-solid fa-sun  text-sm" x-cloak></i>
        <i x-show="!isDark" class="fa-solid fa-moon text-sm" x-cloak></i>
      </button>

      <!-- Wishlist -->
      <a href="/wishlist" title="Wishlist"
         :class="navTransparent ? 'nav-icon-transparent' : 'nav-icon-btn'"
         class="relative p-2.5 rounded-xl transition-all duration-200">
        <i class="fa-regular fa-heart text-sm"></i>
        <span x-show="$store.counts.wishlist > 0" x-text="$store.counts.wishlist" x-cloak
              class="badge-dot bg-rose-500"></span>
      </a>

      <!-- Cart -->
      <a href="/cart" title="Cart"
         :class="navTransparent ? 'nav-icon-transparent' : 'nav-icon-btn'"
         class="relative p-2.5 rounded-xl transition-all duration-200">
        <i class="fa-solid fa-bag-shopping text-sm"></i>
        <span x-show="$store.counts.cart > 0" x-text="$store.counts.cart" x-cloak
              class="badge-dot bg-blue-600"></span>
      </a>

      <!-- Notifications (logged-in only) -->
      <?php if (isLoggedIn()): ?>
      <a href="/notifications" title="Notifications"
         :class="navTransparent ? 'nav-icon-transparent' : 'nav-icon-btn'"
         class="relative p-2.5 rounded-xl transition-all duration-200">
        <i class="fa-regular fa-bell text-sm"></i>
        <?php $unread = (new NotificationModel())->unreadCount(getCurrentUserId()); ?>
        <?php if ($unread > 0): ?>
        <span class="badge-dot bg-red-500"><?= $unread ?></span>
        <?php endif; ?>
      </a>
      <?php endif; ?>

      <!-- ── User Area ──────────────────────────────────────────── -->
      <?php if (isLoggedIn()): ?>

      <div class="relative ml-1" x-data="{open:false}" @click.outside="open=false">
        <button @click="open=!open"
                :class="navTransparent ? 'nav-user-transparent' : 'nav-user-btn'"
                class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl text-sm font-medium transition-all duration-200">
          <span class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 select-none">
            <?= strtoupper(substr(clean($currentUser['name']), 0, 1)) ?>
          </span>
          <span class="hidden xl:block max-w-[90px] truncate leading-none">
            <?= clean(explode(' ', $currentUser['name'])[0]) ?>
          </span>
          <i class="fa-solid fa-chevron-down text-[9px] opacity-40 transition-transform duration-200"
             :class="open && 'rotate-180'"></i>
        </button>

        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="nav-dropdown absolute right-0 top-full mt-2 w-56 rounded-2xl overflow-hidden py-1.5 origin-top-right">

          <div class="px-4 py-3 nav-dropdown-header">
            <p class="text-sm font-semibold truncate"><?= clean($currentUser['name']) ?></p>
            <p class="text-xs opacity-55 truncate mt-0.5"><?= clean($currentUser['email']) ?></p>
          </div>

          <a href="/dashboard" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm">
            <i class="fa-solid fa-gauge-high w-4 text-center text-xs opacity-50"></i> Dashboard
          </a>
          <a href="/orders" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm">
            <i class="fa-solid fa-box w-4 text-center text-xs opacity-50"></i> My Orders
          </a>
          <a href="/wishlist" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm">
            <i class="fa-regular fa-heart w-4 text-center text-xs opacity-50"></i> Wishlist
          </a>

          <?php if (isAdmin()): ?>
          <div class="nav-dropdown-divider mt-1 pt-1">
            <a href="/admin" class="nav-dropdown-item-admin flex items-center gap-3 px-4 py-2.5 text-sm">
              <i class="fa-solid fa-shield-halved w-4 text-center text-xs"></i> Admin Panel
            </a>
          </div>
          <?php endif; ?>

          <div class="nav-dropdown-divider mt-1 pt-1">
            <a href="#" onclick="fetch('/api/auth/logout',{method:'POST'}).then(()=>location.href='/')"
               class="nav-dropdown-item-danger flex items-center gap-3 px-4 py-2.5 text-sm">
              <i class="fa-solid fa-right-from-bracket w-4 text-center text-xs"></i> Sign Out
            </a>
          </div>
        </div>
      </div>

      <?php else: ?>

      <div class="flex items-center gap-1.5 ml-1">
        <a href="/login"
           :class="navTransparent ? 'nav-btn-ghost-transparent' : 'nav-btn-ghost'"
           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
          Login
        </a>
        <a href="/register"
           :class="navTransparent
             ? 'bg-white text-blue-700 hover:bg-blue-50 shadow-lg shadow-black/25'
             : 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/25'"
           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
          Register
        </a>
      </div>

      <?php endif; ?>

      <!-- Mobile toggle -->
      <button @click="mobileOpen=!mobileOpen"
              :class="navTransparent ? 'nav-icon-transparent' : 'nav-icon-btn'"
              class="lg:hidden p-2.5 rounded-xl transition-all duration-200 ml-1">
        <i x-show="!mobileOpen" class="fa-solid fa-bars  text-base"></i>
        <i x-show="mobileOpen"  class="fa-solid fa-xmark text-base" x-cloak></i>
      </button>

    </div>
  </div>

  <!-- ═══ Mobile Menu ══════════════════════════════════════════════ -->
  <div x-show="mobileOpen" x-cloak
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       :class="navTransparent ? 'bg-slate-900/97 border-white/10' : 'nav-mobile-menu'"
       class="lg:hidden border-t px-4 pt-3 pb-4 space-y-0.5 backdrop-blur-lg">

    <a href="/products"    class="<?= str_starts_with($curPath, '/products') ? 'mobile-nav-link-active' : 'mobile-nav-link' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium">
      <i class="fa-solid fa-microchip w-4 text-center text-xs mobile-nav-icon"></i> Products
    </a>
    <a href="/diy-kits"    class="<?= str_starts_with($curPath, '/diy-kits') ? 'mobile-nav-link-active' : 'mobile-nav-link' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium">
      <i class="fa-solid fa-wrench w-4 text-center text-xs mobile-nav-icon"></i> DIY Kits
    </a>
    <a href="/3d-printing"  class="<?= str_starts_with($curPath, '/3d-printing') ? 'mobile-nav-link-active' : 'mobile-nav-link' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium">
      <i class="fa-solid fa-cube w-4 text-center text-xs mobile-nav-icon"></i> 3D Printing
    </a>
    <a href="/offers"       class="<?= str_starts_with($curPath, '/offers') ? 'mobile-nav-link-offer-active' : 'mobile-nav-link-offer' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-semibold">
      <i class="fa-solid fa-tag w-4 text-center text-xs"></i> Offers
    </a>
    <a href="/blogs"        class="<?= str_starts_with($curPath, '/blogs') ? 'mobile-nav-link-active' : 'mobile-nav-link' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium">
      <i class="fa-solid fa-newspaper w-4 text-center text-xs mobile-nav-icon"></i> Blog
    </a>
    <a href="/track-order"  class="<?= str_starts_with($curPath, '/track-order') ? 'mobile-nav-link-active' : 'mobile-nav-link' ?> flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium">
      <i class="fa-solid fa-truck w-4 text-center text-xs mobile-nav-icon"></i> Track Order
    </a>

    <!-- Mobile search -->
    <div class="pt-1 pb-1">
      <form action="/products" method="GET">
        <div class="relative">
          <input type="text" name="q" placeholder="Search products…"
                 class="nav-search w-full pl-9 pr-4 py-2.5 text-sm rounded-xl outline-none">
          <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs nav-search-icon"></i>
        </div>
      </form>
    </div>

    <!-- Mobile auth -->
    <?php if (!isLoggedIn()): ?>
    <div class="flex gap-2 pt-1 nav-mobile-auth">
      <a href="/login"    class="mobile-btn-ghost flex-1 text-center py-2.5 rounded-xl text-sm font-medium">Login</a>
      <a href="/register" class="flex-1 text-center py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold">Register</a>
    </div>
    <?php endif; ?>

  </div>
</nav>

<main>
