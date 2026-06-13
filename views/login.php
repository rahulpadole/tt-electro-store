<!DOCTYPE html>
<html lang="en" x-data x-init="
  (function(){
    const saved   = localStorage.getItem('theme');
    const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark  = saved ? saved === 'dark' : sysDark;
    document.documentElement.classList.toggle('dark',  isDark);
    document.documentElement.classList.toggle('light', !isDark);
  })();
">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Sign In – <?= APP_NAME ?></title>
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <style>
    body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased}
    .input-field{width:100%;padding:.75rem 1rem;border-radius:.75rem;font-size:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;transition:border-color .15s,box-shadow .15s;outline:none}
    .input-field::placeholder{color:#94a3b8}
    .input-field:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .input-field{background:hsl(222,47%,13%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .input-field::placeholder{color:#475569}
    .dark .input-field:focus{border-color:#3b82f6}
    .btn-primary{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.8rem 1.5rem;border-radius:.75rem;font-weight:600;font-size:.925rem;background:#2563eb;color:#fff;border:none;cursor:pointer;transition:background .15s,transform .1s,box-shadow .15s}
    .btn-primary:hover{background:#1d4ed8;box-shadow:0 4px 14px rgba(37,99,235,.35)}
    .btn-primary:active{transform:scale(.98)}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}
    .btn-google{display:flex;align-items:center;justify-content:center;gap:.75rem;width:100%;padding:.8rem 1.5rem;border-radius:.75rem;font-weight:600;font-size:.925rem;background:#fff;color:#1e293b;border:1.5px solid #e2e8f0;cursor:pointer;transition:all .15s}
    .btn-google:hover{background:#f8fafc;border-color:#cbd5e1;box-shadow:0 2px 8px rgba(0,0,0,.08)}
    .dark .btn-google{background:hsl(222,47%,13%);color:#f1f5f9;border-color:rgba(255,255,255,.12)}
    .dark .btn-google:hover{background:hsl(222,47%,16%)}
    .divider{display:flex;align-items:center;gap:.75rem;color:#94a3b8;font-size:.8125rem}
    .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e2e8f0}
    .dark .divider::before,.dark .divider::after{background:rgba(255,255,255,.08)}
    .toast-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem}
    .toast{padding:.75rem 1.25rem;border-radius:.625rem;color:#fff;font-size:.875rem;background:#1e293b;border-left:4px solid #2563eb;box-shadow:0 8px 24px rgba(0,0,0,.3);animation:ti .25s cubic-bezier(.16,1,.3,1)}
    .toast.success{border-left-color:#22c55e;background:#14532d}
    .toast.error{border-left-color:#ef4444;background:#450a0a}
    @keyframes ti{from{opacity:0;transform:translateY(.5rem) scale(.97)}to{opacity:1;transform:none}}
    [x-cloak]{display:none!important}
    .left-panel{background:linear-gradient(145deg,hsl(222,47%,8%) 0%,hsl(222,60%,14%) 100%)}
  </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-[hsl(222,47%,5%)] flex">
<div class="toast-container" id="toastContainer"></div>

<div class="flex w-full min-h-screen">

  <!-- ── Left Branding Panel (hidden on mobile) ─────────────────────────── -->
  <div class="hidden lg:flex lg:w-[42%] left-panel flex-col justify-between p-12 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-20 right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 left-10 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative">
      <a href="/" class="inline-flex items-center gap-3 mb-16">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
          <i class="fa-solid fa-bolt text-white text-base"></i>
        </div>
        <span class="text-white font-bold text-xl"><?= APP_NAME ?></span>
      </a>

      <h2 class="text-3xl font-bold text-white leading-snug mb-3">
        India's #1 Electronics<br>Store for Makers
      </h2>
      <p class="text-slate-400 text-base mb-10">Premium components. Fast delivery. Expert support.</p>

      <div class="space-y-5">
        <?php foreach([
          ['fa-truck-fast','Free shipping on orders above ₹499','Delivered to 28,000+ pin codes'],
          ['fa-shield-check','100% genuine products','Sourced from authorised distributors'],
          ['fa-headset','Helpline: +91 7721892429','Mon–Sat, 9 AM – 6 PM'],
          ['fa-rotate-left','7-day hassle-free returns','On defective or damaged items'],
        ] as [$icon,$title,$sub]): ?>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fa-solid <?= $icon ?> text-blue-400"></i>
          </div>
          <div>
            <p class="text-white font-medium text-sm"><?= $title ?></p>
            <p class="text-slate-500 text-xs mt-0.5"><?= $sub ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="relative">
      <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="flex gap-1 mb-2">
          <?php for($i=0;$i<5;$i++): ?><i class="fa-solid fa-star text-amber-400 text-xs"></i><?php endfor; ?>
        </div>
        <p class="text-slate-300 text-sm italic leading-relaxed">"Received the Arduino starter kit within 3 days. Amazing quality and packaging. Will definitely order again!"</p>
        <div class="flex items-center gap-3 mt-3">
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white text-xs font-bold">R</div>
          <div>
            <p class="text-white text-xs font-semibold">Ravi Kumar</p>
            <p class="text-slate-500 text-[11px]">Maker · Bengaluru</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Right Form Panel ────────────────────────────────────────────────── -->
  <div class="flex-1 flex flex-col items-center justify-center p-6 lg:p-12">
    <!-- Mobile logo -->
    <div class="lg:hidden mb-8 text-center">
      <a href="/" class="inline-flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
          <i class="fa-solid fa-bolt text-white text-sm"></i>
        </div>
        <span class="text-slate-900 dark:text-white font-bold text-lg"><?= APP_NAME ?></span>
      </a>
    </div>

    <div class="w-full max-w-md" x-data="loginForm()">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back!</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Sign in to your account to continue</p>
      </div>

      <!-- Flash error from Google OAuth -->
      <?php if(!empty($_SESSION['flash_error'])): ?>
      <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
        <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
      </div>
      <?php unset($_SESSION['flash_error']); endif; ?>

      <!-- Google Button -->
      <?php $googleConfigured = !empty(getenv('GOOGLE_CLIENT_ID')); ?>
      <?php if($googleConfigured): ?>
      <a href="/auth/google" class="btn-google mb-5 no-underline">
        <svg width="18" height="18" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
        Continue with Google
      </a>
      <div class="divider mb-5">or sign in with email</div>
      <?php else: ?>
      <div class="mb-5 px-4 py-3 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/8 text-slate-500 dark:text-slate-400 text-xs text-center">
        <i class="fa-brands fa-google mr-1.5"></i> Google Sign-In not configured.
        <a href="mailto:support@ttelectro.in" class="text-blue-500 ml-1">Contact support</a>
      </div>
      <?php endif; ?>

      <!-- Email -->
      <div class="space-y-4 mb-5">
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Email Address</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-regular fa-envelope text-sm"></i></span>
            <input type="email" x-model="email" @keydown.enter="step===1?nextStep():submit()"
                   placeholder="you@example.com" class="input-field pl-10">
          </div>
        </div>

        <!-- Password (shown after email step) -->
        <div x-show="step===2" x-transition>
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Password</label>
            <a href="/forgot-password" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Forgot password?</a>
          </div>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span>
            <input :type="showPw?'text':'password'" x-model="password" @keydown.enter="submit()"
                   placeholder="Your password" class="input-field pl-10 pr-12" autocomplete="current-password">
            <button type="button" @click="showPw=!showPw"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs font-medium transition-colors px-1">
              <i :class="showPw?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Error -->
      <p x-show="error" x-text="error" x-cloak
         class="mb-4 text-sm text-red-500 dark:text-red-400 flex items-center gap-1.5">
        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
      </p>

      <!-- Submit Button -->
      <button @click="step===1?nextStep():submit()" :disabled="loading" class="btn-primary mb-4">
        <span x-show="!loading" x-text="step===1?'Continue':'Sign In'"></span>
        <span x-show="loading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" x-cloak></span>
      </button>

      <!-- Back to step 1 -->
      <button x-show="step===2" @click="step=1" x-cloak
              class="w-full text-center text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 mb-4 transition-colors">
        ← Use a different email
      </button>

      <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        Don't have an account?
        <a href="/register" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Create one free</a>
      </p>

      <!-- Helpline -->
      <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/8 text-center">
        <p class="text-xs text-slate-400 dark:text-slate-500 mb-1">Need help signing in?</p>
        <a href="tel:+917721892429" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
          <i class="fa-solid fa-phone text-xs mr-1.5"></i>+91 7721892429
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function showToast(msg,type='info'){const c=document.getElementById('toastContainer');if(!c)return;const t=document.createElement('div');t.className='toast '+(type==='success'?'success':type==='error'?'error':'');t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4000);}
async function apiFetch(url,opts={}){const res=await fetch(url,{headers:{'Content-Type':'application/json',...(opts.headers||{})},credentials:'same-origin',...opts});const data=await res.json();if(!res.ok)throw new Error(data.message||'Request failed');return data.data!==undefined?data.data:data;}

function loginForm(){
  return {
    step: 1, email: '', password: '', showPw: false, loading: false, error: '',
    async nextStep(){
      this.error='';
      if(!this.email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)){this.error='Enter a valid email address';return;}
      this.step=2;
      this.$nextTick(()=>document.querySelector('input[type="password"]')?.focus());
    },
    async submit(){
      this.error=''; this.loading=true;
      try{
        await apiFetch('/api/auth/login',{method:'POST',body:JSON.stringify({email:this.email,password:this.password})});
        window.location='/dashboard';
      }catch(e){this.error=e.message;}
      this.loading=false;
    }
  }
}
</script>
</body>
</html>
