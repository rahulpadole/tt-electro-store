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

  <title>Create Account – <?= APP_NAME ?> | Join India's Electronics Community</title>
  <meta name="description" content="Create your free <?= APP_NAME ?> account. Join 15,000+ makers across India for the best deals on Arduino, Raspberry Pi, sensors, and electronic components.">

  <!-- Canonical -->
  <link rel="canonical" href="<?= APP_URL ?>/register">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= APP_URL ?>/register">
  <meta property="og:title"       content="Create Account – <?= APP_NAME ?>">
  <meta property="og:description" content="Join 15,000+ makers. Get exclusive deals on electronics, Arduino, ESP32, sensors and more. Free delivery above ₹999.">
  <meta property="og:image"       content="<?= APP_URL ?>/assets/logo.png">
  <meta property="og:site_name"   content="<?= APP_NAME ?>">
  <meta property="og:locale"      content="en_IN">

  <!-- Favicon -->
  <link rel="icon"             href="/assets/logo-icon.png" type="image/png" sizes="any">
  <link rel="apple-touch-icon" href="/assets/logo-icon.png">

  <!-- Performance hints -->
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="preconnect"   href="https://fonts.googleapis.com">
  <link rel="preconnect"   href="https://fonts.gstatic.com" crossorigin>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">

  <!-- Tailwind + Alpine -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif','system-ui','sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <!-- JSON-LD -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Create Account – <?= APP_NAME ?>",
    "url": "<?= APP_URL ?>/register",
    "description": "Create your free TT Electro Store account and join India's electronics maker community.",
    "isPartOf": {
      "@type": "WebSite",
      "name": "<?= APP_NAME ?>",
      "url": "<?= APP_URL ?>"
    }
  }
  </script>

  <style>
    *,*::before,*::after{box-sizing:border-box}
    html{font-size:16px;-webkit-text-size-adjust:100%}
    body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;margin:0}
    [x-cloak]{display:none!important}

    .brand-panel{background:linear-gradient(150deg,#0d1b2e 0%,#112240 40%,#0d2137 70%,#091828 100%)}

    /* ── Inputs ── */
    .f-input{
      width:100%;padding:.75rem 1rem .75rem 2.75rem;
      border-radius:.875rem;font-size:.9rem;
      border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;
      transition:border-color .15s,box-shadow .15s,background .15s;
      outline:none;font-family:inherit;
    }
    .f-input.no-icon{padding-left:1rem}
    .f-input::placeholder{color:#94a3b8}
    .f-input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);background:#fff}
    .dark .f-input{background:hsl(222,47%,11%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .f-input::placeholder{color:#475569}
    .dark .f-input:focus{border-color:#3b82f6;background:hsl(222,47%,13%);box-shadow:0 0 0 3px rgba(59,130,246,.15)}
    .f-input.err{border-color:#ef4444}
    .f-input.ok{border-color:#22c55e}

    /* ── Buttons ── */
    .btn-primary{
      display:flex;align-items:center;justify-content:center;gap:.5rem;
      width:100%;padding:.85rem 1.5rem;border-radius:.875rem;
      font-weight:600;font-size:.95rem;font-family:inherit;
      background:#2563eb;color:#fff;border:none;cursor:pointer;
      transition:background .15s,transform .1s,box-shadow .15s;
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
    .btn-secondary{
      display:flex;align-items:center;justify-content:center;gap:.5rem;
      width:100%;padding:.85rem 1.5rem;border-radius:.875rem;
      font-weight:600;font-size:.95rem;font-family:inherit;
      background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;border:none;cursor:pointer;
      transition:opacity .15s,transform .1s,box-shadow .15s;
    }
    .btn-secondary:hover:not(:disabled){opacity:.9;box-shadow:0 4px 20px rgba(37,99,235,.40)}
    .btn-secondary:active:not(:disabled){transform:scale(.98)}
    .btn-secondary:disabled{opacity:.55;cursor:not-allowed;transform:none}

    /* ── Or divider ── */
    .or-divider{display:flex;align-items:center;gap:.75rem;color:#94a3b8;font-size:.8rem;font-weight:500;letter-spacing:.04em}
    .or-divider::before,.or-divider::after{content:'';flex:1;height:1px;background:#e2e8f0}
    .dark .or-divider::before,.dark .or-divider::after{background:rgba(255,255,255,.08)}

    /* ── Progress stepper ── */
    .step-dot{
      width:2rem;height:2rem;border-radius:9999px;
      display:flex;align-items:center;justify-content:center;
      font-size:.75rem;font-weight:700;
      transition:all .25s;flex-shrink:0;
    }
    .step-line{flex:1;height:2px;border-radius:9999px;transition:background .25s}

    /* ── OTP boxes ── */
    .otp-box{
      width:3rem;height:3.25rem;text-align:center;
      font-size:1.375rem;font-weight:700;
      border-radius:.75rem;border:2px solid #e2e8f0;
      background:#f8fafc;color:#0f172a;outline:none;
      transition:border-color .15s,box-shadow .15s;
      font-family:inherit;
    }
    .otp-box:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .otp-box{background:hsl(222,47%,11%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .otp-box:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.15)}

    /* ── Password strength bar ── */
    .pw-bar{height:4px;border-radius:9999px;transition:all .3s;flex:1}

    /* ── Toast ── */
    .toast-wrap{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none}
    .toast{padding:.75rem 1.25rem;border-radius:.75rem;color:#fff;font-size:.875rem;background:#1e293b;border-left:4px solid #2563eb;box-shadow:0 8px 32px rgba(0,0,0,.35);animation:toast-in .25s cubic-bezier(.16,1,.3,1) forwards;pointer-events:auto}
    .toast.ok{border-left-color:#22c55e;background:#14532d}
    .toast.err{border-left-color:#ef4444;background:#450a0a}
    @keyframes toast-in{from{opacity:0;transform:translateY(.5rem) scale(.97)}to{opacity:1;transform:none}}

    /* ── India flag pill ── */
    .flag-pill{
      display:flex;align-items:center;padding:.75rem .875rem;
      border-radius:.875rem 0 0 .875rem;
      border:1.5px solid #e2e8f0;border-right:0;
      background:#f8fafc;color:#334155;font-size:.9rem;font-weight:600;
      flex-shrink:0;gap:.5rem;white-space:nowrap;
    }
    .dark .flag-pill{background:hsl(222,47%,11%);border-color:rgba(255,255,255,.1);color:#cbd5e1}
    .f-input.phone-r{border-radius:0 .875rem .875rem 0;padding-left:1rem}

    ::-webkit-scrollbar{width:4px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(100,116,139,.3);border-radius:9999px}

    .field-group{display:flex;flex-direction:column;gap:1.25rem}
    .field-label{display:block;font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.375rem}
    .dark .field-label{color:#94a3b8}
  </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-[hsl(222,47%,4%)] flex">
<div class="toast-wrap" id="toastWrap"></div>

<div class="flex w-full min-h-screen">

  <!-- ═══════════════════════════════════════════════════
       LEFT — Brand Panel
  ═══════════════════════════════════════════════════ -->
  <aside class="hidden lg:flex lg:w-[40%] xl:w-[38%] brand-panel flex-col justify-between p-10 xl:p-14 relative overflow-hidden"
         aria-label="TT Electro Store benefits">

    <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
      <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full blur-3xl" style="background:rgba(37,99,235,.10)"></div>
      <div class="absolute bottom-0 -left-20 w-72 h-72 rounded-full blur-3xl" style="background:rgba(232,105,43,.07)"></div>
      <div class="absolute top-2/3 right-10 w-48 h-48 rounded-full blur-3xl" style="background:rgba(6,182,212,.06)"></div>
    </div>

    <div class="relative z-10">
      <a href="/" class="inline-flex items-center gap-3 mb-12" aria-label="TT Electro Store homepage">
        <img src="/assets/logo-icon.png" alt="TT Electro" class="h-10 w-auto" style="filter:brightness(0) invert(1)">
        <img src="/assets/logo.png" alt="TT Electro Store" class="h-8 w-auto" style="filter:brightness(0) invert(1)">
      </a>

      <h2 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight mb-3 tracking-tight">
        Join India's #1<br>Electronics Community
      </h2>
      <p class="text-slate-400 text-sm leading-relaxed mb-10 max-w-xs">
        Over 15,000 makers, students &amp; engineers trust TT Electro Store for all their component needs.
      </p>

      <!-- Perks list -->
      <div class="space-y-4">
        <?php foreach([
          ['fa-gift',         '#e8692b', 'Exclusive Member Deals',      '10% off on first order + regular member-only flash sales'],
          ['fa-truck-fast',   '#3b82f6', 'Free Shipping from ₹999',     'Delivered across 28,000+ pin codes via Delhivery'],
          ['fa-bell',         '#a78bfa', 'Order & Dispatch Alerts',     'Instant SMS + email updates at every shipment stage'],
          ['fa-heart',        '#f43f5e', 'Wishlist & Order History',    'Save items, reorder easily, track all past purchases'],
          ['fa-clock-rotate-left','#22c55e','Priority Customer Support','Faster resolution for registered members'],
        ] as [$icon,$color,$title,$sub]): ?>
        <div class="flex items-start gap-3.5">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 text-sm"
               style="background:<?= $color ?>18;border:1px solid <?= $color ?>28">
            <i class="fa-solid <?= $icon ?>" style="color:<?= $color ?>"></i>
          </div>
          <div>
            <p class="text-white text-sm font-semibold"><?= $title ?></p>
            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed"><?= $sub ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="relative z-10">
      <!-- Social proof numbers -->
      <div class="grid grid-cols-3 gap-4 mb-5">
        <?php foreach([
          ['15K+','Happy Customers'],
          ['25K+','Orders Shipped'],
          ['4.9★','Avg. Rating'],
        ] as [$num,$lbl]): ?>
        <div class="text-center rounded-xl p-3" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)">
          <p class="text-white font-extrabold text-lg leading-none mb-0.5"><?= $num ?></p>
          <p class="text-slate-500 text-[10px] font-medium leading-tight"><?= $lbl ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Trust -->
      <div class="flex items-center gap-4 pt-4 border-t" style="border-color:rgba(255,255,255,.07)">
        <div class="flex items-center gap-1.5 text-slate-500 text-xs"><i class="fa-solid fa-lock text-[10px]"></i> SSL Secured</div>
        <div class="flex items-center gap-1.5 text-slate-500 text-xs"><i class="fa-solid fa-building-columns text-[10px]"></i> GST Registered</div>
        <div class="flex items-center gap-1.5 text-slate-500 text-xs"><i class="fa-solid fa-map-location-dot text-[10px]"></i> Amravati, MH</div>
      </div>
    </div>
  </aside>

  <!-- ═══════════════════════════════════════════════════
       RIGHT — Form Panel
  ═══════════════════════════════════════════════════ -->
  <main class="flex-1 flex flex-col items-center justify-start lg:justify-center p-6 sm:p-8 overflow-y-auto"
        role="main">

    <div class="w-full max-w-md py-8 lg:py-0" x-data="registerForm()">

      <!-- Mobile logo -->
      <div class="lg:hidden mb-7 text-center">
        <a href="/" class="inline-flex items-center gap-2.5" aria-label="TT Electro Store">
          <img src="/assets/logo-icon.png" alt="TT" class="h-9 w-auto dark:brightness-200">
          <img src="/assets/logo.png" alt="TT Electro Store" class="h-7 w-auto dark:brightness-200">
        </a>
      </div>

      <!-- Header -->
      <header class="mb-7">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Create your account</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">
          Already a member?
          <a href="/login" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Sign In →</a>
        </p>
      </header>

      <!-- ── Progress Stepper ── -->
      <div class="flex items-center gap-2 mb-7" role="progressbar" :aria-valuenow="step" aria-valuemin="1" aria-valuemax="2">
        <!-- Step 1 -->
        <div class="flex items-center gap-2">
          <div class="step-dot" :class="step>=1 ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-400'">
            <i x-show="step>1" class="fa-solid fa-check text-[10px]" x-cloak aria-hidden="true"></i>
            <span x-show="step<=1" aria-hidden="true">1</span>
          </div>
          <span class="text-xs font-semibold transition-colors hidden sm:block"
                :class="step>=1 ? 'text-slate-700 dark:text-slate-200' : 'text-slate-400 dark:text-slate-600'">
            Basic Info
          </span>
        </div>

        <div class="step-line" :class="step>=2 ? 'bg-blue-600' : 'bg-slate-200 dark:bg-white/10'"></div>

        <!-- Step 2 -->
        <div class="flex items-center gap-2">
          <div class="step-dot" :class="step>=2 ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-400'">
            <span aria-hidden="true">2</span>
          </div>
          <span class="text-xs font-semibold transition-colors hidden sm:block"
                :class="step>=2 ? 'text-slate-700 dark:text-slate-200' : 'text-slate-400 dark:text-slate-600'">
            Verify Mobile
          </span>
        </div>
      </div>

      <!-- ══════════════════════════════════════
           STEP 1 — Basic Info
      ══════════════════════════════════════ -->
      <div x-show="step===1"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-x-3"
           x-transition:enter-end="opacity-100 translate-x-0">

        <!-- Google Sign-up -->
        <?php if (!empty(getenv('GOOGLE_CLIENT_ID'))): ?>
        <a href="/auth/google?redirect=/dashboard" class="btn-google mb-5" aria-label="Sign up with Google">
          <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
            <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
            <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
            <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
            <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
          </svg>
          Sign up with Google
        </a>
        <div class="or-divider mb-5">OR SIGN UP WITH EMAIL</div>
        <?php endif; ?>

        <!-- Form fields -->
        <form @submit.prevent="nextStep()" novalidate>
        <div class="field-group">

          <!-- Full Name -->
          <div>
            <label for="reg-name" class="field-label">Full Name</label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
                <i class="fa-regular fa-user text-sm"></i>
              </span>
              <input id="reg-name" type="text" x-model="name"
                     placeholder="Tejas Sharma"
                     class="f-input" :class="fieldErrors.name ? 'err' : name.length>2 ? 'ok' : ''"
                     autocomplete="name" autocapitalize="words" spellcheck="false"
                     aria-required="true" aria-describedby="name-error">
            </div>
            <p x-show="fieldErrors.name" x-text="fieldErrors.name" x-cloak id="name-error"
               class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
            </p>
          </div>

          <!-- Email -->
          <div>
            <label for="reg-email" class="field-label">Email Address</label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
                <i class="fa-regular fa-envelope text-sm"></i>
              </span>
              <input id="reg-email" type="email" x-model="email"
                     placeholder="you@example.com"
                     class="f-input" :class="fieldErrors.email ? 'err' : ''"
                     autocomplete="email" autocapitalize="none" inputmode="email" spellcheck="false"
                     aria-required="true">
            </div>
            <p x-show="fieldErrors.email" x-text="fieldErrors.email" x-cloak
               class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
            </p>
          </div>

          <!-- Password -->
          <div>
            <label for="reg-password" class="field-label">Password</label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
                <i class="fa-solid fa-lock text-sm"></i>
              </span>
              <input id="reg-password" :type="showPw ? 'text' : 'password'" x-model="password"
                     @input="calcStrength()"
                     placeholder="Min. 8 characters"
                     class="f-input pr-12" :class="fieldErrors.password ? 'err' : ''"
                     autocomplete="new-password" aria-required="true"
                     aria-describedby="pw-strength-label">
              <button type="button" @click="showPw=!showPw"
                      :aria-label="showPw ? 'Hide password' : 'Show password'"
                      class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1">
                <i :class="showPw ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-base" aria-hidden="true"></i>
              </button>
            </div>
            <!-- Strength bar -->
            <div x-show="password.length>0" x-cloak class="flex gap-1 mt-2" aria-hidden="true">
              <template x-for="i in 4" :key="i">
                <div class="pw-bar" :class="{
                  'bg-red-500':   strength < 2 && i <= strength,
                  'bg-amber-500': strength === 2 && i <= strength,
                  'bg-green-500': strength >= 3 && i <= strength,
                  'bg-slate-200 dark:bg-white/10': i > strength
                }"></div>
              </template>
            </div>
            <p x-show="password.length>0" x-cloak id="pw-strength-label"
               class="text-xs mt-0.5 font-medium transition-colors"
               :class="{'text-red-500':strength<2,'text-amber-500':strength===2,'text-green-600 dark:text-green-400':strength>=3}"
               x-text="['','Weak — add uppercase &amp; numbers','Fair — add a symbol','Strong','Very Strong'][strength]||''"></p>
            <p x-show="fieldErrors.password" x-text="fieldErrors.password" x-cloak
               class="text-red-500 text-xs mt-1"></p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="reg-confirm" class="field-label">Confirm Password</label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true">
                <i class="fa-solid fa-lock-open text-sm"></i>
              </span>
              <input id="reg-confirm" :type="showConfirm ? 'text' : 'password'" x-model="confirmPassword"
                     @keydown.enter="nextStep()"
                     placeholder="Re-enter your password"
                     class="f-input pr-12"
                     :class="confirmPassword && confirmPassword !== password ? 'err' : confirmPassword && confirmPassword === password ? 'ok' : ''"
                     autocomplete="new-password" aria-required="true">
              <button type="button" @click="showConfirm=!showConfirm"
                      :aria-label="showConfirm ? 'Hide confirm password' : 'Show confirm password'"
                      class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1">
                <i :class="showConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-base" aria-hidden="true"></i>
              </button>
            </div>
            <p x-show="confirmPassword && confirmPassword !== password" x-cloak
               class="text-red-500 text-xs mt-1">Passwords don't match</p>
            <p x-show="confirmPassword && confirmPassword === password" x-cloak
               class="text-green-600 dark:text-green-400 text-xs mt-1 flex items-center gap-1">
              <i class="fa-solid fa-check-circle text-[10px]"></i> Passwords match
            </p>
          </div>
        </div>

        <!-- Global error -->
        <div x-show="error" x-cloak x-transition
             class="mt-4 flex items-start gap-2.5 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-4 py-3 rounded-xl"
             role="alert" aria-live="polite">
          <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0 text-xs" aria-hidden="true"></i>
          <span x-text="error"></span>
        </div>

        <button @click="nextStep()" :disabled="loading" class="btn-primary mt-5" type="button">
          <span x-show="!loading" class="flex items-center gap-2">
            <i class="fa-solid fa-arrow-right text-sm" aria-hidden="true"></i>
            Continue to Verify Mobile
          </span>
          <span x-show="loading" x-cloak class="flex items-center gap-2">
            <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
            Checking…
          </span>
        </button>

        <!-- Terms -->
        <p class="text-xs text-center text-slate-400 dark:text-slate-500 mt-4 leading-relaxed">
          By creating an account you agree to our
          <a href="/terms" class="text-blue-500 hover:underline" target="_blank" rel="noopener">Terms of Service</a>
          &amp;
          <a href="/privacy-policy" class="text-blue-500 hover:underline" target="_blank" rel="noopener">Privacy Policy</a>
        </p>
      </div>

      <!-- ══════════════════════════════════════
           STEP 2 — Mobile Verification
      ══════════════════════════════════════ -->
      <div x-show="step===2" x-cloak
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-x-3"
           x-transition:enter-end="opacity-100 translate-x-0">

        <!-- Header -->
        <div class="text-center mb-6">
          <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-mobile-screen-button text-blue-600 dark:text-blue-400 text-2xl" aria-hidden="true"></i>
          </div>
          <h2 class="text-lg font-bold text-slate-900 dark:text-white">Verify your mobile number</h2>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
            Required for account security &amp; order shipment alerts via SMS
          </p>
        </div>

        <!-- Phone input -->
        <div x-show="!otpSent">
          <label for="reg-phone" class="field-label">Mobile Number</label>
          <div class="flex">
            <div class="flag-pill" aria-label="Country code India +91">
              <span aria-hidden="true">🇮🇳</span>
              <span>+91</span>
            </div>
            <input id="reg-phone"
                   type="tel"
                   x-model="phone"
                   @keydown.enter="sendOtp()"
                   @input="phone = phone.replace(/\D/g,'').slice(0,10)"
                   placeholder="98765 43210"
                   maxlength="10"
                   inputmode="numeric"
                   pattern="[0-9]{10}"
                   class="f-input phone-r"
                   :class="phone.length===10 ? 'ok' : ''"
                   autocomplete="tel-national"
                   aria-required="true">
          </div>
          <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 flex items-center gap-1">
            <i class="fa-solid fa-info-circle text-[10px]" aria-hidden="true"></i>
            OTP will be sent via SMS to this number
          </p>

          <button @click="sendOtp()" :disabled="otpSending || phone.length < 10"
                  class="btn-primary mt-4" type="button">
            <span x-show="!otpSending" class="flex items-center gap-2">
              <i class="fa-solid fa-paper-plane text-sm" aria-hidden="true"></i>
              Send OTP
            </span>
            <span x-show="otpSending" x-cloak class="flex items-center gap-2">
              <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
              Sending…
            </span>
          </button>
        </div>

        <!-- OTP entry -->
        <div x-show="otpSent" x-cloak>
          <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl px-4 py-3 mb-5 flex items-start gap-2.5">
            <i class="fa-solid fa-circle-info text-blue-500 flex-shrink-0 mt-0.5 text-sm" aria-hidden="true"></i>
            <div>
              <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">OTP sent to <span class="font-bold">+91 <span x-text="phone"></span></span></p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Enter the 6-digit code below. Valid for 10 minutes.</p>
            </div>
          </div>

          <!-- Dev mode OTP (only shown when API returns it) -->
          <div x-show="devOtp" x-cloak
               class="mb-4 px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-sm flex items-center gap-2"
               role="note">
            <i class="fa-solid fa-triangle-exclamation flex-shrink-0" aria-hidden="true"></i>
            <span>Dev mode — OTP: <strong x-text="devOtp"></strong></span>
          </div>

          <label class="field-label" for="otp-input">Enter 6-Digit OTP</label>

          <!-- Single OTP input (better UX on mobile) -->
          <input id="otp-input"
                 type="text"
                 x-model="otpCode"
                 @input="otpCode = otpCode.replace(/\D/g,'').slice(0,6)"
                 @keydown.enter="verifyOtp()"
                 placeholder="· · · · · ·"
                 maxlength="6"
                 inputmode="numeric"
                 pattern="[0-9]{6}"
                 class="f-input no-icon text-center text-2xl font-bold tracking-[.7em] mb-1 py-4"
                 :class="otpCode.length===6 ? 'ok' : ''"
                 autocomplete="one-time-code"
                 aria-required="true"
                 aria-describedby="otp-help">
          <p id="otp-help" class="sr-only">Enter the 6 digit one-time password sent to your phone</p>

          <!-- Resend + change -->
          <div class="flex items-center justify-between mt-3 mb-4">
            <button @click="otpSent=false;otpCode=''"
                    class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors flex items-center gap-1"
                    type="button">
              <i class="fa-solid fa-pen text-[10px]" aria-hidden="true"></i> Change number
            </button>
            <button @click="sendOtp()" :disabled="otpTimer>0 || otpSending"
                    class="text-sm font-medium transition-colors"
                    :class="otpTimer>0 ? 'text-slate-400 cursor-not-allowed' : 'text-blue-600 dark:text-blue-400 hover:underline'"
                    type="button">
              <span x-show="otpTimer>0">Resend in <span x-text="otpTimer"></span>s</span>
              <span x-show="!otpTimer">Resend OTP</span>
            </button>
          </div>

          <!-- Verify button -->
          <div x-show="!otpVerified">
            <button @click="verifyOtp()" :disabled="otpVerifying || otpCode.length < 6"
                    class="btn-primary" type="button">
              <span x-show="!otpVerifying" class="flex items-center gap-2">
                <i class="fa-solid fa-check-double text-sm" aria-hidden="true"></i>
                Verify OTP
              </span>
              <span x-show="otpVerifying" x-cloak class="flex items-center gap-2">
                <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
                Verifying…
              </span>
            </button>
          </div>

          <!-- ✅ Verified state -->
          <div x-show="otpVerified" x-cloak class="text-center py-4">
            <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-500/15 flex items-center justify-center mx-auto mb-3"
                 role="img" aria-label="Phone verified">
              <i class="fa-solid fa-check text-green-600 dark:text-green-400 text-xl" aria-hidden="true"></i>
            </div>
            <p class="text-green-700 dark:text-green-400 font-bold text-sm">Mobile number verified!</p>
            <p class="text-slate-400 text-xs mt-0.5">You're all set — create your account below</p>
          </div>
        </div>

        <!-- Global error -->
        <div x-show="error" x-cloak x-transition
             class="mt-4 flex items-start gap-2.5 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-4 py-3 rounded-xl"
             role="alert" aria-live="polite">
          <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0 text-xs" aria-hidden="true"></i>
          <span x-text="error"></span>
        </div>

        <!-- Create account (only after OTP verified) -->
        <div x-show="otpVerified" x-cloak class="mt-5">
          <button @click="submit()" :disabled="loading" class="btn-secondary" type="button">
            <span x-show="!loading" class="flex items-center gap-2">
              <i class="fa-solid fa-rocket text-sm" aria-hidden="true"></i>
              Create My Account
            </span>
            <span x-show="loading" x-cloak class="flex items-center gap-2">
              <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
              Creating account…
            </span>
          </button>
        </div>

        <button @click="step=1; error=''" class="w-full text-center text-sm text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 mt-3 transition-colors py-1"
                type="button">
          <i class="fa-solid fa-chevron-left text-[10px] mr-1" aria-hidden="true"></i>
          Back to basic info
        </button>
      </div>

      <!-- Bottom link -->
      <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-6">
        Already have an account?
        <a href="/login" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Sign In</a>
      </p>

    </div><!-- /form wrapper -->
  </main>
</div>

<script>
function showToast(msg, type = 'info') {
  const c = document.getElementById('toastWrap');
  if (!c) return;
  const t = document.createElement('div');
  t.className = 'toast' + (type==='success' ? ' ok' : type==='error' ? ' err' : '');
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => t.remove(), 4500);
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

function registerForm() {
  return {
    step: 1,
    name: '', email: '', password: '', confirmPassword: '',
    showPw: false, showConfirm: false, strength: 0,
    phone: '', otpCode: '',
    otpSent: false, otpSending: false,
    otpVerified: false, otpVerifying: false,
    otpTimer: 0, timerInterval: null,
    devOtp: '',
    loading: false, error: '',
    fieldErrors: {},

    calcStrength() {
      const p = this.password;
      let s = 0;
      if (p.length >= 8) s++;
      if (/[A-Z]/.test(p)) s++;
      if (/[0-9]/.test(p)) s++;
      if (/[^A-Za-z0-9]/.test(p)) s++;
      this.strength = s;
    },

    async nextStep() {
      this.error = ''; this.fieldErrors = {};

      // Validate
      if (!this.name.trim() || this.name.trim().length < 2) {
        this.fieldErrors.name = 'Please enter your full name (min. 2 characters)';
        document.getElementById('reg-name')?.focus();
        return;
      }
      if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
        this.fieldErrors.email = 'Please enter a valid email address';
        document.getElementById('reg-email')?.focus();
        return;
      }
      if (this.password.length < 8) {
        this.fieldErrors.password = 'Password must be at least 8 characters';
        document.getElementById('reg-password')?.focus();
        return;
      }
      if (this.password !== this.confirmPassword) {
        this.error = 'Passwords do not match. Please check and try again.';
        document.getElementById('reg-confirm')?.focus();
        return;
      }

      // Check email availability
      this.loading = true;
      try {
        await apiFetch('/api/auth/check-email', {
          method: 'POST',
          body: JSON.stringify({ email: this.email }),
        });
      } catch (e) {
        const msg = e.message.toLowerCase();
        if (msg.includes('already') || msg.includes('409') || msg.includes('registered')) {
          this.fieldErrors.email = 'This email is already registered. Try signing in instead.';
          this.loading = false;
          document.getElementById('reg-email')?.focus();
          return;
        }
        // Other errors — ignore (email check is advisory)
      }
      this.loading = false;
      this.step = 2;
      await this.$nextTick();
      document.getElementById('reg-phone')?.focus();
    },

    async sendOtp() {
      if (this.phone.length < 10) {
        this.error = 'Please enter a valid 10-digit mobile number';
        return;
      }
      this.otpSending = true; this.error = '';
      try {
        const res = await apiFetch('/api/auth/otp/send', {
          method: 'POST',
          body: JSON.stringify({ phone: this.phone, purpose: 'register' }),
        });
        this.otpSent  = true;
        this.devOtp   = res?.otp || '';
        this.startTimer();
        showToast('OTP sent to +91 ' + this.phone, 'success');
        await this.$nextTick();
        document.getElementById('otp-input')?.focus();
      } catch (e) {
        this.error = e.message || 'Failed to send OTP. Please try again.';
      }
      this.otpSending = false;
    },

    startTimer() {
      this.otpTimer = 30;
      clearInterval(this.timerInterval);
      this.timerInterval = setInterval(() => {
        if (this.otpTimer > 0) this.otpTimer--;
        else clearInterval(this.timerInterval);
      }, 1000);
    },

    async verifyOtp() {
      if (this.otpCode.length < 6) {
        this.error = 'Please enter the full 6-digit OTP';
        return;
      }
      this.otpVerifying = true; this.error = '';
      try {
        await apiFetch('/api/auth/otp/verify', {
          method: 'POST',
          body: JSON.stringify({ phone: this.phone, otp: this.otpCode, purpose: 'register' }),
        });
        this.otpVerified = true;
        showToast('Phone number verified!', 'success');
      } catch (e) {
        this.error = e.message || 'Invalid OTP. Please check and try again.';
      }
      this.otpVerifying = false;
    },

    async submit() {
      if (!this.otpVerified) {
        this.error = 'Please verify your mobile number first';
        return;
      }
      this.loading = true; this.error = '';
      try {
        await apiFetch('/api/auth/register', {
          method: 'POST',
          body: JSON.stringify({
            name:     this.name.trim(),
            email:    this.email.trim().toLowerCase(),
            password: this.password,
            phone:    '+91' + this.phone,
          }),
        });
        showToast('Account created! Welcome to TT Electro Store 🎉', 'success');
        setTimeout(() => { window.location.href = '/dashboard'; }, 800);
      } catch (e) {
        this.error = e.message || 'Account creation failed. Please try again.';
        this.loading = false;
      }
    },
  };
}
</script>
</body>
</html>
