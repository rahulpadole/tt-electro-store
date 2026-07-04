<?php $pageTitle = 'Track Your Order — TT Electro Store'; ?>
<div class="min-h-screen" x-data="trackPage()">

  <!-- ── Hero / Search header ───────────────────────────────────────────── -->
  <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 pt-14 pb-20 px-4 text-center relative overflow-hidden">
    <!-- background decoration -->
    <div class="absolute inset-0 opacity-20" aria-hidden="true"
         style="background-image:radial-gradient(circle at 20% 50%,#3b82f6 0,transparent 50%),radial-gradient(circle at 80% 20%,#6366f1 0,transparent 40%)"></div>

    <div class="relative max-w-xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/15 border border-blue-400/25 text-blue-300 text-xs font-semibold tracking-wide mb-5">
        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
        REAL-TIME TRACKING
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 tracking-tight">Track Your Order</h1>
      <p class="text-slate-400 text-sm mb-8">Enter your order number (TTE-XXXXXXXX) or Delhivery AWB number</p>

      <!-- Search card -->
      <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-4 sm:p-5 shadow-xl">
        <div class="flex gap-2.5 items-center">
          <div class="relative flex-1">
            <input type="text"
                   x-model="query"
                   @keydown.enter="track()"
                   placeholder="TTE-XXXXXXXX or AWB number"
                   autocomplete="off" spellcheck="false"
                   class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/15 text-white placeholder-slate-400 rounded-xl text-sm font-mono tracking-wider outline-none focus:bg-white/15 focus:border-blue-400/60 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          </div>
          <button @click="track()" :disabled="loading"
                  class="flex-shrink-0 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-700/30 flex items-center gap-2">
            <span x-show="loading" x-cloak class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <span x-show="!loading"><i class="fa-solid fa-arrow-right"></i></span>
            <span class="hidden sm:inline" x-show="!loading">Track</span>
          </button>
        </div>

        <!-- Error -->
        <div x-show="error" x-cloak x-transition
             class="mt-3 flex items-center gap-2 text-sm text-red-300 bg-red-500/10 border border-red-500/20 rounded-xl px-3.5 py-2.5">
          <i class="fa-solid fa-triangle-exclamation flex-shrink-0 text-xs"></i>
          <span x-text="error"></span>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Results area ──────────────────────────────────────────────────── -->
  <div class="max-w-2xl mx-auto px-4 -mt-6 pb-16">

    <!-- Loading skeleton -->
    <div x-show="loading" x-cloak class="space-y-3 animate-pulse">
      <div class="h-28 rounded-2xl bg-slate-200 dark:bg-white/6"></div>
      <div class="h-40 rounded-2xl bg-slate-200 dark:bg-white/6"></div>
      <div class="h-48 rounded-2xl bg-slate-200 dark:bg-white/6"></div>
    </div>

    <!-- Results -->
    <div x-show="order && !loading" x-cloak x-transition class="space-y-4">

      <!-- ① Order Summary card -->
      <div class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-slate-100 dark:border-white/6">
          <div>
            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase tracking-wide font-semibold mb-0.5">Order Number</p>
            <p class="font-mono font-bold text-slate-900 dark:text-white text-lg tracking-wide" x-text="order?.order_number"></p>
          </div>
          <div class="text-right flex-shrink-0">
            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">Placed on</p>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300"
               x-text="order ? new Date(order.created_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}) : ''"></p>
          </div>
        </div>

        <div class="flex items-center justify-between px-5 py-3.5">
          <div class="flex items-center gap-2.5">
            <!-- Status badge -->
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold capitalize"
                  :class="{
                    'bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300': order?.status==='pending',
                    'bg-blue-100 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300': order?.status==='processing',
                    'bg-violet-100 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300': order?.status==='shipped',
                    'bg-green-100 dark:bg-green-500/15 text-green-700 dark:text-green-300': order?.status==='delivered',
                    'bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300': order?.status==='cancelled',
                  }">
              <span class="w-1.5 h-1.5 rounded-full"
                    :class="{
                      'bg-amber-500': order?.status==='pending',
                      'bg-blue-500': order?.status==='processing',
                      'bg-violet-500': order?.status==='shipped',
                      'bg-green-500': order?.status==='delivered',
                      'bg-red-500': order?.status==='cancelled',
                    }"></span>
              <span x-text="order?.status"></span>
            </span>

            <!-- AWB chip -->
            <span x-show="order?.awb_number"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-white/8 text-slate-600 dark:text-slate-300">
              <i class="fa-solid fa-truck-fast text-[10px]"></i>
              AWB: <span class="font-mono" x-text="order?.awb_number"></span>
            </span>
          </div>

          <a :href="'/orders/'+order?.id"
             class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium flex items-center gap-1">
            <i class="fa-solid fa-receipt text-[10px]"></i> Details
          </a>
        </div>
      </div>

      <!-- ② Progress stepper -->
      <div class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl shadow-sm px-5 py-5">
        <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-5">Delivery Progress</p>
        <div class="flex items-start justify-between relative">
          <!-- connecting line -->
          <div class="absolute top-4 left-0 right-0 h-0.5 bg-slate-200 dark:bg-white/8" style="margin:0 calc(100%/10)"></div>
          <div class="absolute top-4 left-0 h-0.5 bg-blue-500 transition-all duration-700"
               :style="'right:' + progressRight() + '%; margin:0 calc(100%/10)'"></div>

          <template x-for="(step,i) in steps" :key="i">
            <div class="flex flex-col items-center gap-2 z-10 flex-1">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm transition-all duration-300 ring-2"
                   :class="stepClass(step.key)">
                <i :class="step.icon + ' text-xs'"></i>
              </div>
              <span class="text-[10px] font-semibold text-center leading-tight transition-colors"
                    :class="isStepDone(step.key) ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500'"
                    x-text="step.label"></span>
            </div>
          </template>
        </div>
      </div>

      <!-- ③ Expected delivery / Delhivery status card -->
      <div x-show="order?.awb_number" x-cloak
           class="bg-blue-50 dark:bg-blue-500/8 border border-blue-200 dark:border-blue-500/20 rounded-2xl px-5 py-4 flex items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide mb-1">Delhivery Status</p>
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm" x-text="order?.delivery_status || '—'"></p>
        </div>
        <div x-show="order?.expected_delivery_date" class="text-right">
          <p class="text-xs text-slate-500 dark:text-slate-400 mb-0.5">Expected by</p>
          <p class="font-bold text-slate-800 dark:text-white text-sm"
             x-text="order?.expected_delivery_date
               ? new Date(order.expected_delivery_date).toLocaleDateString('en-IN',{weekday:'short',day:'numeric',month:'short'})
               : ''"></p>
        </div>
        <div x-show="!order?.expected_delivery_date" class="flex-shrink-0">
          <span class="text-xs text-slate-500 dark:text-slate-400">ETA pending</span>
        </div>
      </div>

      <!-- ④ Live Delhivery scan timeline -->
      <div x-show="order?.delhivery_scans?.length" x-cloak
           class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/6">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-500/15 flex items-center justify-center">
              <i class="fa-solid fa-route text-violet-600 dark:text-violet-400 text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-900 dark:text-white">Shipment Events</p>
              <p class="text-xs text-slate-400 dark:text-slate-500" x-text="(order?.delivery_partner||'Delhivery') + ' live scan log'"></p>
            </div>
          </div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold"
                x-text="(order?.delhivery_scans?.length||0) + ' events'"></span>
        </div>

        <div class="px-5 py-4 space-y-0">
          <template x-for="(scan, idx) in order?.delhivery_scans||[]" :key="idx">
            <div class="flex gap-3.5 pb-4"
                 :class="idx < (order?.delhivery_scans?.length||0) - 1 ? 'border-b border-slate-50 dark:border-white/4' : ''">
              <!-- Timeline dot + line -->
              <div class="flex flex-col items-center flex-shrink-0 pt-0.5">
                <div class="w-2.5 h-2.5 rounded-full ring-2 ring-offset-2 ring-offset-white dark:ring-offset-[hsl(222,47%,10%)] flex-shrink-0 mt-1"
                     :class="idx===0 ? 'bg-blue-600 ring-blue-400 dark:ring-blue-500' : 'bg-slate-300 dark:bg-white/20 ring-slate-200 dark:ring-white/10'"></div>
                <div x-show="idx < (order?.delhivery_scans?.length||0) - 1"
                     class="w-px flex-1 bg-slate-100 dark:bg-white/6 mt-1.5 mb-0" style="min-height:1.5rem"></div>
              </div>
              <!-- Content -->
              <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex items-start justify-between gap-2 flex-wrap">
                  <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 leading-tight" x-text="scan.status"></p>
                  <p class="text-[11px] text-slate-400 dark:text-slate-500 flex-shrink-0 font-mono"
                     x-text="scan.time ? new Date(scan.time).toLocaleString('en-IN',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'}) : ''"></p>
                </div>
                <p x-show="scan.location" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1.5">
                  <i class="fa-solid fa-location-dot text-[10px]"></i>
                  <span x-text="scan.location"></span>
                </p>
                <p x-show="scan.instruction" class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 italic" x-text="scan.instruction"></p>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- ⑤ No AWB yet (order not dispatched) -->
      <div x-show="!order?.awb_number && order?.status !== 'cancelled'" x-cloak
           class="bg-amber-50 dark:bg-amber-500/8 border border-amber-200 dark:border-amber-500/20 rounded-2xl px-5 py-4 flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
          <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400 text-sm"></i>
        </div>
        <div>
          <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-0.5">Preparing Your Order</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Your order is being packed and will be handed to Delhivery soon.
            You'll receive an SMS &amp; email once it's dispatched with a live tracking link.
          </p>
        </div>
      </div>

      <!-- ⑥ Help CTA -->
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="/contact"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium transition-all">
          <i class="fa-solid fa-headset text-xs"></i> Contact Support
        </a>
        <a :href="'https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi%2C+I+need+help+with+order+' + encodeURIComponent(order?.order_number||'')"
           target="_blank" rel="noopener"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-500/15 text-sm font-medium transition-all">
          <i class="fa-brands fa-whatsapp text-base"></i> WhatsApp Us
        </a>
      </div>

    </div><!-- /results -->

    <!-- Empty state — before any search -->
    <div x-show="!order && !loading && !error" x-cloak class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl p-4 text-center">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center mx-auto mb-2.5">
          <i class="fa-solid fa-hashtag text-blue-600 dark:text-blue-400 text-sm"></i>
        </div>
        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Order Number</p>
        <p class="text-xs text-slate-400">Format: <span class="font-mono text-slate-500 dark:text-slate-400">TTE-XXXXXXXX</span></p>
      </div>
      <div class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl p-4 text-center">
        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center mx-auto mb-2.5">
          <i class="fa-solid fa-truck-fast text-violet-600 dark:text-violet-400 text-sm"></i>
        </div>
        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">AWB Number</p>
        <p class="text-xs text-slate-400">From Delhivery tracking SMS</p>
      </div>
      <div class="bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 rounded-2xl p-4 text-center">
        <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center mx-auto mb-2.5">
          <i class="fa-solid fa-envelope text-green-600 dark:text-green-400 text-sm"></i>
        </div>
        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Need Help?</p>
        <a href="/contact" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Contact support →</a>
      </div>
    </div>

  </div>
