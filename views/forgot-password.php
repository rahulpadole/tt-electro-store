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
  <title>Reset Password – <?= APP_NAME ?></title>
  <meta name="description" content="Reset your <?= APP_NAME ?> account password securely using your registered mobile number.">
  <meta name="robots" content="noindex,nofollow">
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <style>
    body{font-family:'Inter',ui-sans-serif,sans-serif;-webkit-font-smoothing:antialiased}
    .input-field{width:100%;padding:.75rem 1rem .75rem 2.85rem;border-radius:.75rem;font-size:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;transition:border-color .15s,box-shadow .15s;outline:none}
    .input-field::placeholder{color:#94a3b8}
    .input-field:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .input-field{background:hsl(222,47%,13%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .input-field::placeholder{color:#475569}
    .dark .input-field:focus{border-color:#3b82f6}
    .otp-input{width:3rem;height:3.25rem;text-align:center;font-size:1.4rem;font-weight:700;border-radius:.625rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;outline:none;transition:border-color .15s,box-shadow .15s}
    .otp-input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .otp-input{background:hsl(222,47%,13%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .otp-input:focus{border-color:#3b82f6}
    .btn-primary{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.85rem 1.5rem;border-radius:.75rem;font-weight:600;font-size:.925rem;background:#2563eb;color:#fff;border:none;cursor:pointer;transition:all .15s}
    .btn-primary:hover{background:#1d4ed8;box-shadow:0 4px 14px rgba(37,99,235,.35)}
    .btn-primary:active{transform:scale(.98)}
    .btn-primary:disabled{opacity:.55;cursor:not-allowed;transform:none;box-shadow:none}
    .toast-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem}
    .toast{padding:.75rem 1.25rem;border-radius:.625rem;color:#fff;font-size:.875rem;background:#1e293b;border-left:4px solid #2563eb;box-shadow:0 8px 24px rgba(0,0,0,.3);animation:ti .25s cubic-bezier(.16,1,.3,1)}
    .toast.success{border-left-color:#22c55e;background:#14532d}
    .toast.error{border-left-color:#ef4444;background:#450a0a}
    @keyframes ti{from{opacity:0;transform:translateY(.5rem) scale(.97)}to{opacity:1;transform:none}}
    [x-cloak]{display:none!important}
  </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-[hsl(222,47%,5%)] flex items-center justify-center p-4">
<div class="toast-container" id="toastContainer"></div>

<div class="w-full max-w-md" x-data="forgotForm()">

  <!-- Logo -->
  <div class="text-center mb-7">
    <a href="/" class="inline-flex items-center gap-2.5 mb-2">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-lg">
        <i class="fa-solid fa-bolt text-white text-base"></i>
      </div>
      <span class="text-slate-900 dark:text-white font-bold text-xl"><?= APP_NAME ?></span>
    </a>
  </div>

  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-xl p-8">

    <!-- Progress indicator -->
    <div class="flex items-center gap-2 mb-7" x-show="step < 4">
      <template x-for="s in [1,2,3]" :key="s">
        <div class="flex items-center gap-2 flex-1">
          <div class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all flex-shrink-0"
               :class="step>s ? 'bg-green-500 text-white' : step===s ? 'bg-blue-600 text-white ring-4 ring-blue-500/20' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-slate-500'">
            <i x-show="step>s" class="fa-solid fa-check text-[10px]" x-cloak></i>
            <span x-show="step<=s" x-text="s"></span>
          </div>
          <template x-if="s < 3">
            <div class="h-px flex-1" :class="step>s ? 'bg-green-500' : 'bg-slate-200 dark:bg-white/10'"></div>
          </template>
        </div>
      </template>
    </div>

    <!-- ── Step 1: Enter Email ──────────────────────────────────── -->
    <div x-show="step===1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-6">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 dark:bg-blue-500/15 flex items-center justify-center mb-4">
          <i class="fa-solid fa-envelope-open-text text-blue-600 dark:text-blue-400 text-2xl"></i>
        </div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Forgot your password?</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Enter your registered email. We'll send a one-time code to your linked mobile number.</p>
      </div>

      <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Email Address</label>
      <div class="relative mb-5">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><i class="fa-regular fa-envelope text-sm"></i></span>
        <input type="email" x-model="email" @keydown.enter="sendOtp()" placeholder="you@example.com" class="input-field" autocomplete="email">
      </div>

      <p x-show="error" x-text="error" x-cloak class="text-red-500 dark:text-red-400 text-sm mb-4 flex items-center gap-1.5">
        <i class="fa-solid fa-circle-exclamation text-xs flex-shrink-0"></i>
      </p>

      <button @click="sendOtp()" :disabled="loading" class="btn-primary mb-4">
        <span x-show="!loading" class="flex items-center gap-2"><i class="fa-solid fa-paper-plane text-sm"></i> Send OTP</span>
        <span x-show="loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
      </button>
    </div>

    <!-- ── Step 2: Enter OTP ───────────────────────────────────── -->
    <div x-show="step===2" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-6">
        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 dark:bg-purple-500/15 flex items-center justify-center mb-4">
          <i class="fa-solid fa-mobile-screen-button text-purple-600 dark:text-purple-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Enter the OTP</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">
          A 6-digit code was sent to
          <span x-show="maskedPhone" class="font-semibold text-slate-700 dark:text-slate-200">+91 <span x-text="maskedPhone"></span></span><span x-show="!maskedPhone">your mobile</span>.
        </p>
        <template x-if="devOtp">
          <div class="mt-3 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-mono font-semibold">
            <i class="fa-solid fa-code mr-1.5"></i>Dev mode OTP: <span x-text="devOtp" class="text-base tracking-widest"></span>
          </div>
        </template>
      </div>

      <div class="flex justify-center gap-2.5 mb-5">
        <template x-for="(digit,idx) in otpDigits" :key="idx">
          <input type="text" inputmode="numeric" maxlength="1"
                 :value="digit"
                 @input="handleOtpInput($event, idx)"
                 @keydown="handleOtpKey($event, idx)"
                 @paste.prevent="handleOtpPaste($event)"
                 :id="'otp-'+idx"
                 class="otp-input">
        </template>
      </div>

      <p x-show="error" x-text="error" x-cloak class="text-red-500 dark:text-red-400 text-sm mb-4 text-center flex items-center justify-center gap-1.5">
        <i class="fa-solid fa-circle-exclamation text-xs"></i>
      </p>

      <button @click="verifyOtp()" :disabled="loading || otpDigits.join('').length < 6" class="btn-primary mb-4">
        <span x-show="!loading" class="flex items-center gap-2"><i class="fa-solid fa-shield-check text-sm"></i> Verify OTP</span>
        <span x-show="loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
      </button>

      <div class="text-center">
        <button @click="resend()" :disabled="cooldown>0" class="text-sm text-blue-600 dark:text-blue-400 hover:underline disabled:opacity-50 disabled:no-underline disabled:cursor-not-allowed transition-all">
          <span x-show="cooldown<=0">Didn't receive it? Resend OTP</span>
          <span x-show="cooldown>0" x-cloak>Resend in <span x-text="cooldown"></span>s</span>
        </button>
      </div>
    </div>

    <!-- ── Step 3: New Password ────────────────────────────────── -->
    <div x-show="step===3" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="mb-6">
        <div class="w-14 h-14 rounded-2xl bg-green-500/10 dark:bg-green-500/15 flex items-center justify-center mb-4">
          <i class="fa-solid fa-lock text-green-600 dark:text-green-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Set New Password</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Choose a strong password for your account.</p>
      </div>

      <div class="space-y-4 mb-5">
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">New Password</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><i class="fa-solid fa-lock text-sm"></i></span>
            <input :type="showPw1?'text':'password'" x-model="password" @keydown.enter="resetPassword()"
                   placeholder="Minimum 8 characters" class="input-field pr-12" autocomplete="new-password">
            <button type="button" @click="showPw1=!showPw1" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors px-1">
              <i :class="showPw1?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
            </button>
          </div>
          <!-- Strength indicator -->
          <div class="mt-2 flex gap-1" x-show="password.length>0">
            <template x-for="i in 4" :key="i">
              <div class="h-1 flex-1 rounded-full transition-all"
                   :class="strength>=i ? (strength<=1?'bg-red-500':strength<=2?'bg-amber-500':strength<=3?'bg-yellow-400':'bg-green-500') : 'bg-slate-200 dark:bg-white/10'"></div>
            </template>
          </div>
          <p class="text-xs mt-1 transition-all"
             :class="strength<=1?'text-red-400':strength<=2?'text-amber-400':strength<=3?'text-yellow-400':'text-green-400'"
             x-show="password.length>0" x-text="['','Weak','Fair','Good','Strong'][strength]" x-cloak></p>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Confirm Password</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><i class="fa-solid fa-lock-open text-sm"></i></span>
            <input :type="showPw2?'text':'password'" x-model="confirm" @keydown.enter="resetPassword()"
                   placeholder="Re-enter password" class="input-field pr-12" autocomplete="new-password">
            <button type="button" @click="showPw2=!showPw2" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors px-1">
              <i :class="showPw2?'fa-regular fa-eye-slash':'fa-regular fa-eye'" class="text-base"></i>
            </button>
          </div>
          <p x-show="confirm.length>0 && password!==confirm" x-cloak class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Passwords don't match
          </p>
        </div>
      </div>

      <p x-show="error" x-text="error" x-cloak class="text-red-500 dark:text-red-400 text-sm mb-4 flex items-center gap-1.5">
        <i class="fa-solid fa-circle-exclamation text-xs flex-shrink-0"></i>
      </p>

      <button @click="resetPassword()" :disabled="loading || password.length<8 || password!==confirm" class="btn-primary mb-4">
        <span x-show="!loading" class="flex items-center gap-2"><i class="fa-solid fa-key text-sm"></i> Reset Password</span>
        <span x-show="loading" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
      </button>
    </div>

    <!-- ── Step 4: Success ────────────────────────────────────── -->
    <div x-show="step===4" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="text-center py-4">
      <div class="w-20 h-20 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-5 ring-4 ring-green-500/10">
        <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
      </div>
      <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Password Reset!</h2>
      <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
        Your password has been updated successfully. You can now sign in with your new password.
      </p>
      <a href="/login" class="btn-primary no-underline inline-flex">
        <i class="fa-solid fa-right-to-bracket text-sm"></i> Sign In Now
      </a>
    </div>

    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-white/8 text-center">
      <a href="/login" class="text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
        <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Sign In
      </a>
    </div>
  </div>

  <p class="text-center text-xs text-slate-400 mt-5">
    Need help? <a href="tel:+917721892429" class="text-blue-500 hover:underline">+91 7721892429</a>
  </p>
</div>

<script>
function showToast(msg,type='info'){const c=document.getElementById('toastContainer');if(!c)return;const t=document.createElement('div');t.className='toast '+(type==='success'?'success':type==='error'?'error':'');t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4000);}
async function apiFetch(url,opts={}){const res=await fetch(url,{headers:{'Content-Type':'application/json',...(opts.headers||{})},credentials:'same-origin',...opts});const d=await res.json();if(!res.ok)throw new Error(d.message||'Request failed');return d;}

function forgotForm(){
  return {
    step:1, email:'', maskedPhone:'', devOtp:'',
    otpDigits:['','','','','',''],
    password:'', confirm:'',
    showPw1:false, showPw2:false,
    loading:false, error:'',
    cooldown:0, cooldownTimer:null,

    get strength(){
      const p=this.password;
      if(p.length<6) return 1;
      let s=1;
      if(p.length>=8) s++;
      if(/[A-Z]/.test(p)&&/[0-9]/.test(p)) s++;
      if(/[^A-Za-z0-9]/.test(p)) s++;
      return s;
    },

    async sendOtp(){
      this.error='';
      if(!this.email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)){this.error='Enter a valid email address';return;}
      this.loading=true;
      try{
        const r=await apiFetch('/api/auth/forgot-password',{method:'POST',body:JSON.stringify({email:this.email})});
        this.maskedPhone=r.data?.masked||'';
        this.devOtp=r.data?.otp||'';
        this.step=2;
        this.startCooldown();
      }catch(e){this.error=e.message;}
      this.loading=false;
    },

    handleOtpInput(e, idx){
      const val=e.target.value.replace(/\D/g,'');
      this.otpDigits[idx]=val?val[val.length-1]:'';
      e.target.value=this.otpDigits[idx];
      if(val&&idx<5) document.getElementById('otp-'+(idx+1))?.focus();
      if(this.otpDigits.join('').length===6) this.$nextTick(()=>this.verifyOtp());
    },

    handleOtpKey(e, idx){
      if(e.key==='Backspace'&&!this.otpDigits[idx]&&idx>0){
        document.getElementById('otp-'+(idx-1))?.focus();
      }
      if(e.key==='ArrowLeft'&&idx>0) document.getElementById('otp-'+(idx-1))?.focus();
      if(e.key==='ArrowRight'&&idx<5) document.getElementById('otp-'+(idx+1))?.focus();
    },

    handleOtpPaste(e){
      const text=(e.clipboardData||window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
      text.split('').forEach((c,i)=>{ if(i<6) this.otpDigits[i]=c; });
      const last=Math.min(text.length,5);
      this.$nextTick(()=>{ document.getElementById('otp-'+last)?.focus(); });
      if(text.length===6) this.$nextTick(()=>this.verifyOtp());
    },

    async verifyOtp(){
      const otp=this.otpDigits.join('');
      if(otp.length<6){this.error='Enter the complete 6-digit OTP';return;}
      this.error=''; this.loading=true;
      try{
        await apiFetch('/api/auth/otp/verify',{method:'POST',body:JSON.stringify({phone:this.maskedPhone?.replace(/\*/g,'')||'0000000000',otp,purpose:'forgot_password',email:this.email})});
        this.step=3;
      }catch(e){this.error=e.message;}
      this.loading=false;
    },

    async resetPassword(){
      if(this.password.length<8){this.error='Password must be at least 8 characters';return;}
      if(this.password!==this.confirm){this.error="Passwords don't match";return;}
      this.error=''; this.loading=true;
      try{
        await apiFetch('/api/auth/reset-password',{method:'POST',body:JSON.stringify({email:this.email,otp:this.otpDigits.join(''),password:this.password})});
        this.step=4;
      }catch(e){this.error=e.message;}
      this.loading=false;
    },

    async resend(){
      if(this.cooldown>0) return;
      this.error=''; this.loading=true;
      try{
        const r=await apiFetch('/api/auth/forgot-password',{method:'POST',body:JSON.stringify({email:this.email})});
        this.devOtp=r.data?.otp||'';
        this.otpDigits=['','','','','',''];
        document.getElementById('otp-0')?.focus();
        showToast('OTP resent!','success');
        this.startCooldown();
      }catch(e){this.error=e.message;}
      this.loading=false;
    },

    startCooldown(){
      this.cooldown=30;
      clearInterval(this.cooldownTimer);
      this.cooldownTimer=setInterval(()=>{ if(--this.cooldown<=0) clearInterval(this.cooldownTimer); },1000);
    }
  }
}
</script>
</body>
</html>
