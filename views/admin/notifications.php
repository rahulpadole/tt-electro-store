<?php
$pageTitle = 'Notifications';
$notifications = (new NotificationModel())->all();
?>
<div class="flex items-center justify-between mb-5" x-data="{showModal:false}">
  <h2 class="text-white font-semibold">Notifications (<?= count($notifications) ?>)</h2>
  <button @click="showModal=true" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm transition-colors">+ Send Notification</button>
  <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click.self="showModal=false">
    <div class="w-full max-w-md bg-[hsl(222,47%,10%)] border border-white/10 rounded-2xl p-6" x-data="{title:'',message:'',type:'info',link:'',loading:false,error:''}">
      <h3 class="font-bold text-white mb-4">Send Notification</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400 mb-1 block">Title *</label><input type="text" x-model="title" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Message *</label><textarea x-model="message" rows="3" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Type</label>
        <select x-model="type" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-gray-300 text-sm focus:outline-none focus:border-blue-500"><option value="info">Info</option><option value="success">Success</option><option value="warning">Warning</option><option value="order">Order</option></select></div>
        <div><label class="text-xs text-gray-400 mb-1 block">Link (optional)</label><input type="text" x-model="link" placeholder="/products" class="w-full px-3 py-2 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-sm focus:outline-none focus:border-blue-500"></div>
        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
      </div>
      <div class="flex gap-3 mt-4">
        <button @click="showModal=false" class="flex-1 py-2.5 rounded-lg border border-white/10 text-gray-400 text-sm hover:bg-white/5">Cancel</button>
        <button @click="loading=true;apiFetch('/api/notifications',{method:'POST',body:JSON.stringify({title,message,type,link:link||null})}).then(()=>{showToast('Sent!','success');setTimeout(()=>location.reload(),600)}).catch(e=>{error=e.message;loading=false})" :disabled="loading" class="flex-1 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm disabled:opacity-50"><span x-show="!loading">Send</span><span x-show="loading">...</span></button>
      </div>
    </div>
  </div>
</div>
<div class="space-y-2">
  <?php foreach($notifications as $n):
    $types=['info'=>'blue','success'=>'green','warning'=>'yellow','error'=>'red','order'=>'purple'];
    $tc=$types[$n['type']]??'blue';
  ?>
  <div class="flex items-center gap-3 p-3 rounded-xl bg-[hsl(222,47%,10%)] border border-white/5" id="notif-<?= $n['id'] ?>">
    <span class="w-8 h-8 rounded-lg bg-<?= $tc ?>-500/20 text-<?= $tc ?>-400 flex items-center justify-center text-sm flex-shrink-0">
      <?php $i=['info'=>'ℹ️','success'=>'✅','warning'=>'⚠️','order'=>'📦']; echo $i[$n['type']]??'🔔'; ?>
    </span>
    <div class="flex-1 min-w-0">
      <p class="text-white text-sm font-medium"><?= clean($n['title']) ?></p>
      <p class="text-gray-400 text-xs truncate"><?= clean($n['message']) ?></p>
      <p class="text-gray-600 text-xs mt-0.5"><?= $n['user_name']?'To: '.clean($n['user_name']):'Broadcast' ?> · <?= date('M j g:i a',strtotime($n['created_at'])) ?></p>
    </div>
    <span class="text-xs <?= $n['is_read']?'text-gray-500':'text-blue-400' ?>"><?= $n['is_read']?'Read':'Unread' ?></span>
    <button onclick="deleteNotif(<?= $n['id'] ?>)" class="text-gray-500 hover:text-red-400 text-xs transition-colors">✕</button>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteNotif(id){if(!confirm('Delete?'))return;try{await apiFetch(`/api/notifications/${id}`,{method:'DELETE'});document.getElementById(`notif-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
