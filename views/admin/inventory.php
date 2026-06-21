<?php
$pageTitle = 'Inventory';
$db = Database::getConnection();
$st = $db->query('SELECT p.id,p.name,p.stock,p.thumbnail,p.price,c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=true ORDER BY p.stock ASC');
$products = $st->fetchAll();
?>
<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="text-white font-semibold">Inventory Management</h2>
    <p class="text-gray-500 text-xs mt-0.5">Products sorted by stock level (lowest first)</p>
  </div>
  <div class="flex gap-3 text-xs">
    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400"><span class="w-2 h-2 rounded-full bg-red-500"></span>Out of Stock</span>
    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-500/10 text-yellow-400"><span class="w-2 h-2 rounded-full bg-yellow-500"></span>Low (&lt;10)</span>
    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-500/10 text-green-400"><span class="w-2 h-2 rounded-full bg-green-500"></span>In Stock</span>
  </div>
</div>
<div class="rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 overflow-hidden">
  <table class="w-full text-sm">
    <thead><tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wide">
      <th class="px-4 py-3 text-left">Product</th>
      <th class="px-4 py-3 text-left">Category</th>
      <th class="px-4 py-3 text-right">Price</th>
      <th class="px-4 py-3 text-right">Stock</th>
      <th class="px-4 py-3 text-center">Stock Status</th>
      <th class="px-4 py-3 text-center">Update</th>
    </tr></thead>
    <tbody>
      <?php foreach($products as $p): ?>
      <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors" id="inv-row-<?= $p['id'] ?>">
        <td class="px-4 py-3">
          <div class="flex items-center gap-3">
            <?php if($p['thumbnail']): ?><img src="<?= clean($p['thumbnail']) ?>" class="w-8 h-8 rounded-lg object-cover flex-shrink-0"><?php else: ?><div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">📦</div><?php endif; ?>
            <p class="text-gray-200 line-clamp-1"><?= clean($p['name']) ?></p>
          </div>
        </td>
        <td class="px-4 py-3 text-gray-500 text-xs"><?= clean($p['category_name']??'—') ?></td>
        <td class="px-4 py-3 text-right text-gray-300">₹<?= number_format((float)$p['price'],2) ?></td>
        <td class="px-4 py-3 text-right"><span class="font-bold <?= $p['stock']<=0?'text-red-400':($p['stock']<=10?'text-yellow-400':'text-green-400') ?>"><?= $p['stock'] ?></span></td>
        <td class="px-4 py-3 text-center">
          <?php if($p['stock']<=0): ?><span class="px-2 py-0.5 rounded-full text-xs bg-red-500/20 text-red-400">Out of Stock</span>
          <?php elseif($p['stock']<=10): ?><span class="px-2 py-0.5 rounded-full text-xs bg-yellow-500/20 text-yellow-400">Low Stock</span>
          <?php else: ?><span class="px-2 py-0.5 rounded-full text-xs bg-green-500/20 text-green-400">In Stock</span><?php endif; ?>
        </td>
        <td class="px-4 py-3 text-center" x-data="{stock:<?= $p['stock'] ?>,loading:false}">
          <div class="flex items-center justify-center gap-1">
            <input type="number" x-model="stock" min="0" class="w-16 px-2 py-1 rounded-lg bg-[hsl(222,47%,13%)] border border-white/10 text-white text-xs text-center focus:outline-none focus:border-blue-500">
            <button @click="updateStock(<?= $p['id'] ?>,stock)" :disabled="loading" class="px-2 py-1 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 text-xs transition-all disabled:opacity-50">Save</button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
async function updateStock(id, stock) {
  try { await apiFetch(`/api/products/${id}`, { method:'PATCH', body:JSON.stringify({stock:parseInt(stock)}) }); showToast('Stock updated!','success'); } catch(e) { showToast(e.message,'error'); }
}
</script>
