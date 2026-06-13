<?php $pageTitle = '404 – Page Not Found'; ?>
<div class="min-h-[70vh] flex items-center justify-center px-4">
  <div class="text-center max-w-md">
    <!-- 404 number -->
    <div class="relative inline-block mb-6">
      <div class="text-9xl font-black text-slate-100 dark:text-white/5 leading-none select-none">404</div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="w-20 h-20 rounded-2xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center">
          <i class="fa-solid fa-magnifying-glass text-blue-600 dark:text-blue-400 text-2xl"></i>
        </div>
      </div>
    </div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Page Not Found</h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-8 leading-relaxed">
      The page you're looking for doesn't exist or has been moved. Let's get you back on track.
    </p>
    <div class="flex items-center justify-center gap-3 flex-wrap">
      <a href="/"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-500/20">
        <i class="fa-solid fa-house text-xs"></i> Go Home
      </a>
      <a href="/products"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-medium text-sm transition-all shadow-sm">
        <i class="fa-solid fa-microchip text-xs"></i> Browse Products
      </a>
    </div>
    <div class="mt-8 flex items-center justify-center gap-4 text-xs text-slate-400 dark:text-slate-500">
      <a href="/contact" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Contact Support</a>
      <span>·</span>
      <a href="/faq" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">FAQ</a>
      <span>·</span>
      <a href="/track-order" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Track Order</a>
    </div>
  </div>
</div>
