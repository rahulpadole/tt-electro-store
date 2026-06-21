<?php
$pageTitle = 'DIY Vision Kits';
$kits = (new DiyKitModel())->all();
$diffColors = ['beginner'=>'green','intermediate'=>'yellow','advanced'=>'red'];
?>
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="text-center mb-10">
    <h1 class="text-3xl font-bold text-white mb-2">🔧 DIY Vision Kits</h1>
    <p class="text-gray-400 max-w-xl mx-auto">Everything you need to build amazing electronics projects. Curated components, step-by-step guides, and video tutorials included.</p>
  </div>

  <?php if(empty($kits)): ?>
  <div class="text-center py-20"><div class="text-6xl mb-4">🔧</div><p class="text-gray-400 text-lg">No kits available yet. Check back soon!</p></div>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($kits as $kit):
      $dc = $diffColors[$kit['difficulty']??'beginner'] ?? 'blue';
    ?>
    <div class="group rounded-2xl bg-[hsl(222,47%,10%)] border border-white/5 hover:border-purple-500/20 overflow-hidden transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-500/5 flex flex-col">
      <?php if($kit['thumbnail']): ?>
      <div class="h-52 overflow-hidden"><img src="<?= clean($kit['thumbnail']) ?>" alt="<?= clean($kit['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"></div>
      <?php else: ?><div class="h-52 bg-gradient-to-br from-purple-900/30 to-blue-900/30 flex items-center justify-center text-7xl">🔧</div><?php endif; ?>
      <div class="p-5 flex flex-col flex-1">
        <div class="flex items-center gap-2 mb-3">
          <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-<?= $dc ?>-500/20 text-<?= $dc ?>-400 border border-<?= $dc ?>-500/30 capitalize">
            <?= clean($kit['difficulty']??'beginner') ?>
          </span>
          <?php if($kit['stock']>0): ?><span class="text-xs text-green-400">In Stock</span><?php else: ?><span class="text-xs text-red-400">Out of Stock</span><?php endif; ?>
        </div>
        <h2 class="text-lg font-bold text-white mb-2"><?= clean($kit['name']) ?></h2>
        <p class="text-gray-400 text-sm leading-relaxed line-clamp-3 flex-1"><?= clean($kit['description']??'') ?></p>

        <?php if(!empty($kit['components'])): ?>
        <div class="mt-4">
          <p class="text-xs text-gray-500 mb-2 font-medium">Components included (<?= count($kit['components']) ?>):</p>
          <div class="flex flex-wrap gap-1">
            <?php foreach(array_slice($kit['components'],0,5) as $comp): ?>
            <span class="px-2 py-0.5 rounded-md bg-white/5 text-gray-400 text-xs"><?= clean($comp) ?></span>
            <?php endforeach; ?>
            <?php if(count($kit['components'])>5): ?><span class="px-2 py-0.5 rounded-md bg-white/5 text-gray-500 text-xs">+<?= count($kit['components'])-5 ?> more</span><?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="flex items-center justify-between mt-5 pt-4 border-t border-white/10">
          <div>
            <span class="text-2xl font-bold text-white">₹<?= number_format((float)$kit['price'],2) ?></span>
            <p class="text-xs text-gray-500 mt-0.5">incl. all components</p>
          </div>
          <div class="flex gap-2">
            <?php if($kit['video_url']): ?><a href="<?= clean($kit['video_url']) ?>" target="_blank" class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors text-xs">▶</a><?php endif; ?>
            <?php if($kit['pdf_url']): ?><a href="<?= clean($kit['pdf_url']) ?>" target="_blank" class="p-2 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors text-xs">PDF</a><?php endif; ?>
            <button onclick="addToCart(<?= 0 /* kits don't have product_id */ ?>)" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium transition-colors">Add to Cart</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
