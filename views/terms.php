<?php $pageTitle = 'Terms of Service'; ?>
<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-white mb-2">Terms of Service</h1>
  <p class="text-gray-500 text-sm mb-8">Last updated: <?= date('F j, Y') ?></p>
  <div class="text-gray-300 text-sm leading-relaxed space-y-6">
    <?php foreach(['Acceptance of Terms'=>'By accessing and using TT Electro Store, you accept and agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.','Orders and Payment'=>'All orders are subject to availability and confirmation. We reserve the right to refuse or cancel any order for any reason. Payment must be made in full before orders are processed, except for Cash on Delivery orders.','Shipping and Delivery'=>'We ship pan-India with standard delivery in 5-7 business days. Express delivery options are available in select cities. We are not responsible for delays caused by third-party logistics partners.','Returns and Refunds'=>'Products may be returned within 1 day of delivery if defective or damaged. Items must be in original packaging. Refunds are processed within 5-7 business days to the original payment method.','Intellectual Property'=>'All content on this website including text, images, logos, and software is the property of TT Electro Store and protected by applicable intellectual property laws.','Limitation of Liability'=>'TT Electro Store shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or services.','Contact'=>'For any questions about these Terms, contact us at legal@ttelectro.in.'] as $h => $c): ?>
    <div><h2 class="text-lg font-bold text-white mb-2"><?= $h ?></h2><p class="text-gray-400"><?= $c ?></p></div>
    <?php endforeach; ?>
  </div>
</div>
