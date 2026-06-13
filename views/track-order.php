<?php $pageTitle = 'Track Order'; ?>
<div class="max-w-lg mx-auto px-4 py-12" x-data="trackOrder()">

  <!-- Header -->
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center mx-auto mb-4">
      <i class="fa-solid fa-truck-fast text-blue-600 dark:text-blue-400 text-2xl"></i>
    </div>
    <p class="section-label justify-center"><i class="fa-solid fa-location-dot"></i> Real-time tracking</p>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Track Your Order</h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Enter your order number to see real-time status</p>
  </div>

  <!-- Search form -->
  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 p-6 shadow-sm">
    <div class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Order Number <span class="text-red-500">*</span></label>
        <input type="text" x-model="orderNumber" placeholder="TTE-XXXXXXXX" @keydown.enter="track()"
               class="input-base w-full px-4 py-3 text-sm uppercase font-mono tracking-widest">
      </div>
      <div>
        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">
          Email <span class="text-slate-300 dark:text-slate-600 font-normal normal-case">(optional)</span>
        </label>
        <input type="email" x-model="email" placeholder="your@email.com" @keydown.enter="track()"
               class="input-base w-full px-4 py-3 text-sm">
      </div>

      <div x-show="error" x-cloak
           class="flex items-start gap-2 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-3.5 py-3 rounded-xl border border-red-200 dark:border-red-500/20">
        <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0 text-xs"></i>
        <span x-text="error"></span>
      </div>

      <button @click="track()" :disabled="loading"
              class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
        <span x-show="!loading" class="flex items-center gap-2">
          <i class="fa-solid fa-magnifying-glass text-xs"></i> Track Order
        </span>
        <span x-show="loading" x-cloak class="flex items-center gap-2">
          <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
          Searching...
        </span>
      </button>
    </div>
  </div>

  <!-- Result -->
  <div x-show="order" x-transition class="mt-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-sm overflow-hidden">
    <!-- Order header -->
    <div class="p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between gap-4">
      <div>
        <p class="text-base font-bold text-slate-900 dark:text-white font-mono" x-text="order?.order_number"></p>
        <p class="text-sm text-slate-400 dark:text-slate-500" x-text="order ? new Date(order.created_at).toLocaleDateString('en-IN',{year:'numeric',month:'short',day:'numeric'}) : ''"></p>
      </div>
      <span class="px-3 py-1.5 rounded-full text-xs font-bold capitalize"
            :class="{
              'bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-400': order?.status==='pending',
              'bg-blue-100 dark:bg-blue-500/15 text-blue-700 dark:text-blue-400': order?.status==='processing',
              'bg-purple-100 dark:bg-purple-500/15 text-purple-700 dark:text-purple-400': order?.status==='shipped',
              'bg-green-100 dark:bg-green-500/15 text-green-700 dark:text-green-400': order?.status==='delivered',
              'bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400': order?.status==='cancelled',
            }"
            x-text="order?.status"></span>
    </div>

    <!-- Tracking number -->
    <div x-show="order?.tracking_number" class="px-5 py-4 border-b border-slate-100 dark:border-white/5">
      <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-1">Tracking Number</p>
      <p class="text-blue-600 dark:text-blue-400 font-mono font-bold text-sm" x-text="order?.tracking_number"></p>
    </div>

    <!-- Status Timeline -->
    <div x-show="order?.status_timeline?.length" class="px-5 py-5">
      <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-4">Status Timeline</p>
      <div class="space-y-4">
        <template x-for="(t,i) in [...(order?.status_timeline||[])].reverse()" :key="i">
          <div class="flex items-start gap-3">
            <div class="flex flex-col items-center flex-shrink-0">
              <div class="w-2.5 h-2.5 rounded-full bg-blue-600 dark:bg-blue-500 mt-0.5"></div>
              <div class="w-px flex-1 bg-slate-200 dark:bg-white/8 mt-1" style="min-height:1.5rem;"></div>
            </div>
            <div class="pb-2">
              <p class="text-sm font-semibold text-slate-800 dark:text-slate-200" x-text="t.label||t.status"></p>
              <p class="text-xs text-slate-400 dark:text-slate-500" x-text="new Date(t.time).toLocaleString('en-IN')"></p>
            </div>
          </div>
        </template>
      </div>
    </div>

    <!-- CTA -->
    <div class="px-5 pb-5">
      <a :href="'/orders/'+order?.id"
         class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-blue-200 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 text-sm font-medium transition-all">
        <i class="fa-solid fa-receipt text-xs"></i>
        View Full Order Details
      </a>
    </div>
  </div>

  <!-- Tips -->
  <div class="mt-5 p-4 rounded-2xl bg-slate-50 dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 text-xs text-slate-500 dark:text-slate-400 space-y-1">
    <p class="font-semibold text-slate-600 dark:text-slate-300 mb-2">Tips:</p>
    <p class="flex items-start gap-2"><i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i> Order numbers start with <span class="font-mono text-slate-600 dark:text-slate-300 ml-1">TTE-</span></p>
    <p class="flex items-start gap-2"><i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i> Check your email for order confirmation</p>
    <p class="flex items-start gap-2"><i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i> <a href="/contact" class="text-blue-600 dark:text-blue-400 hover:underline">Contact support</a> for help with your order</p>
  </div>
</div>

<script>
function trackOrder() {
  return {
    orderNumber: '', email: '', loading: false, error: '', order: null,
    async track() {
      if(!this.orderNumber.trim()) { this.error='Please enter an order number'; return; }
      this.loading=true; this.error=''; this.order=null;
      try {
        const d = await apiFetch('/api/orders/track', { method:'POST', body:JSON.stringify({ order_number:this.orderNumber.toUpperCase(), email:this.email }) });
        this.order = d.data;
      } catch(e) { this.error=e.message; }
      this.loading=false;
    }
  }
}
</script>
