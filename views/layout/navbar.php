<?php
$isHome = (rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') === '');
?>
<nav id="main-nav"
     class="sticky top-0 z-50 transition-all duration-300 backdrop-blur-md"
     :class="navTransparent
       ? 'bg-transparent border-b border-transparent shadow-none'
       : 'bg-white/95 dark:bg-[hsl(222,47%,8%)]/98 border-b border-slate-200 dark:border-white/8 shadow-sm'"
     x-data="{
       mobileOpen: false,
       scrolled: false,
       isHome: <?= $isHome ? 'true' : 'false' ?>,
       get navTransparent() { return this.isHome && !this.scrolled; },
       init() {
         if (this.isHome) {
           const onScroll = () => { this.scrolled = window.scrollY > 70; };
           window.addEventListener('scroll', onScroll, { passive: true });
           onScroll();
         }
       }
     }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 gap-3">

    <!-- Logo -->
    <a href="/" class="flex items-center flex-shrink-0 gap-2 min-w-0">
      <img src="/assets/logo.png" alt="TT Electro Store"
           class="h-9 w-auto hidden sm:block transition-all duration-300"
           :style="navTransparent ? 'filter:brightness(0) invert(1)' : ''">
      <img src="/assets/logo-icon.png" alt="TT"
           class="h-8 w-auto sm:hidden transition-all duration-300"
           :style="navTransparent ? 'filter:brightness(0) invert(1)' : ''">
    </a>

    <!-- Desktop Nav Links -->
    <div class="hidden lg:flex items-center gap-0.5 text-sm flex-shrink-0">

      <a href="/products"
         :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="px-3 py-2 rounded-lg font-medium transition-all duration-200">Products</a>

      <a href="/diy-kits"
         :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="px-3 py-2 rounded-lg font-medium transition-all duration-200">DIY Kits</a>

      <a href="/3d-printing"
         :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="px-3 py-2 rounded-lg font-medium transition-all duration-200">3D Printing</a>

      <a href="/offers"
         :class="navTransparent ? 'text-amber-300 hover:text-amber-200 hover:bg-white/10' : 'text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10'"
         class="px-3 py-2 rounded-lg font-semibold transition-all duration-200 flex items-center gap-1.5">
        <i class="fa-solid fa-tag text-xs"></i> Offers
      </a>

      <a href="/blogs"
         :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="px-3 py-2 rounded-lg font-medium transition-all duration-200">Blog</a>

      <a href="/track-order"
         :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="px-3 py-2 rounded-lg font-medium transition-all duration-200">Track Order</a>
    </div>

    <!-- Search Bar -->
    <form action="/products" method="GET" class="hidden md:flex flex-1 max-w-xs lg:max-w-sm">
      <div class="relative w-full">
        <input type="text" name="q" placeholder="Search products..."
               :class="navTransparent
                 ? 'bg-white/12 border-white/20 text-white placeholder-white/50 focus:bg-white/18 focus:border-white/40'
                 : 'bg-slate-50 dark:bg-white/6 border-slate-200 dark:border-white/8 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-400 dark:focus:border-blue-500'"
               class="w-full pl-9 pr-4 py-2 text-sm border rounded-xl transition-all duration-200 outline-none">
        <i :class="navTransparent ? 'text-white/50' : 'text-slate-400 dark:text-slate-500'"
           class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs transition-colors"></i>
      </div>
    </form>

    <!-- Right Icons -->
    <div class="flex items-center gap-0.5 flex-shrink-0">

      <!-- Theme toggle -->
      <button @click="toggleTheme()"
              :class="navTransparent ? 'text-white/80 hover:text-white hover:bg-white/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
              class="p-2.5 rounded-xl transition-all duration-200" title="Toggle theme">
        <i x-show="isDark"  class="fa-solid fa-sun text-sm" x-cloak></i>
        <i x-show="!isDark" class="fa-solid fa-moon text-sm" x-cloak></i>
      </button>

      <!-- Wishlist -->
      <a href="/wishlist"
         :class="navTransparent ? 'text-white/80 hover:text-white hover:bg-white/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="relative p-2.5 rounded-xl transition-all duration-200" title="Wishlist">
        <i class="fa-regular fa-heart text-sm"></i>
        <?php
        $wCount = (new WishlistModel())->count(getCurrentUserId());
        if ($wCount > 0): ?>
        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-rose-500 rounded-full text-[10px] flex items-center justify-center text-white font-bold leading-none"><?= $wCount ?></span>
        <?php endif; ?>
      </a>

      <!-- Cart -->
      <a href="/cart"
         :class="navTransparent ? 'text-white/80 hover:text-white hover:bg-white/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="relative p-2.5 rounded-xl transition-all duration-200" title="Cart">
        <i class="fa-solid fa-bag-shopping text-sm"></i>
        <?php
        $cartCount = (new CartModel())->count(getCurrentUserId());
        if ($cartCount > 0): ?>
        <span id="cartBadge" class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-blue-600 rounded-full text-[10px] flex items-center justify-center text-white font-bold leading-none"><?= $cartCount ?></span>
        <?php endif; ?>
      </a>

      <!-- Notifications -->
      <?php if (isLoggedIn()): ?>
      <a href="/notifications"
         :class="navTransparent ? 'text-white/80 hover:text-white hover:bg-white/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/8'"
         class="relative p-2.5 rounded-xl transition-all duration-200" title="Notifications">
        <i class="fa-regular fa-bell text-sm"></i>
        <?php
        $unread = (new NotificationModel())->unreadCount(getCurrentUserId());
        if ($unread > 0): ?>
        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 rounded-full text-[10px] flex items-center justify-center text-white font-bold leading-none"><?= $unread ?></span>
        <?php endif; ?>
      </a>
      <?php endif; ?>

      <!-- User dropdown -->
      <?php if (isLoggedIn()): ?>
      <div class="relative" x-data="{open:false}" @click.outside="open=false">
        <button @click="open=!open"
                :class="navTransparent
                  ? 'bg-white/12 hover:bg-white/20 text-white border border-white/20'
                  : 'bg-slate-100 dark:bg-white/8 hover:bg-slate-200 dark:hover:bg-white/12 text-slate-700 dark:text-slate-300 border border-transparent'"
                class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl text-sm font-medium transition-all duration-200">
          <span class="w-6 h-6 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            <?= strtoupper(substr(clean($currentUser['name']), 0, 1)) ?>
          </span>
          <span class="hidden lg:block max-w-[80px] truncate"><?= clean($currentUser['name']) ?></span>
          <i class="fa-solid fa-chevron-down text-[9px] opacity-50 transition-transform duration-200" :class="open && 'rotate-180'"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute right-0 top-full mt-2 w-52 bg-white dark:bg-[hsl(222,47%,12%)] border border-slate-200 dark:border-white/8 rounded-2xl shadow-xl dark:shadow-black/40 overflow-hidden py-1.5">
          <div class="px-4 py-3 border-b border-slate-100 dark:border-white/8">
            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate"><?= clean($currentUser['name']) ?></p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5"><?= clean($currentUser['email']) ?></p>
          </div>
          <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <i class="fa-solid fa-gauge-high w-4 text-center text-slate-400"></i> Dashboard
          </a>
          <a href="/orders" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <i class="fa-solid fa-box w-4 text-center text-slate-400"></i> My Orders
          </a>
          <?php if (isAdmin()): ?>
          <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-600 dark:text-blue-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <i class="fa-solid fa-shield-halved w-4 text-center"></i> Admin Panel
          </a>
          <?php endif; ?>
          <div class="border-t border-slate-100 dark:border-white/8 mt-1 pt-1">
            <a href="#" onclick="fetch('/api/auth/logout',{method:'POST'}).then(()=>location.href='/')"
               class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/8 transition-colors">
              <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Sign Out
            </a>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="flex items-center gap-1.5 ml-1">
        <a href="/login"
           :class="navTransparent ? 'text-white/90 hover:text-white hover:bg-white/10' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/8'"
           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200">Login</a>
        <a href="/register"
           :class="navTransparent ? 'bg-white text-blue-700 hover:bg-blue-50 shadow-lg shadow-black/20' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/20'"
           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200">Register</a>
      </div>
      <?php endif; ?>

      <!-- Mobile menu toggle -->
      <button @click="mobileOpen=!mobileOpen"
              :class="navTransparent ? 'text-white/80 hover:text-white hover:bg-white/10' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/8'"
              class="lg:hidden p-2.5 rounded-xl transition-all duration-200 ml-0.5">
        <i x-show="!mobileOpen" class="fa-solid fa-bars text-base"></i>
        <i x-show="mobileOpen"  class="fa-solid fa-xmark text-base" x-cloak></i>
      </button>
    </div>
  </div>

  <!-- Mobile menu -->
  <div x-show="mobileOpen"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       :class="navTransparent
         ? 'bg-slate-900/95 border-white/10'
         : 'bg-white dark:bg-[hsl(222,47%,8%)] border-slate-200 dark:border-white/8'"
       class="lg:hidden border-t px-4 py-3 space-y-0.5 backdrop-blur-lg"
       x-cloak>
    <a href="/products"    class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all"><i class="fa-solid fa-microchip w-4 text-center text-slate-400 dark:text-slate-500 text-xs"></i> Products</a>
    <a href="/diy-kits"    class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all"><i class="fa-solid fa-wrench w-4 text-center text-slate-400 dark:text-slate-500 text-xs"></i> DIY Kits</a>
    <a href="/3d-printing" class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all"><i class="fa-solid fa-cube w-4 text-center text-slate-400 dark:text-slate-500 text-xs"></i> 3D Printing</a>
    <a href="/offers"      class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10 text-sm font-semibold transition-all"><i class="fa-solid fa-tag w-4 text-center text-xs"></i> Offers</a>
    <a href="/blogs"       class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all"><i class="fa-solid fa-newspaper w-4 text-center text-slate-400 dark:text-slate-500 text-xs"></i> Blog</a>
    <a href="/track-order" class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all"><i class="fa-solid fa-truck w-4 text-center text-slate-400 dark:text-slate-500 text-xs"></i> Track Order</a>
    <?php if (!isLoggedIn()): ?>
    <div class="flex gap-2 pt-2 pb-1 border-t border-slate-100 dark:border-white/8 mt-2">
      <a href="/login"    class="flex-1 text-center py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 text-sm font-medium transition-all hover:bg-slate-50 dark:hover:bg-white/5">Login</a>
      <a href="/register" class="flex-1 text-center py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-all">Register</a>
    </div>
    <?php endif; ?>
    <div class="pt-2">
      <form action="/products" method="GET">
        <div class="relative">
          <input type="text" name="q" placeholder="Search products..."
                 class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 dark:border-white/8 bg-slate-50 dark:bg-white/6 text-slate-900 dark:text-white placeholder-slate-400 rounded-xl outline-none focus:border-blue-400 transition-all">
          <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        </div>
      </form>
    </div>
  </div>
</nav>

<!-- Main content wrapper -->
<main>
