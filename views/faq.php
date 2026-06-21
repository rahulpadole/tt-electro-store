<?php
$pageTitle = 'FAQ';
$faqs    = (new FaqModel())->all();
$grouped = [];
foreach($faqs as $f) { $grouped[$f['category']??'General'][] = $f; }
?>
<div class="max-w-3xl mx-auto px-4 py-12">

  <!-- Header -->
  <div class="text-center mb-12">
    <p class="section-label justify-center"><i class="fa-solid fa-circle-question"></i> Help Center</p>
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Frequently Asked Questions</h1>
    <p class="text-slate-500 dark:text-slate-400">Find answers to common questions about orders, products, and services.</p>
  </div>

  <?php if(empty($faqs)): ?>
  <div class="text-center py-16 bg-white dark:bg-[hsl(222,47%,10%)] rounded-2xl border border-slate-200 dark:border-white/6">
    <i class="fa-solid fa-circle-question text-5xl text-slate-300 dark:text-slate-600 mb-4 block"></i>
    <p class="text-slate-500 dark:text-slate-400">No FAQs available yet.</p>
  </div>

  <?php else: ?>
  <div class="space-y-8">
    <?php foreach($grouped as $cat => $items): ?>
    <div>
      <h2 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-3 flex items-center gap-2">
        <span class="block h-px flex-1 bg-blue-100 dark:bg-blue-500/15"></span>
        <?= clean($cat) ?>
        <span class="block h-px flex-1 bg-blue-100 dark:bg-blue-500/15"></span>
      </h2>
      <div class="space-y-2.5">
        <?php foreach($items as $f): ?>
        <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 overflow-hidden hover:border-blue-300 dark:hover:border-blue-500/20 transition-colors" x-data="{open:false}">
          <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-4 text-left gap-4">
            <span class="font-medium text-slate-800 dark:text-slate-200 text-sm leading-snug"><?= clean($f['question']) ?></span>
            <span class="w-6 h-6 rounded-lg flex-shrink-0 flex items-center justify-center bg-slate-100 dark:bg-white/8 text-slate-500 dark:text-slate-400 transition-all duration-200" :class="open && 'rotate-180 !bg-blue-50 dark:!bg-blue-500/10 !text-blue-600 dark:!text-blue-400'">
              <i class="fa-solid fa-chevron-down text-[9px]"></i>
            </span>
          </button>
          <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
               class="px-5 pb-5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-white/5 pt-4">
            <?= nl2br(clean($f['answer'])) ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Still have questions CTA -->
  <div class="mt-12 p-7 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/15 border border-blue-100 dark:border-blue-500/20 text-center">
    <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center mx-auto mb-4">
      <i class="fa-solid fa-headset text-blue-600 dark:text-blue-400 text-xl"></i>
    </div>
    <p class="font-bold text-slate-900 dark:text-white mb-1">Still have questions?</p>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-5">Our support team is ready to help you within 24 hours.</p>
    <a href="/contact" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-500/20">
      <i class="fa-solid fa-envelope text-xs"></i> Contact Support
    </a>
  </div>
</div>
