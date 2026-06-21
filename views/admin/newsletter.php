<?php
$pageTitle = 'Newsletter';
$subs = (new NewsletterModel())->all();
?>
<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="text-white font-semibold">Newsletter Subscribers (<?= count($subs) ?>)</h2>
    <p class="text-gray-500 text-xs mt-0.5">All email addresses subscribed to the newsletter</p>
  </div>
  <button onclick="
    const emails = <?= json_encode(array_column($subs,'email')) ?>;
    const csv = 'Email\n'+emails.join('\n');
    const a = document.createElement('a'); a.href = 'data:text/csv,'+encodeURIComponent(csv); a.download = 'subscribers.csv'; a.click();
    showToast('Exported!','success');" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm transition-colors">Export CSV</button>
</div>
<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <table class="w-full text-sm">
    <thead><tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
      <th class="px-4 py-3 text-left">#</th>
      <th class="px-4 py-3 text-left">Email</th>
      <th class="px-4 py-3 text-left">Subscribed At</th>
      <th class="px-4 py-3 text-center">Action</th>
    </tr></thead>
    <tbody>
      <?php foreach($subs as $i => $s): ?>
      <tr class="border-b border-white/5 hover:bg-white/[0.02]" id="sub-<?= $s['id'] ?>">
        <td class="px-4 py-3 text-gray-500"><?= $i+1 ?></td>
        <td class="px-4 py-3 text-gray-200"><?= clean($s['email']) ?></td>
        <td class="px-4 py-3 text-gray-500 text-xs"><?= date('M j, Y g:i a',strtotime($s['subscribed_at'])) ?></td>
        <td class="px-4 py-3 text-center">
          <button onclick="unsubscribe(<?= $s['id'] ?>)" class="text-xs text-red-400 hover:text-red-300 transition-colors">Remove</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
async function unsubscribe(id){if(!confirm('Remove from newsletter?'))return;try{await apiFetch(`/api/newsletter/${id}`,{method:'DELETE'});document.getElementById(`sub-${id}`)?.remove();showToast('Removed','success');}catch(e){showToast(e.message,'error');}}
</script>
