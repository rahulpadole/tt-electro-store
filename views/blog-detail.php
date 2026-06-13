<?php
$bm   = new BlogModel();
$blog = $bm->findBySlug($blogSlug);
if(!$blog){ http_response_code(404); include __DIR__.'/not-found.php'; return; }
$bm->incrementViews((int)$blog['id']);
$pageTitle = $blog['title'];
$pageDesc  = $blog['excerpt']??$blog['title'];
$related   = $bm->all(1,3,$blog['category']??'');
$related['items'] = array_filter($related['items'],fn($r)=>$r['id']!=$blog['id']);
?>
<div class="max-w-3xl mx-auto px-4 py-8">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-1.5 text-sm text-slate-500 dark:text-slate-500 mb-7 flex-wrap">
    <a href="/" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Home</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 dark:text-slate-700"></i>
    <a href="/blogs" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Blog</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 dark:text-slate-700"></i>
    <span class="text-slate-700 dark:text-slate-300 truncate max-w-[220px]"><?= clean($blog['title']) ?></span>
  </nav>

  <!-- Hero image -->
  <?php if($blog['thumbnail']): ?>
  <div class="rounded-2xl overflow-hidden mb-8 bg-slate-100 dark:bg-[hsl(222,47%,13%)] shadow-sm">
    <img src="<?= clean($blog['thumbnail']) ?>" alt="<?= clean($blog['title']) ?>" class="w-full h-72 object-cover">
  </div>
  <?php endif; ?>

  <!-- Meta -->
  <div class="mb-6">
    <?php if($blog['category']): ?>
    <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest"><?= clean($blog['category']) ?></span>
    <?php endif; ?>
    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mt-2 leading-tight tracking-tight"><?= clean($blog['title']) ?></h1>

    <div class="flex items-center flex-wrap gap-x-4 gap-y-1.5 mt-4 text-sm text-slate-500 dark:text-slate-400">
      <span class="flex items-center gap-1.5">
        <span class="w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center text-[9px] text-white font-bold flex-shrink-0">
          <?= strtoupper(substr($blog['author_name']??'T',0,1)) ?>
        </span>
        <?= clean($blog['author_name']??'TT Electro') ?>
      </span>
      <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
      <span><?= date('F j, Y',strtotime($blog['created_at'])) ?></span>
      <?php if($blog['reading_time']): ?>
      <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
      <span class="flex items-center gap-1">
        <i class="fa-regular fa-clock text-xs"></i> <?= $blog['reading_time'] ?> min read
      </span>
      <?php endif; ?>
      <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
      <span class="flex items-center gap-1">
        <i class="fa-regular fa-eye text-xs"></i> <?= number_format($blog['view_count']) ?> views
      </span>
    </div>
  </div>

  <!-- Excerpt / pull quote -->
  <?php if($blog['excerpt']): ?>
  <div class="border-l-4 border-blue-500 pl-5 mb-7 py-1">
    <p class="text-base text-slate-600 dark:text-slate-300 italic leading-relaxed"><?= clean($blog['excerpt']) ?></p>
  </div>
  <?php endif; ?>

  <!-- Content -->
  <div class="prose prose-slate dark:prose-invert prose-sm max-w-none
              prose-headings:font-bold prose-headings:tracking-tight
              prose-a:text-blue-600 dark:prose-a:text-blue-400
              prose-code:text-pink-600 dark:prose-code:text-pink-400 prose-code:bg-slate-100 dark:prose-code:bg-white/8 prose-code:px-1 prose-code:rounded
              prose-pre:bg-slate-900 dark:prose-pre:bg-black/40 prose-pre:rounded-xl prose-pre:border prose-pre:border-slate-200 dark:prose-pre:border-white/8
              prose-blockquote:border-l-blue-500 prose-blockquote:text-slate-600 dark:prose-blockquote:text-slate-400
              text-slate-700 dark:text-slate-300 leading-relaxed">
    <?= nl2br(clean($blog['content'])) ?>
  </div>

  <!-- Tags -->
  <?php if(!empty($blog['tags'])): ?>
  <div class="flex flex-wrap gap-2 mt-8 pt-7 border-t border-slate-100 dark:border-white/8">
    <?php foreach((array)$blog['tags'] as $tag): ?>
    <a href="/blogs?tag=<?= urlencode($tag) ?>"
       class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-white/6 hover:bg-blue-50 dark:hover:bg-blue-500/10 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-xs font-medium transition-all">
      #<?= clean($tag) ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Share -->
  <div class="mt-8 p-5 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-sm">
    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-share-nodes text-blue-600 dark:text-blue-400 text-sm"></i> Share this article
    </p>
    <div class="flex gap-2.5 flex-wrap">
      <?php
      $shareUrl   = urlencode("https://".$_SERVER['HTTP_HOST']."/blogs/{$blog['slug']}");
      $shareTitle = urlencode($blog['title']);
      $shares = [
        ['fa-x-twitter','bg-slate-100 dark:bg-white/8 text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400', "https://twitter.com/intent/tweet?text={$shareTitle}&url={$shareUrl}", 'X (Twitter)'],
        ['fa-linkedin','bg-slate-100 dark:bg-white/8 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-700/10 hover:text-blue-700 dark:hover:text-blue-400',"https://www.linkedin.com/sharing/share-offsite/?url={$shareUrl}",'LinkedIn'],
        ['fa-whatsapp','bg-slate-100 dark:bg-white/8 text-slate-700 dark:text-slate-300 hover:bg-green-50 dark:hover:bg-green-500/10 hover:text-green-600 dark:hover:text-green-400',"https://wa.me/?text={$shareTitle}%20{$shareUrl}",'WhatsApp'],
      ];
      foreach($shares as [$icon,$cls,$url,$label]): ?>
      <a href="<?= $url ?>" target="_blank" rel="noopener"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $cls ?>">
        <i class="fa-brands fa-<?= $icon ?>"></i> <?= $label ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Related Posts -->
<?php if(!empty($related['items'])): ?>
<div class="max-w-7xl mx-auto px-4 pb-14 mt-10">
  <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
    <i class="fa-solid fa-newspaper text-blue-600 dark:text-blue-400 text-base"></i>
    Related Articles
  </h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <?php foreach(array_slice($related['items'],0,3) as $b): ?>
    <a href="/blogs/<?= clean($b['slug']) ?>"
       class="group flex flex-col rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 hover:border-blue-300 dark:hover:border-blue-500/30 overflow-hidden transition-all hover:-translate-y-1 hover:shadow-xl dark:hover:shadow-none">
      <?php if($b['thumbnail']): ?>
      <div class="h-36 overflow-hidden bg-slate-100 dark:bg-[hsl(222,47%,13%)]">
        <img src="<?= clean($b['thumbnail']) ?>" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500">
      </div>
      <?php else: ?>
      <div class="h-36 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 flex items-center justify-center">
        <i class="fa-solid fa-newspaper text-3xl text-blue-200 dark:text-blue-700"></i>
      </div>
      <?php endif; ?>
      <div class="p-4 flex-1">
        <h3 class="font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 text-sm leading-snug"><?= clean($b['title']) ?></h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2"><?= date('M j, Y',strtotime($b['created_at'])) ?></p>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
