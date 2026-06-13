<?php
$pageTitle = 'Verify Mobile';
$user = getCurrentUser();
if (!$user) redirect('/login');
if ((int)($user['phone_verified'] ?? 0)) redirect('/dashboard');
?>
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
  <title>Verify Mobile – <?= APP_NAME ?></title>
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','ui-sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <style>
    body{font-family:'Inter',ui-sans-serif,sans-serif;-webkit-font-smoothing:antialiased}
    .input-field{width:100%;padding:.75rem 1rem;border-radius:.75rem;font-size:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#0f172a;transition:border-color .15s,box-shadow .15s;outline:none}
    .input-field::placeholder{color:#94a3b8}
    .input-field:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
    .dark .input-field{background:hsl(222,47%,13%);border-color:rgba(255,255,255,.1);color:#e2e8f0}
    .dark .input-field:focus{border-color:#3b82f6}
    .btn{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.8rem 1.5rem;border-radius:.75rem;font-weight:600;font-size:.925rem;border:none;cursor:pointer;transition:all .15s}
    .btn-primary{background:#2563eb;color:#fff}.btn-primary:hover{background:#1d4ed8}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed}
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

<div class="w-full max-w-sm" x-data="verifyPhone()">

  <!-- Card -->
  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-xl p-8 text-center">

    <!-- Avatar from Google -->
    <?php if(!empty($user['google_avatar'])): ?>
    <img src="<?= htmlspecialchars($user['google_avatar']) ?>" alt="" class="w-16 h-16 rounded-full mx-auto mb-4 ring-4 ring-blue-500/20">
    <?php else: ?>
    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
      <?= strtoupper(substr($user['name'],0,1)) ?>
    </div>
    <?php endif; ?>

    <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-1">One more step, <?= htmlspecialchars(explode(' ',$user['name'])[0]) ?>!</h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-7">Add your mobile number to secure your account and receive order updates.</p>

    <!-- Phone step -->
    <div x-show="!otpSent">
      <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide text-left">Mobile Number</label>
      <div class="flex gap-2 mb-3">
        <div class="flex items-center px-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[hsl(222,47%,13%)] text-slate-600 dark:text-slate-300 text-sm font-medium flex-shrink-0">
          🇮🇳 +91
        </div>
        <input type="tel" x-model="phone" @input="phone=phone.replace(/\D/g,'').slice(0,10)" @keydown.enter="sendOtp()"
               placeholder="9876543210" maxlength="10" class="input-field flex-1">
      </div>
      <button @click="sendOtp()" :disabled="sending||phone.length<10" class="btn btn-primary">
        <span x-show="!sending">Send OTP</span>
        <span x-show="sending" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <i x-show="!sending" class="fa-solid fa-paper-plane text-sm"></i>
      </button>
    </div>

    <!-- OTP step -->
    <div x-show="otpSent" x-cloak>
      <div x-show="devOtp" x-cloak class="mb-4 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-sm text-left">
        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Dev OTP: <strong x-text="devOtp"></strong>
      </div>
      <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
        OTP sent to <strong>+91 <span x-text="phone"></span></strong>
      </p>
      <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide text-left">6-Digit OTP</label>
      <input type="text" x-model="otpCode" @input="otpCode=otpCode.replace(/\D/g,'').slice(0,6)" @keydown.enter="verifyOtp()"
             placeholder="· · · · · ·" maxlength="6" class="input-field text-center text-2xl font-bold tracking-[1rem] mb-3 !py-4">

      <div class="flex items-center justify-between mb-4 text-sm">
        <button @click="otpSent=false;otpCode=''" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
          <i class="fa-solid fa-pen text-xs mr-1"></i> Change number
        </button>
        <button @click="sendOtp()" :disabled="timer>0||sending"
                class="font-medium transition-colors"
                :class="timer>0?'text-slate-400 cursor-not-allowed':'text-blue-600 dark:text-blue-400 hover:underline'">
          <span x-show="timer>0">Resend in <span x-text="timer"></span>s</span>
          <span x-show="!timer">Resend OTP</span>
        </button>
      </div>

      <button @click="verifyOtp()" :disabled="verifying||otpCode.length<4" class="btn btn-primary">
        <span x-show="!verifying">Verify &amp; Continue</span>
        <span x-show="verifying" x-cloak class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <i x-show="!verifying" class="fa-solid fa-arrow-right text-sm"></i>
      </button>
    </div>

    <!-- Error -->
    <p x-show="error" x-text="error" x-cloak class="mt-3 text-sm text-red-500 flex items-center justify-center gap-1.5">
      <i class="fa-solid fa-triangle-exclamation text-xs"></i>
    </p>

    <!-- Skip (only if not new Google user) -->
    <?php if(empty($_SESSION['google_just_authed'])): ?>
    <a href="/dashboard" class="block mt-5 text-sm text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
      Skip for now →
    </a>
    <?php endif; ?>
  </div>

  <p class="text-center mt-5 text-xs text-slate-400">
    Need help? <a href="tel:+917721892429" class="text-blue-500 hover:underline">+91 7721892429</a>
  </p>
</div>

<script>
function showToast(msg,type='info'){const c=document.getElementById('toastContainer');if(!c)return;const t=document.createElement('div');t.className='toast '+(type==='success'?'success':type==='error'?'error':'');t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4000);}
async function apiFetch(url,opts={}){const res=await fetch(url,{headers:{'Content-Type':'application/json',...(opts.headers||{})},credentials:'same-origin',...opts});const data=await res.json();if(!res.ok)throw new Error(data.message||'Request failed');return data.data!==undefined?data.data:data;}

function verifyPhone(){
  return {
    phone:'', otpCode:'', otpSent:false, sending:false, verifying:false,
    timer:0, timerInt:null, devOtp:'', error:'',

    async sendOtp(){
      if(this.phone.length<10){this.error='Enter a valid 10-digit number';return;}
      this.sending=true;this.error='';
      try{
        const res=await apiFetch('/api/auth/otp/send',{method:'POST',body:JSON.stringify({phone:this.phone,purpose:'verify_phone'})});
        this.otpSent=true;this.devOtp=res.otp||'';this.startTimer();
        showToast('OTP sent!','success');
      }catch(e){this.error=e.message;}
      this.sending=false;
    },

    startTimer(){
      this.timer=30;clearInterval(this.timerInt);
      this.timerInt=setInterval(()=>{if(this.timer>0)this.timer--;else clearInterval(this.timerInt);},1000);
    },

    async verifyOtp(){
      if(this.otpCode.length<4){this.error='Enter the OTP';return;}
      this.verifying=true;this.error='';
      try{
        await apiFetch('/api/auth/otp/verify',{method:'POST',body:JSON.stringify({phone:this.phone,otp:this.otpCode,purpose:'verify_phone'})});
        showToast('Phone verified! Redirecting...','success');
        sessionStorage.removeItem('google_just_authed');
        setTimeout(()=>window.location='/dashboard',1000);
      }catch(e){this.error=e.message;}
      this.verifying=false;
    }
  }
}
</script>
</body>
</html>
