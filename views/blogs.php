<?php
$pageTitle = 'Blog';
$bm       = new BlogModel();
$page     = max(1,(int)($_GET['page']??1));
$category = $_GET['category']??'';
$result   = $bm->all($page,9,$category);
?>
<div class="max-w-7xl mx-auto px-4 py-12">

  <!-- Header -->
  <div class="text-center mb-12">
    <p class="section-label justify-center"><i class="fa-solid fa-newspaper"></i> From the Blog</p>
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Tech Blog</h1>
    <p class="text-slate-500 dark:text-slate-400">Tips, tutorials, project ideas and maker stories</p>
  </div>

  <?php if(empty($result['items'])): ?>
  <div class="text-center py-20 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
      <i class="fa-solid fa-newspaper text-3xl text-slate-300 dark:text-slate-600"></i>
    </div>
    <p class="text-slate-500 dark:text-slate-400">No blog posts yet. Check back soon!</p>
  </div>

  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach($result['items'] as $b): ?>
    <a href="/blogs/<?= clean($b['slug']) ?>"
       class="group flex flex-col rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-300 dark:hover:border-blue-500/30 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-50 dark:hover:shadow-blue-500/8">

      <?php if($b['thumbnail']): ?>
      <div class="h-48 overflow-hidden bg-slate-100 dark:bg-[hsl(222,47%,13%)]">
        <img src="<?= clean($b['thumbnail']) ?>" alt="<?= clean($b['title']) ?>"
             class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500">
      </div>
      <?php else: ?>
      <div class="h-48 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 flex items-center justify-center">
        <i class="fa-solid fa-newspaper text-5xl text-blue-200 dark:text-blue-700"></i>
      </div>
      <?php endif; ?>

      <div class="p-5 flex flex-col flex-1">
        <?php if($b['category']): ?>
        <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2"><?= clean($b['category']) ?></span>
        <?php endif; ?>
        <h2 class="font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 text-base leading-snug line-clamp-2 transition-colors flex-1"><?= clean($b['title']) ?></h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 line-clamp-2 leading-relaxed"><?= clean($b['excerpt']??'') ?></p>

        <?php if(!empty($b['tags'])): ?>
        <div class="flex flex-wrap gap-1.5 mt-3">
          <?php foreach(array_slice((array)$b['tags'],0,3) as $tag): ?>
          <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/6 text-slate-500 dark:text-slate-400 text-xs">#<?= clean($tag) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-white/5 text-xs text-slate-400 dark:text-slate-500">
          <span class="flex items-center gap-1.5">
            <i class="fa-regular fa-user text-[10px]"></i>
            <?= clean($b['author_name']??'TT Electro') ?>
          </span>
          <div class="flex items-center gap-3">
            <?php if($b['reading_time']): ?>
            <span class="flex items-center gap-1"><i class="fa-regular fa-clock text-[10px]"></i> <?= $b['reading_time'] ?> min</span>
            <?php endif; ?>
            <span><?= date('M j',strtotime($b['created_at'])) ?></span>
          </div>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if($result['total_pages'] > 1): ?>
  <div class="flex items-center justify-center gap-2 mt-10">
    <?php if($page > 1): ?>
    <a href="?page=<?= $page-1 ?><?= $category?"&category={$category}":'' ?>"
       class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white text-sm font-medium transition-all shadow-sm">
      <i class="fa-solid fa-chevron-left text-xs"></i> Prev
    </a>
    <?php endif; ?>
    <?php for($i=1;$i<=$result['total_pages'];$i++): ?>
    <a href="?page=<?= $i ?><?= $category?"&category={$category}":'' ?>"
       class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm <?= $i===$page ? 'bg-blue-600 text-white border border-blue-600' : 'bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' ?>">
      <?= $i ?>
    </a>
    <?php endfor; ?>
    <?php if($page < $result['total_pages']): ?>
    <a href="?page=<?= $page+1 ?><?= $category?"&category={$category}":'' ?>"
       class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white text-sm font-medium transition-all shadow-sm">
      Next <i class="fa-solid fa-chevron-right text-xs"></i>
    </a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</div>
