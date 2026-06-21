<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Login – <?= APP_NAME ?></title>
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class'}</script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="dark bg-[hsl(222,47%,6%)] text-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="toast-container fixed bottom-6 right-6 z-50 flex flex-col gap-2" id="toastContainer"></div>
<div class="w-full max-w-sm">
  <div class="text-center mb-8">
    <img src="/assets/logo-icon.png" alt="TT" class="w-14 h-14 object-contain mx-auto mb-3" style="filter:brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(355deg);">
    <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
    <p class="text-gray-400 text-sm mt-1">Sign in to manage your store</p>
  </div>
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/10 p-7" x-data="adminLogin()">
    <div class="space-y-4">
      <div><label class="text-xs text-gray-400 mb-1 block">Email</label>
      <input type="email" x-model="email" @keydown.enter="submit()" placeholder="admin@ttelectro.in" class="w-full px-4 py-3 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500"></div>
      <div><label class="text-xs text-gray-400 mb-1 block">Password</label>
      <input type="password" x-model="password" @keydown.enter="submit()" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500"></div>
      <p x-show="error" x-text="error" class="text-red-400 text-sm bg-red-500/10 px-3 py-2 rounded-lg border border-red-500/20"></p>
      <button @click="submit()" :disabled="loading" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all disabled:opacity-50">
        <span x-show="!loading">Sign In →</span>
        <span x-show="loading" class="flex items-center justify-center gap-2"><span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>Signing in...</span>
      </button>
    </div>
    <p class="text-center mt-5"><a href="/" class="text-gray-500 text-sm hover:text-gray-300">← Back to Store</a></p>
  </div>
</div>
<script>
function showToast(m,t='info'){const c=document.getElementById('toastContainer');const el=document.createElement('div');el.style.cssText='padding:.75rem 1.25rem;border-radius:.5rem;color:#fff;background:#1e293b;border-left:4px solid '+(t==='success'?'#22c55e':'#ef4444')+';box-shadow:0 10px 30px rgba(0,0,0,.4);';el.textContent=m;c.appendChild(el);setTimeout(()=>el.remove(),3500);}
function adminLogin(){return{email:'',password:'',loading:false,error:'',async submit(){if(!this.email||!this.password){this.error='Please fill all fields';return;}this.loading=true;this.error='';try{const r=await fetch('/api/auth/login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email:this.email,password:this.password})});const d=await r.json();if(!r.ok)throw new Error(d.message||'Login failed');if(d.data?.role!=='admin'){await fetch('/api/auth/logout',{method:'POST'});throw new Error('Access denied. Admin only.');}location.href='/admin';}catch(e){this.error=e.message;}this.loading=false;}}}
</script>
</body></html>
