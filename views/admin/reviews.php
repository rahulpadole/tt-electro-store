<?php
$pageTitle = 'Reviews';
$reviews = (new ReviewModel())->all(100);
?>
<div class="flex items-center justify-between mb-5">
  <h2 class="text-white font-semibold">Customer Reviews (<?= count($reviews) ?>)</h2>
</div>
<div class="space-y-3">
  <?php foreach($reviews as $r): ?>
  <div class="flex items-start gap-4 p-4 rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5" id="review-<?= $r['id'] ?>">
    <div class="flex-1">
      <div class="flex items-center gap-3 mb-1">
        <span class="font-medium text-white text-sm"><?= clean($r['user_name']??'User') ?></span>
        <span class="text-gray-500 text-xs">on <span class="text-blue-400"><?= clean($r['product_name']??'Product') ?></span></span>
        <div class="flex gap-0.5"><?php for($s=1;$s<=5;$s++): ?><span class="<?= $s<=(int)$r['rating']?'text-yellow-400':'text-gray-600' ?> text-xs">★</span><?php endfor; ?></div>
        <span class="text-gray-500 text-xs"><?= date('M j, Y',strtotime($r['created_at'])) ?></span>
      </div>
      <?php if($r['title']): ?><p class="font-medium text-gray-200 text-sm"><?= clean($r['title']) ?></p><?php endif; ?>
      <?php if($r['body']): ?><p class="text-gray-400 text-sm mt-0.5"><?= clean($r['body']) ?></p><?php endif; ?>
    </div>
    <button onclick="deleteReview(<?= $r['id'] ?>)" class="text-gray-500 hover:text-red-400 transition-colors text-sm flex-shrink-0">✕</button>
  </div>
  <?php endforeach; ?>
</div>
<script>
async function deleteReview(id){if(!confirm('Delete this review?'))return;try{await apiFetch(`/api/reviews/${id}`,{method:'DELETE'});document.getElementById(`review-${id}`)?.remove();showToast('Deleted','success');}catch(e){showToast(e.message,'error');}}
</script>
