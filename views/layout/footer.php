</main>

<!-- Footer -->
<footer class="bg-slate-900 dark:bg-[hsl(222,47%,5%)] border-t border-slate-200 dark:border-white/6 mt-24">
  <div class="max-w-7xl mx-auto px-4 pt-14 pb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

      <!-- Brand -->
      <div class="lg:col-span-1">
        <a href="/" class="inline-flex items-center mb-5">
          <img src="/assets/logo.png" alt="TT Electro Store – Electronic Components Amravati" class="h-9 w-auto" style="filter:brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(355deg);">
        </a>
        <p class="text-slate-400 text-sm leading-relaxed mb-3">Your trusted electronic components store in Amravati, Maharashtra. Arduino, Raspberry Pi, sensors, 3D printing &amp; DIY kits — fast delivery across India.</p>
        <div class="text-slate-500 text-xs mb-4 space-y-1">
          <p class="flex items-start gap-1.5"><i class="fa-solid fa-location-dot text-blue-400 mt-0.5 flex-shrink-0"></i> First Floor, Office No. 31, Trademark Complex,<br>near Gadge Baba Temple, Gadge Nagar,<br>Amravati, Maharashtra 444603</p>
        </div>
        <div class="flex gap-2.5">
          <a href="#" class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-slate-400 hover:text-blue-400 hover:bg-blue-500/15 transition-all" title="LinkedIn" rel="noopener">
            <i class="fa-brands fa-linkedin-in text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:bg-sky-500/15 transition-all" title="X / Twitter" rel="noopener">
            <i class="fa-brands fa-x-twitter text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-slate-400 hover:text-red-400 hover:bg-red-500/15 transition-all" title="YouTube" rel="noopener">
            <i class="fa-brands fa-youtube text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 rounded-xl bg-white/8 flex items-center justify-center text-slate-400 hover:text-pink-400 hover:bg-pink-500/15 transition-all" title="Instagram" rel="noopener">
            <i class="fa-brands fa-instagram text-sm"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-white font-semibold text-sm mb-5 tracking-wide uppercase">Shop</h4>
        <ul class="space-y-3 text-sm">
          <li><a href="/products"    class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Electronic Components</a></li>
          <li><a href="/diy-kits"    class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> DIY Kits</a></li>
          <li><a href="/3d-printing" class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> 3D Printing Service</a></li>
          <li><a href="/offers"      class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Offers &amp; Flash Sales</a></li>
          <li><a href="/blogs"       class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Tech Blog</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div>
        <h4 class="text-white font-semibold text-sm mb-5 tracking-wide uppercase">Support</h4>
        <ul class="space-y-3 text-sm">
          <li><a href="/track-order"   class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Track Order</a></li>
          <li><a href="/faq"           class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> FAQ</a></li>
          <li><a href="/contact"       class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Contact Us</a></li>
          <li><a href="/about"         class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> About Us</a></li>
          <li><a href="/privacy-policy" class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Privacy Policy</a></li>
          <li><a href="/terms"         class="text-slate-400 hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i> Terms of Service</a></li>
        </ul>
      </div>

      <!-- Contact & Newsletter -->
      <div>
        <h4 class="text-white font-semibold text-sm mb-5 tracking-wide uppercase">Stay Updated</h4>
        <p class="text-slate-400 text-sm mb-4">Get exclusive deals on electronic components and tech updates.</p>
        <form x-data="{email:'',loading:false}" @submit.prevent="
          loading=true;
          fetch('/api/newsletter/subscribe',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email})})
            .then(r=>r.json()).then(d=>{ showToast(d.message,'success'); email=''; }).catch(()=>showToast('Error','error')).finally(()=>loading=false)">
          <div class="flex gap-2 mb-5">
            <input type="email" x-model="email" required placeholder="your@email.com"
                   class="flex-1 px-3.5 py-2.5 rounded-xl bg-white/8 border border-white/10 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all">
            <button type="submit" :disabled="loading"
                    class="px-3.5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-all disabled:opacity-50 flex-shrink-0">
              <i x-show="!loading" class="fa-solid fa-paper-plane text-xs"></i>
              <span x-show="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin inline-block" x-cloak></span>
            </button>
          </div>
        </form>
        <div class="space-y-2.5 text-sm text-slate-400">
          <a href="tel:+917721892429" class="flex items-center gap-3 hover:text-white transition-colors">
            <span class="w-7 h-7 rounded-lg bg-blue-500/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-phone text-blue-400 text-xs"></i></span>
            +91 77218 92429
          </a>
          <a href="mailto:support@ttelectro.in" class="flex items-center gap-3 hover:text-white transition-colors">
            <span class="w-7 h-7 rounded-lg bg-blue-500/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-envelope text-blue-400 text-xs"></i></span>
            support@ttelectro.in
          </a>
          <div class="flex items-start gap-3">
            <span class="w-7 h-7 rounded-lg bg-blue-500/15 flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-location-dot text-blue-400 text-xs"></i></span>
            <span class="text-xs leading-relaxed">Trademark Complex, Gadge Nagar,<br>Amravati – 444603</span>
          </div>
          <div class="flex items-center gap-3">
            <span class="w-7 h-7 rounded-lg bg-blue-500/15 flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-clock text-blue-400 text-xs"></i></span>
            Mon–Sat, 9am – 6pm IST
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/8 mt-12 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
      <p class="text-slate-500 text-sm">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved. | Electronic Components Store in Amravati</p>
      <div class="flex items-center gap-5 text-xs text-slate-500">
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-indian-rupee-sign"></i> Made in India</span>
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-truck"></i> COD Available</span>
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-rotate-left"></i> Easy Returns</span>
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-lock"></i> Secure Payments</span>
      </div>
    </div>
  </div>