</div>

<script>
function trackPage() {
  return {
    query: new URLSearchParams(location.search).get('q') || '',
    loading: false,
    error: '',
    order: null,

    steps: [
      { key: 'placed',    label: 'Placed',    icon: 'fa-solid fa-check' },
      { key: 'processing',label: 'Processing', icon: 'fa-solid fa-box-open' },
      { key: 'shipped',   label: 'Shipped',   icon: 'fa-solid fa-truck-fast' },
      { key: 'out',       label: 'Out for\nDelivery', icon: 'fa-solid fa-person-biking' },
      { key: 'delivered', label: 'Delivered', icon: 'fa-solid fa-circle-check' },
    ],

    init() {
      if (this.query) this.track();
    },

    stepOrder: { placed: 0, processing: 1, shipped: 2, out: 3, delivered: 4 },

    currentStepIndex() {
      const s = (this.order?.status || '').toLowerCase();
      const d = (this.order?.delivery_status || '').toLowerCase();
      if (this.order?.status === 'delivered') return 4;
      if (this.order?.status === 'shipped') {
        return d.includes('out') ? 3 : 2;
      }
      if (this.order?.status === 'processing') return 1;
      return 0; // placed
    },

    isStepDone(key) {
      const idx = { placed: 0, processing: 1, shipped: 2, out: 3, delivered: 4 };
      return idx[key] <= this.currentStepIndex();
    },

    stepClass(key) {
      if (this.isStepDone(key)) {
        const isActive = ({ placed:0, processing:1, shipped:2, out:3, delivered:4 }[key] === this.currentStepIndex());
        return isActive
          ? 'bg-blue-600 text-white ring-blue-400 dark:ring-blue-500 scale-110 shadow-lg shadow-blue-500/30'
          : 'bg-blue-600 text-white ring-blue-200 dark:ring-blue-800';
      }
      return 'bg-slate-100 dark:bg-white/8 text-slate-400 dark:text-slate-500 ring-slate-200 dark:ring-white/10';
    },

    progressRight() {
      const idx = this.currentStepIndex(); // 0-4
      return Math.max(0, (4 - idx) / 4 * 90);
    },

    async track() {
      const q = this.query.trim();
      if (!q) { this.error = 'Please enter an order number or AWB number'; return; }
      this.loading = true; this.error = ''; this.order = null;

      // Update URL without reload
      const url = new URL(location.href);
      url.searchParams.set('q', q);
      history.replaceState(null, '', url);

      try {
        const isAwb = !/^TTE/i.test(q) && /^\d{10,}$/.test(q);
        const body  = isAwb
          ? { awb: q }
          : { order_number: q };

        const r = await apiFetch('/api/orders/track/', {
          method: 'POST',
          body: JSON.stringify(body)
        });
        this.order = r.data;
      } catch(e) {
        this.error = e.message || 'Something went wrong. Please try again.';
      }
      this.loading = false;
    }
  };
}
</script>
