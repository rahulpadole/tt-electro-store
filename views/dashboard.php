<?php
$pageTitle = 'My Dashboard';
$user = getCurrentUser();
$um   = new UserModel();
$hasPassword = $um->hasPassword((int)$user['id']);
?>
<script>window.__DASH_USER__ = <?= json_encode([
  'id'             => $user['id'],
  'name'           => $user['name'],
  'email'          => $user['email'],
  'phone'          => $user['phone'] ?? '',
  'phone_verified' => (int)($user['phone_verified'] ?? 0),
  'avatar'         => $user['avatar'] ?? '',
  'google_avatar'  => $user['google_avatar'] ?? '',
  'role'           => $user['role'],
  'created_at'     => $user['created_at'],
  'has_password'   => $hasPassword,
]) ?>;</script>

<div x-data="userDash()" x-init="init()" class="flex min-h-[calc(100vh-4rem)]">

  <!-- ── Sidebar (desktop) ─────────────────────────────────────────────── -->
  <aside class="hidden md:flex flex-col w-56 flex-shrink-0 bg-slate-50 dark:bg-[hsl(222,47%,8%)] border-r border-slate-200 dark:border-white/6 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto">
    <!-- User mini-card -->
    <div class="p-4 border-b border-slate-200 dark:border-white/6">
      <div class="flex items-center gap-3">
        <template x-if="user.google_avatar">
          <img :src="user.google_avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30" alt="">
        </template>
        <template x-if="!user.google_avatar">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-base flex-shrink-0"
               x-text="user.name?.charAt(0).toUpperCase()"></div>
        </template>
        <div class="min-w-0">
          <p class="text-slate-900 dark:text-white text-sm font-semibold truncate" x-text="user.name?.split(' ')[0]"></p>
          <p class="text-slate-400 text-xs truncate" x-text="user.email"></p>
        </div>
      </div>
    </div>

    <!-- Nav items -->
    <nav class="flex-1 py-3 px-2 space-y-0.5">
      <?php $navItems = [
        ['overview',       'Overview',       'fa-gauge-high'],
        ['orders',         'My Orders',      'fa-clipboard-list'],
        ['profile',        'My Profile',     'fa-user'],
        ['security',       'Security',       'fa-shield-halved'],
        ['wishlist',       'Wishlist',       'fa-heart'],
        ['notifications',  'Notifications',  'fa-bell'],
        ['settings',       'Settings',       'fa-sliders'],
        ['support',        'Help & Support', 'fa-headset'],
      ]; ?>
      <?php foreach($navItems as [$key,$label,$icon]): ?>
      <button @click="switchTab('<?= $key ?>')"
              :class="tab==='<?= $key ?>'?'bg-blue-600/10 text-blue-600 dark:text-blue-400 border-blue-500/20':'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5'"
              class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all border border-transparent">
        <i class="fa-solid <?= $icon ?> w-4 text-center text-sm flex-shrink-0"></i>
        <span><?= $label ?></span>
        <?php if($key === 'notifications'): ?>
        <span x-show="unreadCount>0" x-text="unreadCount" x-cloak
              class="ml-auto bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
        <?php endif; ?>
      </button>
      <?php endforeach; ?>
    </nav>

    <!-- Logout -->
    <div class="p-3 border-t border-slate-200 dark:border-white/6">
      <a href="/api/auth/logout" onclick="fetch('/api/auth/logout',{method:'POST',credentials:'same-origin'}).then(()=>window.location='/login');return false"
         class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-500/8 hover:text-red-600 transition-all">
        <i class="fa-solid fa-right-from-bracket w-4 text-center text-sm"></i>
        <span>Sign Out</span>
      </a>
    </div>
  </aside>

  <!-- ── Mobile Tab Bar ────────────────────────────────────────────────── -->
  <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-[hsl(222,47%,8%)] border-t border-slate-200 dark:border-white/8 flex overflow-x-auto scrollbar-hide">
    <?php $mobileTabs = [
      ['overview','Home','fa-house'],
      ['orders','Orders','fa-clipboard-list'],
      ['profile','Profile','fa-user'],
      ['security','Security','fa-shield-halved'],
      ['wishlist','Wishlist','fa-heart'],
      ['notifications','Alerts','fa-bell'],
      ['settings','Settings','fa-sliders'],
      ['support','Support','fa-headset'],
    ]; ?>
    <?php foreach($mobileTabs as [$key,$label,$icon]): ?>
    <button @click="switchTab('<?= $key ?>')"
            :class="tab==='<?= $key ?>'?'text-blue-600 dark:text-blue-400':'text-slate-400 dark:text-slate-500'"
            class="flex flex-col items-center gap-0.5 px-3 py-2 min-w-[4rem] flex-shrink-0 transition-colors relative">
      <i class="fa-solid <?= $icon ?> text-base"></i>
      <span class="text-[9px] font-medium"><?= $label ?></span>
      <?php if($key==='notifications'): ?>
      <span x-show="unreadCount>0" x-cloak class="absolute top-1 right-2 bg-red-500 text-white text-[8px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center" x-text="unreadCount"></span>
      <?php endif; ?>
    </button>
    <?php endforeach; ?>
  </div>

  <!-- ── Main Content ───────────────────────────────────────────────────── -->
  <main class="flex-1 overflow-y-auto pb-24 md:pb-8">
    <div class="max-w-3xl mx-auto px-4 py-6">

      <!-- ══ OVERVIEW ══════════════════════════════════════════════════════ -->
      <section x-show="tab==='overview'" x-transition>
        <!-- Welcome banner -->
        <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 p-6 mb-6 text-white relative overflow-hidden">
          <div class="absolute right-0 top-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl pointer-events-none"></div>
          <div class="flex items-center gap-4 relative">
            <template x-if="user.google_avatar">
              <img :src="user.google_avatar" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/30" alt="">
            </template>
            <template x-if="!user.google_avatar">
              <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white font-bold text-2xl"
                   x-text="user.name?.charAt(0).toUpperCase()"></div>
            </template>
            <div>
              <h1 class="text-xl font-bold">Hello, <span x-text="user.name?.split(' ')[0]"></span>! 👋</h1>
              <p class="text-blue-100 text-sm mt-0.5" x-text="'Member since ' + new Date(user.created_at).toLocaleDateString('en-IN',{month:'long',year:'numeric'})"></p>
              <div class="flex items-center gap-2 mt-1.5">
                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-medium">
                  <i class="fa-solid fa-store text-blue-200 text-[10px] mr-1"></i>TT Electro Member
                </span>
                <span x-show="user.phone_verified" class="bg-green-500/30 text-white text-xs px-2 py-0.5 rounded-full font-medium" x-cloak>
                  <i class="fa-solid fa-check text-[10px] mr-1"></i>Verified
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
          <?php $statCards = [
            ['orders','Total Orders','fa-box','text-blue-500','bg-blue-500/10'],
            ['spent','Total Spent','fa-indian-rupee-sign','text-green-500','bg-green-500/10'],
            ['wishlist','Wishlist Items','fa-heart','text-pink-500','bg-pink-500/10'],
            ['reviews','Reviews','fa-star','text-amber-500','bg-amber-500/10'],
          ]; ?>
          <?php foreach($statCards as [$key,$label,$icon,$textColor,$bgColor]): ?>
          <div class="rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
              <span class="text-slate-500 dark:text-slate-400 text-xs font-medium"><?= $label ?></span>
              <span class="w-8 h-8 rounded-lg <?= $bgColor ?> flex items-center justify-center flex-shrink-0">
                <i class="fa-solid <?= $icon ?> <?= $textColor ?> text-sm"></i>
              </span>
            </div>
            <p class="text-xl font-bold text-slate-900 dark:text-white">
              <?php if($key==='spent'): ?>
              <span>₹<span x-text="stats.spent.toLocaleString('en-IN',{maximumFractionDigits:0})"></span></span>
              <?php else: ?>
              <span x-text="stats.<?= $key ?>"></span>
              <?php endif; ?>
            </p>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-4 gap-3 mb-6">
          <?php $quickActions = [
            ['/products','fa-shop','Shop','text-blue-500','bg-blue-500/10'],
            ['orders','fa-clipboard-list','My Orders','text-purple-500','bg-purple-500/10'],
            ['/track-order','fa-truck-fast','Track','text-orange-500','bg-orange-500/10'],
            ['wishlist','fa-heart','Wishlist','text-pink-500','bg-pink-500/10'],
          ]; ?>
          <?php foreach($quickActions as [$href,$icon,$label,$textColor,$bgColor]): ?>
          <?php if(strpos($href,'/')===0): ?>
          <a href="<?= $href ?>" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-400/30 shadow-sm transition-all group">
            <span class="w-10 h-10 rounded-xl <?= $bgColor ?> flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="fa-solid <?= $icon ?> <?= $textColor ?> text-base"></i>
            </span>
            <span class="text-xs font-medium text-slate-600 dark:text-slate-300"><?= $label ?></span>
          </a>
          <?php else: ?>
          <button @click="switchTab('<?= $href ?>')" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-400/30 shadow-sm transition-all group">
            <span class="w-10 h-10 rounded-xl <?= $bgColor ?> flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="fa-solid <?= $icon ?> <?= $textColor ?> text-base"></i>
            </span>
            <span class="text-xs font-medium text-slate-600 dark:text-slate-300"><?= $label ?></span>
          </button>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <!-- Recent Orders -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/6">
            <h2 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
              <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs"></i> Recent Orders
            </h2>
            <button @click="switchTab('orders')" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">View All →</button>
          </div>
          <div x-show="!overviewLoaded" class="flex items-center justify-center py-10">
            <span class="w-6 h-6 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"></span>
          </div>
          <template x-if="overviewLoaded && recentOrders.length===0">
            <div class="text-center py-12">
              <i class="fa-solid fa-box-open text-slate-300 dark:text-slate-600 text-4xl mb-3"></i>
              <p class="text-slate-500 dark:text-slate-400 text-sm">No orders yet.</p>
              <a href="/products" class="text-blue-600 dark:text-blue-400 text-sm hover:underline mt-1 inline-block">Start shopping →</a>
            </div>
          </template>
          <template x-if="overviewLoaded && recentOrders.length>0">
            <div class="divide-y divide-slate-100 dark:divide-white/5">
              <template x-for="o in recentOrders" :key="o.id">
                <a :href="'/orders/'+o.id" class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors group">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-white/8 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-box text-slate-400 text-sm"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-slate-900 dark:text-white text-sm font-semibold truncate" x-text="o.order_number"></p>
                    <p class="text-slate-400 text-xs mt-0.5" x-text="new Date(o.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'})"></p>
                  </div>
                  <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                          :class="{
                            'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400': o.status==='pending',
                            'bg-blue-500/10 text-blue-600 dark:text-blue-400': o.status==='processing',
                            'bg-purple-500/10 text-purple-600 dark:text-purple-400': o.status==='shipped',
                            'bg-green-500/10 text-green-600 dark:text-green-400': o.status==='delivered',
                            'bg-red-500/10 text-red-600 dark:text-red-400': o.status==='cancelled',
                          }" x-text="o.status"></span>
                    <span class="text-slate-900 dark:text-white font-bold text-sm" x-text="'₹'+parseFloat(o.total).toLocaleString('en-IN',{maximumFractionDigits:0})"></span>
                    <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs group-hover:text-blue-500 transition-colors"></i>
                  </div>
                </a>
              </template>
            </div>
          </template>
        </div>
      </section>

      <!-- ══ ORDERS ════════════════════════════════════════════════════════ -->
      <section x-show="tab==='orders'" x-cloak x-transition>
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-bold text-slate-900 dark:text-white">My Orders</h2>
          <a href="/track-order" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
            <i class="fa-solid fa-truck-fast text-xs mr-1"></i>Track Order
          </a>
        </div>

        <!-- Filter chips -->
        <div class="flex gap-2 overflow-x-auto pb-2 mb-4 scrollbar-hide">
          <?php foreach(['all','pending','processing','shipped','delivered','cancelled'] as $status): ?>
          <button @click="orderFilter='<?= $status ?>'"
                  :class="orderFilter==='<?= $status ?>'?'bg-blue-600 text-white border-blue-600':'bg-white dark:bg-[hsl(222,47%,10%)] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-blue-400/50'"
                  class="px-4 py-1.5 rounded-full text-xs font-medium border capitalize flex-shrink-0 transition-all">
            <?= $status === 'all' ? 'All Orders' : ucfirst($status) ?>
          </button>
          <?php endforeach; ?>
        </div>

        <!-- Loading -->
        <div x-show="!orders" class="flex items-center justify-center py-16">
          <span class="w-8 h-8 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"></span>
        </div>

        <!-- Empty -->
        <template x-if="orders && filteredOrders.length===0">
          <div class="text-center py-16 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6">
            <i class="fa-solid fa-clipboard text-slate-300 dark:text-slate-600 text-5xl mb-4"></i>
            <p class="text-slate-500 dark:text-slate-400 font-medium mb-1">No orders found</p>
            <p class="text-slate-400 dark:text-slate-500 text-sm" x-text="orderFilter!=='all'?'Try changing the filter above':''"></p>
          </div>
        </template>

        <!-- Order list -->
        <div class="space-y-3">
          <template x-for="o in filteredOrders" :key="o.id">
            <a :href="'/orders/'+o.id" class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:border-blue-400/30 hover:shadow-md transition-all group">
              <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500/10 to-cyan-500/10 dark:from-blue-500/20 dark:to-cyan-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-box text-blue-500 text-base"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-slate-900 dark:text-white font-semibold text-sm" x-text="o.order_number"></p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="text-slate-400 text-xs" x-text="new Date(o.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'})"></span>
                  <span class="text-slate-300 dark:text-slate-600 text-xs">·</span>
                  <span class="text-slate-400 text-xs" x-text="(o.items_count||1)+' item'+(o.items_count>1?'s':'')"></span>
                </div>
              </div>
              <div class="text-right flex-shrink-0">
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium capitalize mb-1"
                      :class="{
                        'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400': o.status==='pending',
                        'bg-blue-500/10 text-blue-600 dark:text-blue-400': o.status==='processing',
                        'bg-purple-500/10 text-purple-600 dark:text-purple-400': o.status==='shipped',
                        'bg-green-500/10 text-green-600 dark:text-green-400': o.status==='delivered',
                        'bg-red-500/10 text-red-600 dark:text-red-400': o.status==='cancelled',
                      }" x-text="o.status"></span>
                <p class="text-slate-900 dark:text-white font-bold text-sm" x-text="'₹'+parseFloat(o.total).toLocaleString('en-IN',{maximumFractionDigits:0})"></p>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs ml-1 group-hover:text-blue-500 transition-colors flex-shrink-0"></i>
            </a>
          </template>
        </div>
      </section>

      <!-- ══ PROFILE ══════════════════════════════════════════════════════ -->
      <section x-show="tab==='profile'" x-cloak x-transition>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-5">My Profile</h2>

        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm overflow-hidden">
          <!-- Avatar header -->
          <div class="bg-gradient-to-r from-blue-600/10 to-cyan-600/10 dark:from-blue-600/20 dark:to-cyan-600/20 px-6 py-6 flex items-center gap-5 border-b border-slate-100 dark:border-white/6">
            <template x-if="user.google_avatar">
              <img :src="user.google_avatar" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white dark:ring-white/10 shadow-lg" alt="">
            </template>
            <template x-if="!user.google_avatar">
              <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-3xl ring-4 ring-white dark:ring-white/10 shadow-lg"
                   x-text="user.name?.charAt(0).toUpperCase()"></div>
            </template>
            <div>
              <h3 class="text-slate-900 dark:text-white font-bold text-lg" x-text="user.name"></h3>
              <p class="text-slate-500 dark:text-slate-400 text-sm" x-text="user.email"></p>
              <div class="flex flex-wrap gap-2 mt-2">
                <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-medium">
                  <i class="fa-solid fa-user-check text-[10px]"></i> <?= ucfirst($user['role']) ?>
                </span>
                <span x-show="user.phone_verified" class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-green-500/10 text-green-600 dark:text-green-400 font-medium" x-cloak>
                  <i class="fa-solid fa-mobile-screen-button text-[10px]"></i> Mobile Verified
                </span>
                <span x-show="user.google_avatar" class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/8 text-slate-500 dark:text-slate-400 font-medium" x-cloak>
                  <i class="fa-brands fa-google text-[10px]"></i> Google Account
                </span>
              </div>
            </div>
          </div>

          <!-- Form -->
          <div class="p-6 space-y-5">
            <!-- Name -->
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Full Name</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-regular fa-user text-sm"></i></span>
                <input type="text" x-model="pf.name" placeholder="Your full name"
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/15 transition-all">
              </div>
            </div>

            <!-- Email (readonly) -->
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Email Address</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-regular fa-envelope text-sm"></i></span>
                <input type="email" :value="user.email" readonly
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,11%)] border border-slate-200 dark:border-white/6 text-slate-500 dark:text-slate-500 text-sm cursor-not-allowed">
                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 bg-slate-200 dark:bg-white/8 px-1.5 py-0.5 rounded">Read-only</span>
              </div>
            </div>

            <!-- Current Phone -->
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                Mobile Number
                <span x-show="user.phone_verified" x-cloak class="ml-1 text-green-500 normal-case font-normal">
                  <i class="fa-solid fa-circle-check text-xs"></i> Verified
                </span>
              </label>
              <div class="flex gap-2">
                <div class="relative flex-1">
                  <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-mobile-screen text-sm"></i></span>
                  <input type="text" :value="pf.phone||'Not added'" readonly
                         class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-100 dark:bg-[hsl(222,47%,11%)] border border-slate-200 dark:border-white/6 text-slate-500 dark:text-slate-400 text-sm cursor-default">
                </div>
                <button @click="ot.show=!ot.show" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[hsl(222,47%,13%)] text-slate-600 dark:text-slate-300 text-sm font-medium hover:border-blue-500/40 transition-all flex-shrink-0">
                  <i class="fa-solid fa-pen text-xs mr-1"></i> Change
                </button>
              </div>
            </div>

            <!-- Change phone OTP section -->
            <div x-show="ot.show" x-cloak x-transition class="rounded-xl border border-blue-500/20 bg-blue-500/5 dark:bg-blue-500/10 p-4 space-y-3">
              <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-blue-500 text-xs"></i> Change Mobile Number
              </p>
              <div x-show="!ot.sent">
                <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5">New Mobile Number</label>
                <div class="flex gap-2">
                  <div class="flex items-center px-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[hsl(222,47%,13%)] text-slate-600 dark:text-slate-300 text-sm font-medium flex-shrink-0">
                    🇮🇳 +91
                  </div>
                  <input type="tel" x-model="ot.newPhone" @input="ot.newPhone=ot.newPhone.replace(/\D/g,'').slice(0,10)"
                         placeholder="9876543210" maxlength="10"
                         class="flex-1 px-3 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                  <button @click="otSendOtp()" :disabled="ot.sending||ot.newPhone.length<10" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-all disabled:opacity-50 flex-shrink-0">
                    <span x-show="!ot.sending">Send OTP</span>
                    <span x-show="ot.sending" x-cloak class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin inline-block"></span>
                  </button>
                </div>
              </div>
              <div x-show="ot.sent && !ot.verified" x-cloak>
                <div x-show="ot.devOtp" x-cloak class="mb-3 px-3 py-2 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs">
                  <i class="fa-solid fa-triangle-exclamation mr-1"></i> Dev OTP: <strong x-text="ot.devOtp"></strong>
                </div>
                <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5">Enter OTP</label>
                <div class="flex gap-2">
                  <input type="text" x-model="ot.code" @input="ot.code=ot.code.replace(/\D/g,'').slice(0,6)"
                         placeholder="6-digit OTP" maxlength="6"
                         class="flex-1 px-3 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 transition-all text-center font-bold tracking-widest">
                  <button @click="otVerify()" :disabled="ot.verifying||ot.code.length<4" class="px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-medium transition-all disabled:opacity-50 flex-shrink-0">
                    <span x-show="!ot.verifying">Verify</span>
                    <span x-show="ot.verifying" x-cloak class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin inline-block"></span>
                  </button>
                </div>
                <div class="flex justify-between mt-2 text-xs">
                  <button @click="ot.sent=false" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">← Change number</button>
                  <button @click="otSendOtp()" :disabled="ot.timer>0" :class="ot.timer>0?'text-slate-400 cursor-not-allowed':'text-blue-600 dark:text-blue-400 hover:underline'">
                    <span x-show="ot.timer>0">Resend in <span x-text="ot.timer"></span>s</span>
                    <span x-show="!ot.timer">Resend OTP</span>
                  </button>
                </div>
              </div>
              <div x-show="ot.verified" x-cloak class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-medium">
                <i class="fa-solid fa-circle-check"></i> Phone number updated &amp; verified!
              </div>
              <p x-show="ot.err" x-text="ot.err" x-cloak class="text-red-500 text-xs"></p>
            </div>

            <!-- Success/Error -->
            <div x-show="pf.msg" x-cloak class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-medium">
              <i class="fa-solid fa-circle-check text-sm"></i> <span x-text="pf.msg"></span>
            </div>
            <p x-show="pf.err" x-text="pf.err" x-cloak class="text-red-500 dark:text-red-400 text-sm"></p>

            <button @click="saveProfile()" :disabled="pf.loading"
                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm transition-all disabled:opacity-50 flex items-center justify-center gap-2">
              <span x-show="!pf.loading">Save Changes</span>
              <span x-show="pf.loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              <i x-show="!pf.loading" class="fa-solid fa-floppy-disk text-sm"></i>
            </button>
          </div>
        </div>
      </section>

      <!-- ══ SECURITY (CHANGE PASSWORD) ══════════════════════════════════ -->
      <section x-show="tab==='security'" x-cloak x-transition>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-5">Security</h2>

        <!-- Change Password Card -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm mb-4">
          <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-white/6">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center">
              <i class="fa-solid fa-lock text-blue-500 text-base"></i>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 dark:text-white text-sm">Change Password</h3>
              <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Keep your account secure with a strong password</p>
            </div>
          </div>

          <div class="p-6 space-y-4">
            <?php if(!$hasPassword): ?>
            <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-500/8 border border-blue-500/15 text-sm text-slate-600 dark:text-slate-300">
              <i class="fa-brands fa-google text-blue-500 mt-0.5 flex-shrink-0"></i>
              <p>You signed in with Google, so no password is set. Enter a new password below to also enable email/password login.</p>
            </div>
            <?php endif; ?>

            <!-- Current Password (only if has password) -->
            <?php if($hasPassword): ?>
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Current Password</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span>
                <input :type="cp.showCurrent?'text':'password'" x-model="cp.current"
                       placeholder="Enter current password"
                       class="w-full pl-10 pr-12 py-3 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                <button type="button" @click="cp.showCurrent=!cp.showCurrent" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                  <i :class="cp.showCurrent?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
                </button>
              </div>
            </div>
            <?php endif; ?>

            <!-- New Password -->
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">New Password</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-key text-sm"></i></span>
                <input :type="cp.showNew?'text':'password'" x-model="cp.newPw" @input="cpCalcStrength()"
                       placeholder="Min. 8 characters"
                       class="w-full pl-10 pr-12 py-3 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                <button type="button" @click="cp.showNew=!cp.showNew" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                  <i :class="cp.showNew?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
                </button>
              </div>
              <!-- Strength -->
              <div x-show="cp.newPw.length>0" x-cloak class="mt-2">
                <div class="flex gap-1 mb-1">
                  <template x-for="i in 4" :key="i">
                    <div class="flex-1 h-1.5 rounded-full transition-all"
                         :class="i<=cp.strength ? (cp.strength<2?'bg-red-500':cp.strength===2?'bg-amber-500':'bg-green-500') : 'bg-slate-200 dark:bg-white/10'"></div>
                  </template>
                </div>
                <p class="text-xs transition-colors"
                   :class="{'text-red-500':cp.strength<2,'text-amber-500':cp.strength===2,'text-green-500':cp.strength>=3}"
                   x-text="['','Weak — add uppercase, numbers, symbols','Fair — getting there!','Strong','Very Strong ✓'][cp.strength]||''"></p>
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Confirm New Password</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock-open text-sm"></i></span>
                <input :type="cp.showConfirm?'text':'password'" x-model="cp.confirm"
                       placeholder="Re-enter new password" @keydown.enter="changePassword()"
                       class="w-full pl-10 pr-12 py-3 rounded-xl bg-slate-50 dark:bg-[hsl(222,47%,13%)] border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                <button type="button" @click="cp.showConfirm=!cp.showConfirm" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                  <i :class="cp.showConfirm?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
                </button>
              </div>
              <p x-show="cp.confirm.length>0 && cp.newPw!==cp.confirm" x-cloak class="text-red-500 text-xs mt-1">Passwords do not match</p>
            </div>

            <div x-show="cp.msg" x-cloak class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-medium py-1">
              <i class="fa-solid fa-circle-check"></i> <span x-text="cp.msg"></span>
            </div>
            <p x-show="cp.err" x-text="cp.err" x-cloak class="text-red-500 dark:text-red-400 text-sm"></p>

            <button @click="changePassword()" :disabled="cp.loading || cp.newPw!==cp.confirm || cp.newPw.length<8"
                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm transition-all disabled:opacity-50 flex items-center justify-center gap-2">
              <span x-show="!cp.loading">Update Password</span>
              <span x-show="cp.loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              <i x-show="!cp.loading" class="fa-solid fa-shield-halved text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Account Security Info -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm p-5">
          <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-500 text-base"></i> Security Tips
          </h3>
          <div class="space-y-3">
            <?php foreach([
              ['fa-check-circle','text-green-500','Use at least 8 characters with uppercase, numbers & symbols'],
              ['fa-check-circle','text-green-500','Never share your password with anyone'],
              ['fa-check-circle','text-green-500','Use a unique password not used on other sites'],
              ['fa-circle-info','text-blue-500','Contact +91 7721892429 if you suspect unauthorized access'],
            ] as [$icon,$color,$tip]): ?>
            <div class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-300">
              <i class="fa-solid <?= $icon ?> <?= $color ?> text-sm mt-0.5 flex-shrink-0"></i>
              <span><?= $tip ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ══ WISHLIST ══════════════════════════════════════════════════════ -->
      <section x-show="tab==='wishlist'" x-cloak x-transition>
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-bold text-slate-900 dark:text-white">My Wishlist</h2>
          <a href="/products" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">Browse Products →</a>
        </div>

        <div x-show="!wishlist" class="flex items-center justify-center py-16">
          <span class="w-8 h-8 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"></span>
        </div>

        <template x-if="wishlist && wishlist.length===0">
          <div class="text-center py-16 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6">
            <i class="fa-regular fa-heart text-slate-300 dark:text-slate-600 text-5xl mb-4"></i>
            <p class="text-slate-500 dark:text-slate-400 font-medium mb-1">Your wishlist is empty</p>
            <a href="/products" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">Explore products →</a>
          </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <template x-for="item in (wishlist||[])" :key="item.id">
            <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm overflow-hidden hover:shadow-md transition-all">
              <a :href="'/products/'+item.product_id" class="block">
                <div class="h-40 bg-slate-50 dark:bg-white/5 overflow-hidden">
                  <img :src="item.thumbnail||'https://picsum.photos/seed/product/400/300'" :alt="item.product_name"
                       class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
              </a>
              <div class="p-4">
                <a :href="'/products/'+item.product_id">
                  <h3 class="text-slate-900 dark:text-white font-semibold text-sm leading-snug mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors line-clamp-2" x-text="item.product_name"></h3>
                </a>
                <div class="flex items-center justify-between mb-3">
                  <span class="text-blue-600 dark:text-blue-400 font-bold" x-text="'₹'+parseFloat(item.price||0).toLocaleString('en-IN',{maximumFractionDigits:0})"></span>
                  <span x-show="item.original_price>item.price" x-cloak class="text-xs text-green-600 dark:text-green-400 font-medium bg-green-500/10 px-2 py-0.5 rounded-full"
                        x-text="Math.round((1-item.price/item.original_price)*100)+'% off'"></span>
                </div>
                <div class="flex gap-2">
                  <button @click="addToCartFromWishlist(item)"
                          class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-cart-plus text-xs"></i> Add to Cart
                  </button>
                  <button @click="removeWishlist(item.product_id)"
                          class="w-9 h-9 rounded-xl border border-red-200 dark:border-red-500/20 bg-red-500/5 hover:bg-red-500/15 text-red-500 flex items-center justify-center transition-all flex-shrink-0">
                    <i class="fa-regular fa-trash-can text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </section>

      <!-- ══ NOTIFICATIONS ════════════════════════════════════════════════ -->
      <section x-show="tab==='notifications'" x-cloak x-transition>
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-bold text-slate-900 dark:text-white">Notifications</h2>
          <button @click="markAllRead()" x-show="notifications && notifications.some(n=>!n.is_read)" x-cloak
                  class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">
            <i class="fa-solid fa-check-double text-xs mr-1"></i>Mark all read
          </button>
        </div>

        <div x-show="!notifications" class="flex items-center justify-center py-16">
          <span class="w-8 h-8 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"></span>
        </div>

        <template x-if="notifications && notifications.length===0">
          <div class="text-center py-16 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6">
            <i class="fa-regular fa-bell text-slate-300 dark:text-slate-600 text-5xl mb-4"></i>
            <p class="text-slate-500 dark:text-slate-400 font-medium">No notifications yet</p>
          </div>
        </template>

        <div class="space-y-2">
          <template x-for="n in (notifications||[])" :key="n.id">
            <div class="flex gap-4 p-4 rounded-2xl border transition-all"
                 :class="n.is_read?'bg-white dark:bg-[hsl(222,47%,10%)] border-slate-200 dark:border-white/6':'bg-blue-500/5 dark:bg-blue-500/10 border-blue-500/15 dark:border-blue-500/20'">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                   :class="n.type==='success'?'bg-green-500/10 text-green-500':n.type==='warning'?'bg-amber-500/10 text-amber-500':n.type==='error'?'bg-red-500/10 text-red-500':'bg-blue-500/10 text-blue-500'">
                <i class="fa-solid text-base"
                   :class="n.type==='success'?'fa-check':n.type==='warning'?'fa-triangle-exclamation':n.type==='error'?'fa-circle-xmark':'fa-bell'"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <p class="text-slate-900 dark:text-white font-semibold text-sm" x-text="n.title"></p>
                  <div class="flex items-center gap-2 flex-shrink-0">
                    <span x-show="!n.is_read" class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                    <span class="text-xs text-slate-400" x-text="new Date(n.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'short'})"></span>
                  </div>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 leading-relaxed" x-text="n.message"></p>
              </div>
            </div>
          </template>
        </div>
      </section>

      <!-- ══ SETTINGS ══════════════════════════════════════════════════════ -->
      <section x-show="tab==='settings'" x-cloak x-transition>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-5">Settings</h2>

        <!-- Theme -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm mb-4">
          <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 dark:border-white/6">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center">
              <i class="fa-solid fa-palette text-blue-500 text-base"></i>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 dark:text-white text-sm">Appearance</h3>
              <p class="text-slate-400 text-xs mt-0.5">Choose your preferred theme</p>
            </div>
          </div>
          <div class="p-5 grid grid-cols-3 gap-3">
            <?php foreach([
              ['system','System Default','fa-circle-half-stroke'],
              ['light','Light','fa-sun'],
              ['dark','Dark','fa-moon'],
            ] as [$val,$label,$icon]): ?>
            <button @click="setTheme('<?= $val ?>')"
                    :class="theme==='<?= $val ?>'?'border-blue-500 bg-blue-500/8 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400':'border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:border-blue-400/50'"
                    class="flex flex-col items-center gap-2 px-3 py-4 rounded-xl border transition-all">
              <i class="fa-solid <?= $icon ?> text-xl"></i>
              <span class="text-xs font-medium"><?= $label ?></span>
              <i x-show="theme==='<?= $val ?>'" class="fa-solid fa-circle-check text-blue-500 text-xs" x-cloak></i>
            </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Account -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/6">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
              <i class="fa-solid fa-user-gear text-slate-500 dark:text-slate-400 text-base"></i> Account
            </h3>
          </div>
          <div class="divide-y divide-slate-100 dark:divide-white/6">
            <button @click="switchTab('profile')" class="w-full flex items-center justify-between px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors text-left">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-user text-slate-400 text-sm w-4 text-center"></i>
                <span class="text-sm text-slate-700 dark:text-slate-200 font-medium">Edit Profile</span>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs"></i>
            </button>
            <button @click="switchTab('security')" class="w-full flex items-center justify-between px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors text-left">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-lock text-slate-400 text-sm w-4 text-center"></i>
                <span class="text-sm text-slate-700 dark:text-slate-200 font-medium">Change Password</span>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs"></i>
            </button>
            <a href="/privacy-policy" class="flex items-center justify-between px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-shield text-slate-400 text-sm w-4 text-center"></i>
                <span class="text-sm text-slate-700 dark:text-slate-200 font-medium">Privacy Policy</span>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs"></i>
            </a>
            <a href="/terms" class="flex items-center justify-between px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-scroll text-slate-400 text-sm w-4 text-center"></i>
                <span class="text-sm text-slate-700 dark:text-slate-200 font-medium">Terms of Service</span>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs"></i>
            </a>
            <button onclick="fetch('/api/auth/logout',{method:'POST',credentials:'same-origin'}).then(()=>window.location='/login')"
                    class="w-full flex items-center gap-3 px-5 py-4 hover:bg-red-50 dark:hover:bg-red-500/8 transition-colors text-left">
              <i class="fa-solid fa-right-from-bracket text-red-500 text-sm w-4 text-center"></i>
              <span class="text-sm text-red-500 font-medium">Sign Out</span>
            </button>
          </div>
        </div>
      </section>

      <!-- ══ SUPPORT ════════════════════════════════════════════════════════ -->
      <section x-show="tab==='support'" x-cloak x-transition>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-5">Help &amp; Support</h2>

        <!-- Contact cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
          <a href="tel:+917721892429" class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:border-blue-400/30 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500/20 transition-colors">
              <i class="fa-solid fa-phone text-blue-500 text-lg"></i>
            </div>
            <div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-0.5">Helpline</p>
              <p class="text-slate-900 dark:text-white font-bold text-sm">+91 7721892429</p>
              <p class="text-slate-400 text-xs mt-0.5">Mon–Sat, 9 AM – 6 PM</p>
            </div>
          </a>
          <a href="https://wa.me/917721892429" target="_blank" rel="noopener" class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:border-green-400/30 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-green-500/10 dark:bg-green-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-green-500/20 transition-colors">
              <i class="fa-brands fa-whatsapp text-green-500 text-xl"></i>
            </div>
            <div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-0.5">WhatsApp</p>
              <p class="text-slate-900 dark:text-white font-bold text-sm">Chat with us</p>
              <p class="text-slate-400 text-xs mt-0.5">Typically replies in 1 hour</p>
            </div>
          </a>
          <a href="mailto:support@ttelectro.in" class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:border-purple-400/30 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 dark:bg-purple-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-500/20 transition-colors">
              <i class="fa-solid fa-envelope text-purple-500 text-lg"></i>
            </div>
            <div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-0.5">Email</p>
              <p class="text-slate-900 dark:text-white font-bold text-sm">support@ttelectro.in</p>
              <p class="text-slate-400 text-xs mt-0.5">Response within 24 hours</p>
            </div>
          </a>
          <a href="/track-order" class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm hover:border-orange-400/30 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-orange-500/10 dark:bg-orange-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-orange-500/20 transition-colors">
              <i class="fa-solid fa-truck-fast text-orange-500 text-lg"></i>
            </div>
            <div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-0.5">Track Order</p>
              <p class="text-slate-900 dark:text-white font-bold text-sm">Track your shipment</p>
              <p class="text-slate-400 text-xs mt-0.5">Real-time tracking updates</p>
            </div>
          </a>
        </div>

        <!-- Quick links -->
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 dark:border-white/6">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
              <i class="fa-solid fa-circle-question text-blue-500 text-base"></i> Quick Help
            </h3>
          </div>
          <div class="divide-y divide-slate-100 dark:divide-white/6">
            <?php foreach([
              ['/faq','Frequently Asked Questions','fa-circle-question'],
              ['/track-order','Track your order','fa-truck-fast'],
              ['/contact','Contact Us','fa-comments'],
              ['/privacy-policy','Privacy Policy','fa-file-shield'],
              ['/terms','Terms of Service','fa-scroll'],
            ] as [$href,$label,$icon]): ?>
            <a href="<?= $href ?>" class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors group">
              <div class="flex items-center gap-3">
                <i class="fa-solid <?= $icon ?> text-slate-400 text-sm w-4 text-center"></i>
                <span class="text-sm text-slate-700 dark:text-slate-200 font-medium"><?= $label ?></span>
              </div>
              <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 text-xs group-hover:text-blue-500 transition-colors"></i>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- App version -->
        <p class="text-center text-xs text-slate-400 dark:text-slate-600 mt-6">
          <?= APP_NAME ?> v1.0.0 · Built with ❤️ in India
        </p>
      </section>

    </div><!-- /max-w -->
  </main><!-- /main -->
