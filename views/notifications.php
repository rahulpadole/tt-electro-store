<?php
$pageTitle = 'Notifications';
$nm = new NotificationModel();
$notifications = $nm->forUser(getCurrentUserId());
$typeColors = ['info'=>'blue','success'=>'green','warning'=>'yellow','error'=>'red','order'=>'purple'];
?>
<div class="max-w-2xl mx-auto px-4 py-8" x-data="notifPage()">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Notifications</h1>
    <?php if(!empty($notifications)): ?>
    <button @click="markAll()" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Mark all as read</button>
    <?php endif; ?>
  </div>

  <?php if(empty($notifications)): ?>
  <div class="text-center py-20">
    <div class="text-6xl mb-4">🔔</div>
    <p class="text-gray-400 text-lg font-medium">No notifications yet</p>
    <p class="text-gray-500 text-sm mt-1">We'll notify you about orders, offers, and updates</p>
  </div>
  <?php else: ?>
  <div class="space-y-3" id="notifList">
    <?php foreach($notifications as $n):
      $tc = $typeColors[$n['type']] ?? 'blue';
    ?>
    <div id="notif-<?= $n['id'] ?>" class="flex items-start gap-3 p-4 rounded-2xl border transition-all <?= !$n['is_read'] ? 'bg-blue-500/5 border-blue-500/20' : 'bg-[hsl(222,47%,10%)] border-white/5' ?>">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 bg-<?= $tc ?>-500/20 text-<?= $tc ?>-400">
        <?php $icons=['info'=>'ℹ️','success'=>'✅','warning'=>'⚠️','error'=>'❌','order'=>'📦']; echo $icons[$n['type']]??'🔔'; ?>
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-medium text-white text-sm"><?= clean($n['title']) ?></p>
        <p class="text-gray-400 text-sm mt-0.5"><?= clean($n['message']) ?></p>
        <div class="flex items-center gap-3 mt-1.5">
          <p class="text-gray-600 text-xs"><?= date('M j, g:i a', strtotime($n['created_at'])) ?></p>
          <?php if(!$n['is_read']): ?>
          <button onclick="markRead(<?= $n['id'] ?>,this)" class="text-xs text-blue-400 hover:text-blue-300">Mark read</button>
          <?php endif; ?>
          <?php if($n['link']): ?><a href="<?= clean($n['link']) ?>" class="text-xs text-blue-400 hover:text-blue-300">View →</a><?php endif; ?>
        </div>
      </div>
      <?php if(!$n['is_read']): ?><div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-2"></div><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<script>
function notifPage() { return {}; }
async function markRead(id, btn) {
  try {
    await apiFetch(`/api/notifications/${id}/read`, { method:'PATCH' });
    const el = document.getElementById(`notif-${id}`);
    if(el) { el.classList.remove('bg-blue-500/5','border-blue-500/20'); el.classList.add('bg-[hsl(222,47%,10%)]','border-white/5'); }
    btn.remove();
  } catch(e) {}
}
async function markAll() {
  try {
    await apiFetch('/api/notifications/read-all', { method:'PATCH' });
    document.querySelectorAll('[id^="notif-"]').forEach(el=>{
      el.classList.remove('bg-blue-500/5','border-blue-500/20'); el.classList.add('bg-[hsl(222,47%,10%)]','border-white/5');
    });
    showToast('All notifications marked as read','success');
  } catch(e) {}
}
</script>
