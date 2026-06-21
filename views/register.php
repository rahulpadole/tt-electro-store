<!DOCTYPE html>
<html lang="en" x-data x-init="
  (function(){
    const saved=localStorage.getItem('theme'),sysDark=window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark=saved?saved==='dark':sysDark;
    document.documentElement.classList.toggle('dark',isDark);
    document.documentElement.classList.toggle('light',!isDark);
  })();
">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Create Account – <?= APP_NAME ?></title>
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <style>
    body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased}
    .input-field{width:100%;padding:.75rem 1rem .75rem 2.75rem;border-radius:.75rem;font-size:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;transition:border-color .15s,box-shadow .15s;outline:none}
    .input-field.no-icon{padding-left:1rem}
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
    .pw-bar{height:4px;border-radius:9999px;transition:all .3s}
    .otp-box{width:3rem;height:3.25rem;text-align:center;font-size:1.25rem;font-weight:700;border-radius:.625rem;border:2px solid #e2e8f0;background:#f8fafc;color:#0f172a;outline:none;transition:border-color .15s}
    .otp-box:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .otp-box{background:hsl(222,47%,13%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .otp-box:focus{border-color:#3b82f6}
    .toast-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem}
    .toast{padding:.75rem 1.25rem;border-radius:.625rem;color:#fff;font-size:.875rem;background:#1e293b;border-left:4px solid #2563eb;box-shadow:0 8px 24px rgba(0,0,0,.3);animation:ti .25s cubic-bezier(.16,1,.3,1)}
    .toast.success{border-left-color:#22c55e;background:#14532d}
    .toast.error{border-left-color:#ef4444;background:#450a0a}
    @keyframes ti{from{opacity:0;transform:translateY(.5rem) scale(.97)}to{opacity:1;transform:none}}
    [x-cloak]{display:none!important}
  </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-[hsl(222,47%,5%)] flex flex-col items-center justify-center p-4 py-8">
<div class="toast-container" id="toastContainer"></div>

<div class="w-full max-w-md" x-data="registerForm()">

  <!-- Logo -->
  <div class="text-center mb-7">
    <a href="/" class="inline-flex items-center gap-2.5 mb-5">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
        <i class="fa-solid fa-bolt text-white text-sm"></i>
      </div>
      <span class="text-slate-900 dark:text-white font-bold text-lg"><?= APP_NAME ?></span>
    </a>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create your account</h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Join thousands of makers across India</p>
  </div>

  <!-- Progress Steps -->
  <div class="flex items-center gap-2 mb-7 px-2">
    <div class="flex items-center gap-2 flex-1">
      <div :class="step>=1?'bg-blue-600 text-white':'bg-slate-200 dark:bg-white/10 text-slate-400'"
           class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all flex-shrink-0">
        <span x-show="step<=1">1</span>
        <i x-show="step>1" class="fa-solid fa-check text-[10px]" x-cloak></i>
      </div>
      <span :class="step>=1?'text-slate-700 dark:text-slate-200':'text-slate-400'"
            class="text-xs font-medium transition-colors">Basic Info</span>
    </div>
    <div :class="step>=2?'bg-blue-600':'bg-slate-200 dark:bg-white/10'" class="flex-1 h-0.5 rounded-full transition-all"></div>
    <div class="flex items-center gap-2 flex-1">
      <div :class="step>=2?'bg-blue-600 text-white':'bg-slate-200 dark:bg-white/10 text-slate-400'"
           class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all flex-shrink-0">
        <span x-show="step<=2">2</span>
        <i x-show="step>2" class="fa-solid fa-check text-[10px]" x-cloak></i>
      </div>
      <span :class="step>=2?'text-slate-700 dark:text-slate-200':'text-slate-400'"
            class="text-xs font-medium transition-colors">Verify Mobile</span>
    </div>
  </div>

  <!-- Card -->
  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-xl shadow-slate-200/60 dark:shadow-black/40 p-7">

    <!-- ── STEP 1: Basic Info ───────────────────────────────────────────── -->
    <div x-show="step===1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
      <?php if(!empty(getenv('GOOGLE_CLIENT_ID'))): ?>
      <a href="/auth/google?redirect=/dashboard" class="btn-google mb-5 no-underline">
        <svg width="18" height="18" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
        Sign up with Google
      </a>
      <div class="divider mb-5">or create with email</div>
      <?php endif; ?>

      <div class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Full Name</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-regular fa-user text-sm"></i></span>
            <input type="text" x-model="name" placeholder="Tejas Sharma" class="input-field" autocomplete="name">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Email Address</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-regular fa-envelope text-sm"></i></span>
            <input type="email" x-model="email" placeholder="you@example.com" class="input-field" autocomplete="email">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Password</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span>
            <input :type="showPw?'text':'password'" x-model="password" @input="calcStrength()" placeholder="Min. 8 characters" class="input-field pr-12" autocomplete="new-password">
            <button type="button" @click="showPw=!showPw" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs transition-colors px-1">
              <i :class="showPw?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
            </button>
          </div>
          <!-- Strength bars -->
          <div x-show="password.length>0" class="flex gap-1 mt-2" x-cloak>
            <template x-for="i in 4" :key="i">
              <div class="pw-bar flex-1" :class="{
                'bg-red-500': strength<2 && i<=strength,
                'bg-amber-500': strength===2 && i<=strength,
                'bg-green-500': strength>=3 && i<=strength,
                'bg-slate-200 dark:bg-white/10': i>strength
              }"></div>
            </template>
          </div>
          <p x-show="password.length>0" class="text-xs mt-1 transition-colors" x-cloak
             :class="{'text-red-500':strength<2,'text-amber-500':strength===2,'text-green-500':strength>=3}"
             x-text="['','Weak','Fair','Strong','Very Strong'][strength]||''"></p>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Confirm Password</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock-open text-sm"></i></span>
            <input :type="showConfirm?'text':'password'" x-model="confirmPassword" @keydown.enter="nextStep()" placeholder="Re-enter password" class="input-field pr-12" autocomplete="new-password">
            <button type="button" @click="showConfirm=!showConfirm" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs transition-colors px-1">
              <i :class="showConfirm?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
            </button>
          </div>
        </div>
      </div>

      <p x-show="error" x-text="error" x-cloak class="mt-3 text-sm text-red-500 flex items-center gap-1.5">
        <i class="fa-solid fa-circle-exclamation text-xs"></i>
      </p>

      <button @click="nextStep()" :disabled="loading" class="btn-primary mt-5">
        <span x-show="!loading">Continue to Verify Mobile</span>
        <span x-show="loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <i x-show="!loading" class="fa-solid fa-arrow-right text-sm"></i>
      </button>

      <!-- T&C -->
      <p class="text-xs text-center text-slate-400 dark:text-slate-500 mt-4">
        By creating an account you agree to our
        <a href="/terms" class="text-blue-500 hover:underline">Terms</a> &amp;
        <a href="/privacy-policy" class="text-blue-500 hover:underline">Privacy Policy</a>
      </p>
    </div>

    <!-- ── STEP 2: Mobile Verification ──────────────────────────────────── -->
    <div x-show="step===2" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
      <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-2xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-mobile-screen text-blue-500 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Verify your mobile</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Required for account security &amp; order updates</p>
      </div>

      <!-- Phone input -->
      <div x-show="!otpSent">
        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Mobile Number</label>
        <div class="flex gap-2">
          <div class="flex items-center px-3 rounded-xl border-1.5 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[hsl(222,47%,13%)] text-slate-600 dark:text-slate-300 text-sm font-medium flex-shrink-0 border">
            🇮🇳 +91
          </div>
          <div class="relative flex-1">
            <input type="tel" x-model="phone" @keydown.enter="sendOtp()" @input="phone=phone.replace(/\D/g,'').slice(0,10)"
                   placeholder="9876543210" maxlength="10" class="input-field no-icon">
          </div>
        </div>
        <button @click="sendOtp()" :disabled="otpSending || phone.length<10" class="btn-primary mt-3">
          <span x-show="!otpSending">Send OTP</span>
          <span x-show="otpSending" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
          <i x-show="!otpSending" class="fa-solid fa-paper-plane text-sm"></i>
        </button>
      </div>

      <!-- OTP entry -->
      <div x-show="otpSent" x-cloak>
        <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">OTP sent to <span class="font-semibold">+91 <span x-text="phone"></span></span></p>

        <!-- Dev mode OTP display -->
        <div x-show="devOtp" x-cloak class="mb-4 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-sm flex items-center gap-2">
          <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
          <span>Dev mode — OTP: <strong x-text="devOtp"></strong></span>
        </div>

        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3 uppercase tracking-wide">Enter 6-Digit OTP</label>
        <input type="text" x-model="otpCode" @input="otpCode=otpCode.replace(/\D/g,'').slice(0,6)" @keydown.enter="verifyOtp()"
               placeholder="· · · · · ·" maxlength="6"
               class="input-field no-icon text-center text-2xl font-bold tracking-[1rem] mb-1 !py-4">

        <div class="flex items-center justify-between mt-3 mb-4">
          <button @click="otpSent=false;otpCode=''" class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
            <i class="fa-solid fa-pen text-xs mr-1"></i> Change number
          </button>
          <button @click="sendOtp()" :disabled="otpTimer>0 || otpSending"
                  class="text-sm font-medium transition-colors"
                  :class="otpTimer>0?'text-slate-400 cursor-not-allowed':'text-blue-600 dark:text-blue-400 hover:underline'">
            <span x-show="otpTimer>0">Resend in <span x-text="otpTimer"></span>s</span>
            <span x-show="!otpTimer">Resend OTP</span>
          </button>
        </div>

        <template x-if="!otpVerified">
          <button @click="verifyOtp()" :disabled="otpVerifying||otpCode.length<6" class="btn-primary">
            <span x-show="!otpVerifying">Verify OTP</span>
            <span x-show="otpVerifying" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <i x-show="!otpVerifying" class="fa-solid fa-check text-sm"></i>
          </button>
        </template>

        <!-- Verified state -->
        <div x-show="otpVerified" x-cloak class="text-center py-3">
          <div class="w-12 h-12 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-2">
            <i class="fa-solid fa-check text-green-500 text-lg"></i>
          </div>
          <p class="text-green-600 dark:text-green-400 font-semibold text-sm">Mobile number verified!</p>
        </div>
      </div>

      <!-- Error -->
      <p x-show="error" x-text="error" x-cloak class="mt-3 text-sm text-red-500 flex items-center gap-1.5">
        <i class="fa-solid fa-circle-exclamation text-xs"></i>
      </p>

      <!-- Create Account button (shown only after OTP verified) -->
      <template x-if="otpVerified">
        <button @click="submit()" :disabled="loading" class="btn-primary mt-5" style="background:linear-gradient(135deg,#2563eb,#06b6d4)">
          <span x-show="!loading">Create My Account</span>
          <span x-show="loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
          <i x-show="!loading" class="fa-solid fa-rocket text-sm"></i>
        </button>
      </template>

      <button @click="step=1;error=''" class="w-full text-center text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 mt-3 transition-colors">
        ← Back to basic info
      </button>
    </div>
  </div>

  <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-5">
    Already have an account? <a href="/login" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Sign In</a>
  </p>
</div>

<script>
function showToast(msg,type='info'){const c=document.getElementById('toastContainer');if(!c)return;const t=document.createElement('div');t.className='toast '+(type==='success'?'success':type==='error'?'error':'');t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4000);}
async function apiFetch(url,opts={}){const res=await fetch(url,{headers:{'Content-Type':'application/json',...(opts.headers||{})},credentials:'same-origin',...opts});const data=await res.json();if(!res.ok)throw new Error(data.message||'Request failed');return data.data!==undefined?data.data:data;}

function registerForm(){
  return {
    step:1, name:'', email:'', password:'', confirmPassword:'', showPw:false, showConfirm:false, strength:0,
    phone:'', otpCode:'', otpSent:false, otpSending:false, otpVerified:false, otpVerifying:false,
    otpTimer:0, timerInt:null, devOtp:'',
    loading:false, error:'',

    calcStrength(){
      let s=0,p=this.password;
      if(p.length>=8)s++;if(/[A-Z]/.test(p))s++;if(/[0-9]/.test(p))s++;if(/[^A-Za-z0-9]/.test(p))s++;
      this.strength=s;
    },

    async nextStep(){
      this.error='';
      if(!this.name.trim()){this.error='Please enter your full name';return;}
      if(!this.email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)){this.error='Enter a valid email address';return;}
      if(this.password.length<8){this.error='Password must be at least 8 characters';return;}
      if(this.password!==this.confirmPassword){this.error='Passwords do not match';return;}
      // Check email availability
      this.loading=true;
      try{
        await apiFetch('/api/auth/check-email',{method:'POST',body:JSON.stringify({email:this.email})});
      }catch(e){
        // email taken
        if(e.message.includes('409')||e.message.toLowerCase().includes('already')){this.error='This email is already registered. Try signing in.';this.loading=false;return;}
      }
      this.loading=false;
      this.step=2;
    },

    async sendOtp(){
      if(this.phone.length<10){this.error='Enter a valid 10-digit mobile number';return;}
      this.otpSending=true;this.error='';
      try{
        const res=await apiFetch('/api/auth/otp/send',{method:'POST',body:JSON.stringify({phone:this.phone,purpose:'register'})});
        this.otpSent=true;
        this.devOtp=res.otp||'';
        this.startTimer();
        showToast('OTP sent!','success');
      }catch(e){this.error=e.message;}
      this.otpSending=false;
    },

    startTimer(){
      this.otpTimer=30;clearInterval(this.timerInt);
      this.timerInt=setInterval(()=>{if(this.otpTimer>0)this.otpTimer--;else clearInterval(this.timerInt);},1000);
    },

    async verifyOtp(){
      if(this.otpCode.length<4){this.error='Enter the OTP';return;}
      this.otpVerifying=true;this.error='';
      try{
        await apiFetch('/api/auth/otp/verify',{method:'POST',body:JSON.stringify({phone:this.phone,otp:this.otpCode,purpose:'register'})});
        this.otpVerified=true;showToast('Phone verified!','success');
      }catch(e){this.error=e.message;}
      this.otpVerifying=false;
    },

    async submit(){
      if(!this.otpVerified){this.error='Verify your mobile number first';return;}
      this.loading=true;this.error='';
      try{
        await apiFetch('/api/auth/register',{method:'POST',body:JSON.stringify({
          name:this.name,email:this.email,password:this.password,phone:'+91'+this.phone
        })});
        showToast('Account created! Welcome aboard 🎉','success');
        setTimeout(()=>window.location='/dashboard',800);
      }catch(e){this.error=e.message;this.loading=false;}
    }
  }
}
</script>
</body>
</html>