</div><!-- /flex -->

<script>
function userDash(){
  return {
    tab: new URLSearchParams(location.search).get('tab')||'overview',
    user: window.__DASH_USER__||{},
    orders: null, wishlist: null, notifications: null,
    stats: {orders:0,spent:0,wishlist:0,reviews:0},
    recentOrders: [], overviewLoaded: false,
    orderFilter: 'all',
    unreadCount: 0,
    // Profile
    pf: {name:'',phone:'',loading:false,msg:'',err:''},
    // OTP for phone change
    ot: {show:false,newPhone:'',code:'',sent:false,sending:false,verifying:false,verified:false,timer:0,timerInt:null,devOtp:'',err:''},
    // Change password
    cp: {current:'',newPw:'',confirm:'',loading:false,msg:'',err:'',showCurrent:false,showNew:false,showConfirm:false,strength:0},
    // Theme
    theme: localStorage.getItem('theme')||'system',

    async init(){
      this.pf.name  = this.user.name  || '';
      this.pf.phone = this.user.phone || '';
      await this.loadOverview();
      this.loadUnread();
    },

    switchTab(t){
      this.tab=t;
      const u=new URL(location.href);u.searchParams.set('tab',t);history.pushState({},'',u);
      if(t==='orders'&&!this.orders)         this.loadOrders();
      if(t==='wishlist'&&!this.wishlist)     this.loadWishlist();
      if(t==='notifications'&&!this.notifications) this.loadNotifications();
    },

    async loadOverview(){
      if(this.overviewLoaded)return;
      try{
        const [oR,wR] = await Promise.all([
          apiFetch('/api/orders').catch(()=>({data:[]})),
          apiFetch('/api/wishlist').catch(()=>({data:[]}))
        ]);
        const ords=oR.data||[];
        this.orders=ords;
        this.recentOrders=ords.slice(0,3);
        this.stats.orders   = ords.length;
        this.stats.spent    = ords.reduce((s,o)=>s+parseFloat(o.total||0),0);
        this.stats.wishlist = (wR.data||[]).length;
        this.stats.loyalty  = this.user.loyalty_points||0;
        this.overviewLoaded=true;
      }catch(e){this.overviewLoaded=true;}
    },

    async loadOrders(){
      try{const r=await apiFetch('/api/orders');this.orders=r.data||[];}catch(e){this.orders=[];}
    },

    get filteredOrders(){
      if(!this.orders)return[];
      return this.orderFilter==='all'?this.orders:this.orders.filter(o=>o.status===this.orderFilter);
    },

    async loadWishlist(){
      try{const r=await apiFetch('/api/wishlist');this.wishlist=r.data||[];}catch(e){this.wishlist=[];}
    },

    async loadNotifications(){
      try{
        const r=await apiFetch('/api/notifications');
        this.notifications=r.data||[];
        this.unreadCount=this.notifications.filter(n=>!n.is_read).length;
      }catch(e){this.notifications=[];}
    },

    async loadUnread(){
      try{
        const r=await apiFetch('/api/notifications');
        this.unreadCount=(r.data||[]).filter(n=>!n.is_read).length;
      }catch(e){}
    },

    async markAllRead(){
      try{
        await apiFetch('/api/notifications/read-all',{method:'POST'});
        if(this.notifications)this.notifications=this.notifications.map(n=>({...n,is_read:1}));
        this.unreadCount=0;
        showToast('All notifications marked as read','success');
      }catch(e){}
    },

    async removeWishlist(pid){
      try{
        await apiFetch('/api/wishlist/'+pid,{method:'DELETE'});
        this.wishlist=this.wishlist.filter(w=>w.product_id!=pid);
        this.stats.wishlist=Math.max(0,this.stats.wishlist-1);
        showToast('Removed from wishlist','success');
      }catch(e){}
    },

    async addToCartFromWishlist(item){
      try{
        await apiFetch('/api/cart',{method:'POST',body:JSON.stringify({product_id:item.product_id,quantity:1})});
        showToast((item.product_name||'Item')+' added to cart!','success');
      }catch(e){showToast(e.message,'error');}
    },

    async saveProfile(){
      this.pf.loading=true;this.pf.msg='';this.pf.err='';
      try{
        await apiFetch('/api/auth/me/update',{method:'PATCH',body:JSON.stringify({name:this.pf.name})});
        this.user.name=this.pf.name;
        this.pf.msg='Profile updated!';
        showToast('Profile updated!','success');
        setTimeout(()=>this.pf.msg='',3000);
      }catch(e){this.pf.err=e.message;}
      this.pf.loading=false;
    },

    // OTP for phone change
    async otSendOtp(){
      if(this.ot.newPhone.length<10){this.ot.err='Enter a valid 10-digit number';return;}
      this.ot.sending=true;this.ot.err='';
      try{
        const r=await apiFetch('/api/auth/otp/send',{method:'POST',body:JSON.stringify({phone:this.ot.newPhone,purpose:'verify_phone'})});
        this.ot.sent=true;this.ot.devOtp=r.otp||'';this.otStartTimer();
        showToast('OTP sent!','success');
      }catch(e){this.ot.err=e.message;}
      this.ot.sending=false;
    },
    otStartTimer(){
      this.ot.timer=30;clearInterval(this.ot.timerInt);
      this.ot.timerInt=setInterval(()=>{if(this.ot.timer>0)this.ot.timer--;else clearInterval(this.ot.timerInt);},1000);
    },
    async otVerify(){
      this.ot.verifying=true;this.ot.err='';
      try{
        await apiFetch('/api/auth/otp/verify',{method:'POST',body:JSON.stringify({phone:this.ot.newPhone,otp:this.ot.code,purpose:'verify_phone'})});
        this.ot.verified=true;
        this.pf.phone='+91'+this.ot.newPhone;
        this.user.phone=this.pf.phone;
        this.user.phone_verified=1;
        showToast('Phone number verified!','success');
      }catch(e){this.ot.err=e.message;}
      this.ot.verifying=false;
    },

    // Change password
    cpCalcStrength(){
      let s=0,p=this.cp.newPw;
      if(p.length>=8)s++;if(/[A-Z]/.test(p))s++;if(/[0-9]/.test(p))s++;if(/[^A-Za-z0-9]/.test(p))s++;
      this.cp.strength=s;
    },
    async changePassword(){
      if(this.cp.newPw.length<8){this.cp.err='Password must be at least 8 characters';return;}
      if(this.cp.newPw!==this.cp.confirm){this.cp.err='Passwords do not match';return;}
      this.cp.loading=true;this.cp.msg='';this.cp.err='';
      try{
        await apiFetch('/api/auth/change-password',{method:'POST',body:JSON.stringify({current_password:this.cp.current,new_password:this.cp.newPw,confirm_password:this.cp.confirm})});
        this.cp.msg='Password changed successfully!';
        this.cp.current=this.cp.newPw=this.cp.confirm='';this.cp.strength=0;
        showToast('Password changed!','success');
        setTimeout(()=>this.cp.msg='',4000);
      }catch(e){this.cp.err=e.message;}
      this.cp.loading=false;
    },

    // Theme
    setTheme(t){
      this.theme=t;
      if(t==='system'){
        localStorage.removeItem('theme');
        const d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark',d);
        document.documentElement.classList.toggle('light',!d);
      }else{
        localStorage.setItem('theme',t);
        document.documentElement.classList.toggle('dark',t==='dark');
        document.documentElement.classList.toggle('light',t==='light');
      }
      showToast('Theme set to '+t,'success');
    }
  }
}
</script>
