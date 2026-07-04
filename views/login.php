<!DOCTYPE html>
<html lang="en-IN" class="scroll-smooth" x-data x-init="(function(){
  const s=localStorage.getItem('theme'),d=window.matchMedia('(prefers-color-scheme:dark)').matches;
  const dark=s?s==='dark':d;
  document.documentElement.classList.toggle('dark',dark);
  document.documentElement.classList.toggle('light',!dark);
})()">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO: Auth pages should NOT be indexed -->
  <meta name="robots" content="noindex, follow">

  <title>Sign In – <?= APP_NAME ?> | Electronics Store India</title>
  <meta name="description" content="Sign in to your <?= APP_NAME ?> account to track orders, manage your wishlist, and get exclusive deals on electronic components delivered across India.">

  <!-- Canonical -->
  <link rel="canonical" href="<?= APP_URL ?>/login">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= APP_URL ?>/login">
  <meta property="og:title"       content="Sign In – <?= APP_NAME ?>">
  <meta property="og:description" content="Access your TT Electro Store account. Track orders, manage wishlist and enjoy fast electronics delivery across India.">
  <meta property="og:image"       content="<?= APP_URL ?>/assets/logo.png">
  <meta property="og:site_name"   content="<?= APP_NAME ?>">
  <meta property="og:locale"      content="en_IN">

  <!-- Favicon -->
  <link rel="icon"             href="/assets/logo-icon.png" type="image/png" sizes="any">
  <link rel="apple-touch-icon" href="/assets/logo-icon.png">

  <!-- Performance: DNS prefetch for CDN domains -->
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="preconnect"   href="https://fonts.googleapis.com">
  <link rel="preconnect"   href="https://fonts.gstatic.com" crossorigin>

  <!-- Fonts (display=swap prevents FOIT) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif','system-ui','sans-serif']}}}}</script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <!-- JSON-LD Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Sign In – <?= APP_NAME ?>",
    "url": "<?= APP_URL ?>/login",
    "description": "Sign in to TT Electro Store – Your trusted electronics components store.",
    "isPartOf": {
      "@type": "WebSite",
      "name": "<?= APP_NAME ?>",
      "url": "<?= APP_URL ?>"
    },
    "publisher": {
      "@type": "Organization",
      "name": "<?= APP_NAME ?>",
      "url": "<?= APP_URL ?>",
      "logo": {"@type": "ImageObject", "url": "<?= APP_URL ?>/assets/logo.png"}
    }
  }
  </script>

  <style>
    *,*::before,*::after{box-sizing:border-box}
    html{font-size:16px;-webkit-text-size-adjust:100%}
    body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;margin:0}
    [x-cloak]{display:none!important}

    /* ── Brand panel gradient matching TT Electro logo palette ── */
    .brand-panel{
      background: linear-gradient(150deg, #0d1b2e 0%, #112240 40%, #0d2137 70%, #091828 100%);
    }
    .brand-accent{color:#e8692b}
    .brand-accent-bg{background:rgba(232,105,43,0.12)}
    .brand-accent-border{border-color:rgba(232,105,43,0.25)}

    /* ── Input fields ── */
    .f-input{
      width:100%;padding:.75rem 1rem .75rem 2.75rem;
      border-radius:.875rem;font-size:.9rem;
      border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;
      transition:border-color .15s,box-shadow .15s,background .15s;
      outline:none;font-family:inherit;
    }
    .f-input::placeholder{color:#94a3b8}
    .f-input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);background:#fff}
    .dark .f-input{background:hsl(222,47%,11%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .f-input::placeholder{color:#475569}
    .dark .f-input:focus{border-color:#3b82f6;background:hsl(222,47%,13%);box-shadow:0 0 0 3px rgba(59,130,246,.15)}
    .f-input.error{border-color:#ef4444}
    .f-input.error:focus{box-shadow:0 0 0 3px rgba(239,68,68,.12)}

    /* ── Buttons ── */
    .btn-primary{
      display:flex;align-items:center;justify-content:center;gap:.5rem;
      width:100%;padding:.85rem 1.5rem;border-radius:.875rem;
      font-weight:600;font-size:.95rem;font-family:inherit;
      background:#2563eb;color:#fff;border:none;cursor:pointer;
      transition:background .15s,transform .1s,box-shadow .15s;
      letter-spacing:.01em;
    }
    .btn-primary:hover:not(:disabled){background:#1d4ed8;box-shadow:0 4px 16px rgba(37,99,235,.35)}
    .btn-primary:active:not(:disabled){transform:scale(.98)}
    .btn-primary:disabled{opacity:.55;cursor:not-allowed;transform:none}
    .btn-google{
      display:flex;align-items:center;justify-content:center;gap:.75rem;
      width:100%;padding:.8rem 1.5rem;border-radius:.875rem;
      font-weight:600;font-size:.9rem;font-family:inherit;
      background:#fff;color:#1e293b;border:1.5px solid #e2e8f0;
      cursor:pointer;transition:all .15s;text-decoration:none;
    }
    .btn-google:hover{background:#f8fafc;border-color:#cbd5e1;box-shadow:0 2px 12px rgba(0,0,0,.09)}
    .dark .btn-google{background:hsl(222,47%,13%);color:#f1f5f9;border-color:rgba(255,255,255,.12)}
    .dark .btn-google:hover{background:hsl(222,47%,16%)}

    /* ── Divider ── */
    .or-divider{display:flex;align-items:center;gap:.75rem;color:#94a3b8;font-size:.8rem;font-weight:500;letter-spacing:.04em}
    .or-divider::before,.or-divider::after{content:'';flex:1;height:1px;background:#e2e8f0}
    .dark .or-divider::before,.dark .or-divider::after{background:rgba(255,255,255,.08)}

    /* ── Toast ── */
    .toast-wrap{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none}
    .toast{
      padding:.75rem 1.25rem;border-radius:.75rem;color:#fff;font-size:.875rem;
      background:#1e293b;border-left:4px solid #2563eb;
      box-shadow:0 8px 32px rgba(0,0,0,.35);
      animation:toast-in .25s cubic-bezier(.16,1,.3,1) forwards;
      pointer-events:auto;
    }
    .toast.ok{border-left-color:#22c55e;background:#14532d}
    .toast.err{border-left-color:#ef4444;background:#450a0a}
    @keyframes toast-in{from{opacity:0;transform:translateY(.5rem) scale(.97)}to{opacity:1;transform:none}}

    /* ── Feature pill ── */
    .feat-row{display:flex;align-items:flex-start;gap:.875rem}
    .feat-icon{
      width:2.5rem;height:2.5rem;border-radius:.75rem;
      display:flex;align-items:center;justify-content:center;
      flex-shrink:0;font-size:.875rem;
    }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar{width:4px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(100,116,139,.3);border-radius:9999px}
  </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-[hsl(222,47%,4%)] flex">
<div class="toast-wrap" id="toastWrap"></div>

<div class="flex w-full min-h-screen">

  <!-- ═══════════════════════════════════════════════════
       LEFT — Brand Panel  (hidden on mobile)
  ═══════════════════════════════════════════════════ -->
  <aside class="hidden lg:flex lg:w-[44%] xl:w-[42%] brand-panel flex-col justify-between p-10 xl:p-14 relative overflow-hidden"
         aria-label="TT Electro Store branding">

    <!-- Decorative blobs -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
      <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full blur-3xl" style="background:rgba(37,99,235,.12)"></div>
      <div class="absolute bottom-10 -left-16 w-72 h-72 rounded-full blur-3xl" style="background:rgba(232,105,43,.08)"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full blur-3xl" style="background:rgba(6,182,212,.05)"></div>
    </div>

    <!-- Top: Logo + Headline -->
    <div class="relative z-10">
      <a href="/" class="inline-flex items-center gap-3 mb-12" aria-label="Go to TT Electro Store homepage">
        <img src="/assets/logo-icon.png" alt="TT Electro Store Icon"
             class="h-10 w-auto" style="filter:brightness(0) invert(1)">
        <img src="/assets/logo.png" alt="TT Electro Store"
             class="h-8 w-auto" style="filter:brightness(0) invert(1)">
      </a>

      <h2 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight mb-3 tracking-tight">
        India's Trusted<br>Electronics Partner
      </h2>
      <p class="text-slate-400 text-sm leading-relaxed mb-10 max-w-xs">
        Arduino, ESP32, Raspberry Pi, sensors, 3D printing &amp; DIY kits — delivered fast to 28,000+ pin codes.
      </p>

      <!-- Features -->
      <div class="space-y-5">
        <?php foreach([
          ['fa-truck-fast','#3b82f6','Free Shipping above ₹999','Delivered to 28,000+ pin codes across India'],
          ['fa-shield-halved','#22c55e','100% Genuine Products','Sourced directly from authorised distributors'],
          ['fa-headset','#e8692b','Expert Support Available','Mon–Sat, 9 AM – 6 PM · +91 7721892429'],
          ['fa-rotate-left','#a78bfa','7-Day Hassle-Free Returns','On defective or damaged items — no questions asked'],
        ] as [$icon,$color,$title,$sub]): ?>
        <div class="feat-row">
          <div class="feat-icon" style="background:<?= $color ?>1a;border:1px solid <?= $color ?>30">
            <i class="fa-solid <?= $icon ?>" style="color:<?= $color ?>"></i>
          </div>
          <div>
            <p class="text-white font-semibold text-sm"><?= $title ?></p>
            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed"><?= $sub ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Bottom: Testimonial -->
    <div class="relative z-10">
      <div class="rounded-2xl p-5 border" style="background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.09)">
        <div class="flex items-center gap-1 mb-3">
          <?php for($i=0;$i<5;$i++): ?>
          <i class="fa-solid fa-star text-amber-400 text-xs"></i>
          <?php endfor; ?>
          <span class="text-slate-500 text-xs ml-1.5">5.0</span>
        </div>
        <p class="text-slate-300 text-sm italic leading-relaxed mb-4">
          "Received my Arduino kit within 2 days — original packaging, well protected. The component quality is excellent. My go-to store for all electronics needs!"
        </p>
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
               style="background:linear-gradient(135deg,#2563eb,#06b6d4)">A</div>
          <div>
            <p class="text-white text-xs font-semibold">Arjun Mehta</p>
            <p class="text-slate-500 text-[11px]">Electronics Hobbyist · Pune</p>
          </div>
        </div>
      </div>

      <!-- Trust badges -->
      <div class="flex items-center gap-4 mt-5">
        <div class="flex items-center gap-1.5 text-slate-500 text-xs">
          <i class="fa-solid fa-lock text-slate-600 text-[10px]"></i> SSL Secured
        </div>
        <div class="flex items-center gap-1.5 text-slate-500 text-xs">
          <i class="fa-solid fa-certificate text-slate-600 text-[10px]"></i> GST Registered
        </div>
        <div class="flex items-center gap-1.5 text-slate-500 text-xs">
          <i class="fa-solid fa-users text-slate-600 text-[10px]"></i> 15K+ Customers
        </div>
      </div>
    </div>
  </aside>

  <!-- ═══════════════════════════════════════════════════
       RIGHT — Form Panel
  ═══════════════════════════════════════════════════ -->
  <main class="flex-1 flex flex-col items-center justify-center p-6 sm:p-8 lg:p-12 min-h-screen overflow-y-auto"
        role="main">

    <!-- Mobile logo (shown only on small screens) -->
    <div class="lg:hidden mb-8 text-center">
      <a href="/" class="inline-flex items-center gap-2.5" aria-label="TT Electro Store">
        <img src="/assets/logo-icon.png" alt="TT" class="h-9 w-auto dark:brightness-200">
        <img src="/assets/logo.png" alt="TT Electro Store" class="h-7 w-auto dark:brightness-200">
      </a>
    </div>

    <div class="w-full max-w-md" x-data="loginForm()">

      <!-- Header -->
      <header class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome back!</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">
          Sign in to track orders, manage wishlist &amp; more
        </p>
      </header>

      <!-- Flash error from OAuth -->
      <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="mb-5 px-4 py-3.5 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm flex items-start gap-2.5" role="alert">
        <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
        <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
      </div>
      <?php unset($_SESSION['flash_error']); endif; ?>

      <!-- ── Google OAuth ── -->
      <?php $googleOk = !empty(getenv('GOOGLE_CLIENT_ID')); ?>
      <?php if ($googleOk): ?>
      <a href="/auth/google" class="btn-google mb-5"
         aria-label="Sign in with Google">
        <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
          <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
          <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
          <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
          <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
        </svg>
        Continue with Google
      </a>
      <div class="or-divider mb-5">OR SIGN IN WITH EMAIL</div>
      <?php endif; ?>

      <!-- ── Email ── -->
      <form @submit.prevent="step===1 ? nextStep() : submit()" novalidate>
      <div class="space-y-4 mb-5">

        <!-- Email field -->
        <div>
          <label for="login-email" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">
            Email Address
          </label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
              <i class="fa-regular fa-envelope text-sm"></i>
            </span>
            <input id="login-email"
                   type="email"
                   x-model="email"
                   @keydown.enter="step===1 ? nextStep() : submit()"
                   placeholder="you@example.com"
                   class="f-input"
                   :class="fieldError==='email' ? 'error' : ''"
                   autocomplete="email"
                   autocapitalize="none"
                   spellcheck="false"
                   inputmode="email"
                   aria-required="true"
                   aria-describedby="email-hint">
          </div>
        </div>

        <!-- Password field (step 2) -->
        <div x-show="step===2"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0">
          <div class="flex items-center justify-between mb-1.5">
            <label for="login-password" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
              Password
            </label>
            <a href="/forgot-password"
               class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium"
               tabindex="0">
              Forgot password?
            </a>
          </div>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input id="login-password"
                   :type="showPw ? 'text' : 'password'"
                   x-model="password"
                   @keydown.enter="submit()"
                   placeholder="Your password"
                   class="f-input pr-12"
                   :class="fieldError==='password' ? 'error' : ''"
                   autocomplete="current-password"
                   aria-required="true">
            <button type="button"
                    @click="showPw=!showPw"
                    :aria-label="showPw ? 'Hide password' : 'Show password'"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1">
              <i :class="showPw ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-base" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Inline error message -->
      <div x-show="error" x-cloak x-transition
           class="mb-4 flex items-start gap-2.5 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-4 py-3 rounded-xl"
           role="alert" aria-live="polite">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0 text-xs" aria-hidden="true"></i>
        <span x-text="error"></span>
      </div>

      <!-- CTA Button -->
      <button @click="step===1 ? nextStep() : submit()"
              :disabled="loading"
              class="btn-primary mb-3"
              :aria-label="step===1 ? 'Continue to password' : 'Sign in'"
              type="button">
        <span x-show="!loading" class="flex items-center gap-2">
          <i :class="step===1 ? 'fa-solid fa-arrow-right' : 'fa-solid fa-right-to-bracket'" class="text-sm" aria-hidden="true"></i>
          <span x-text="step===1 ? 'Continue' : 'Sign In'"></span>
        </span>
        <span x-show="loading" x-cloak class="flex items-center gap-2">
          <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
          <span>Signing in…</span>
        </span>
      </button>

      <!-- Back link (step 2) -->
      <button x-show="step===2" x-cloak
              @click="step=1; error=''; fieldError=''"
              class="w-full text-center text-sm text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 mb-4 transition-colors py-1"
              type="button">
        <i class="fa-solid fa-chevron-left text-[10px] mr-1" aria-hidden="true"></i>
        Use a different email
      </button>

      <!-- Register link -->
      <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        Don't have an account?
        <a href="/register" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline ml-0.5">Create one free</a>
      </p>

      <!-- Helpline -->
      <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/8 text-center">
        <p class="text-xs text-slate-400 dark:text-slate-500 mb-1.5">Need help signing in?</p>
        <a href="tel:+917721892429"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
           aria-label="Call TT Electro Store helpline">
          <i class="fa-solid fa-phone-volume text-xs" aria-hidden="true"></i>
          +91 7721892429
        </a>
        <p class="text-[11px] text-slate-400 mt-0.5">Mon–Sat, 9 AM – 6 PM</p>
      </div>

      <!-- Security note -->
      <div class="mt-5 flex items-center justify-center gap-1.5 text-xs text-slate-400 dark:text-slate-600">
        <i class="fa-solid fa-lock text-[10px]" aria-hidden="true"></i>
        <span>Protected by 256-bit SSL encryption</span>
      </div>

    </div><!-- /form wrapper -->
  </main>
</div><!-- /flex -->

<script>
function showToast(msg, type='info') {
  const c = document.getElementById('toastWrap');
  if (!c) return;
  const t = document.createElement('div');
  t.className = 'toast' + (type==='success'?' ok':type==='error'?' err':'');
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => t.remove(), 4000);
}

async function apiFetch(url, opts = {}) {
  const res  = await fetch(url, {
    headers: { 'Content-Type': 'application/json', ...(opts.headers || {}) },
    credentials: 'same-origin',
    ...opts,
  });
  const data = await res.json();
  if (!res.ok) throw new Error(data.message || 'Request failed');
  return data.data !== undefined ? data.data : data;
}

function loginForm() {
  return {
    step: 1,
    email: '',
    password: '',
    showPw: false,
    loading: false,
    error: '',
    fieldError: '',

    async nextStep() {
      this.error = ''; this.fieldError = '';
      if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
        this.error = 'Please enter a valid email address.';
        this.fieldError = 'email';
        return;
      }
      this.step = 2;
      await this.$nextTick();
      document.getElementById('login-password')?.focus();
    },

    async submit() {
      this.error = ''; this.fieldError = '';
      if (!this.password) {
        this.error = 'Please enter your password.';
        this.fieldError = 'password';
        return;
      }
      this.loading = true;
      try {
        await apiFetch('/api/auth/login', {
          method: 'POST',
          body: JSON.stringify({ email: this.email, password: this.password }),
        });
        const p = new URLSearchParams(window.location.search);
        const r = p.get('redirect');
        window.location.href = (r && r.startsWith('/') && !r.startsWith('//')) ? r : '/dashboard';
      } catch (e) {
        this.error = e.message || 'Invalid email or password. Please try again.';
        this.fieldError = 'password';
      }
      this.loading = false;
    },
  };
}
</script>
</body>
</html>
