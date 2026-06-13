<?php
$pageTitle = 'My Profile';
$user = getCurrentUser();
?>
<div class="max-w-xl mx-auto px-4 py-8" x-data="profileForm()">
  <h1 class="text-2xl font-bold text-white mb-6">My Profile</h1>
  <div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/10 p-6 space-y-4">
    <div class="flex items-center gap-4 mb-4">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-2xl"><?= strtoupper(substr($user['name'],0,1)) ?></div>
      <div><p class="text-white font-bold"><?= clean($user['name']) ?></p><p class="text-gray-400 text-sm"><?= clean($user['email']) ?></p></div>
    </div>
    <div><label class="text-xs text-gray-400 mb-1 block">Full Name</label>
    <input type="text" x-model="name" class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
    <div><label class="text-xs text-gray-400 mb-1 block">Phone</label>
    <input type="tel" x-model="phone" class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
    <div><label class="text-xs text-gray-400 mb-1 block">Email (read-only)</label>
    <input type="email" value="<?= clean($user['email']) ?>" readonly class="w-full px-3 py-2.5 rounded-xl bg-[hsl(222,47%,8%)] border border-white/5 text-gray-500 text-sm cursor-not-allowed"></div>
    <p x-show="success" class="text-green-400 text-sm">✓ Profile updated!</p>
    <p x-show="error" x-text="error" class="text-red-400 text-sm"></p>
    <button @click="save()" :disabled="loading" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all disabled:opacity-50">
      <span x-show="!loading">Save Changes</span><span x-show="loading">Saving...</span>
    </button>
  </div>
</div>
<script>
function profileForm(){return{name:'<?= clean($user['name']) ?>',phone:'<?= clean($user['phone']??'') ?>',loading:false,error:'',success:false,async save(){this.loading=true;this.error='';this.success=false;try{await apiFetch('/api/auth/me/update',{method:'PATCH',body:JSON.stringify({name:this.name,phone:this.phone})});this.success=true;}catch(e){this.error=e.message;}this.loading=false;}}}
</script>