</footer>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" rel="noopener"
   class="fixed bottom-6 left-6 z-50 w-13 h-13 flex items-center justify-center rounded-full bg-[#25d366] hover:bg-[#20bd5a] shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all hover:scale-110 group"
   style="width:3.25rem;height:3.25rem;"
   title="Chat on WhatsApp – TT Electro Store">
  <i class="fa-brands fa-whatsapp text-2xl text-white"></i>
</a>

<!-- Global JS -->
<script>
function appState() {
  return {
    isDark: document.documentElement.classList.contains('dark'),
    mobileOpen: false,
    init() {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
          this.isDark = e.matches;
          document.documentElement.classList.toggle('dark',  this.isDark);
          document.documentElement.classList.toggle('light', !this.isDark);
        }
      });
    },
    toggleTheme() {
      this.isDark = !this.isDark;
      document.documentElement.classList.toggle('dark',  this.isDark);
      document.documentElement.classList.toggle('light', !this.isDark);
      localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
    }
  }
}
function navbar() { return { mobileOpen: false, scrolled: false }; }

function showToast(msg, type='info') {
  const c = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = `<span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(() => { t.style.opacity='0'; t.style.transform='translateY(.5rem) scale(.97)'; t.style.transition='.2s'; setTimeout(()=>t.remove(),200); }, 3200);
}

async function apiFetch(url, opts={}) {
  const defaults = { headers: { 'Content-Type': 'application/json' } };
  const res = await fetch(url, { ...defaults, ...opts, headers: { ...defaults.headers, ...(opts.headers||{}) } });
  let data = {};
  try { data = await res.json(); } catch(_) {}
  if (!res.ok) throw new Error(data.message || 'Request failed');
  return data;
}

async function addToCart(productId, qty=1) {
  try {
    await apiFetch('/api/cart', { method: 'POST', body: JSON.stringify({ product_id: productId, quantity: qty }) });
    showToast('Added to cart!', 'success');
    const badge = document.querySelector('#cartBadge');
    if(badge) badge.textContent = parseInt(badge.textContent||0)+qty;
  } catch(e) { showToast(e.message, 'error'); }
}

async function addToWishlist(productId) {
  try {
    await apiFetch('/api/wishlist', { method: 'POST', body: JSON.stringify({ product_id: productId }) });
    showToast('Added to wishlist!', 'success');
  } catch(e) { showToast(e.message, 'error'); }
}
</script>
</body>
</html>
