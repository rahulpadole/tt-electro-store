<?php $pageTitle = 'Privacy Policy'; ?>
<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-white mb-2">Privacy Policy</h1>
  <p class="text-gray-500 text-sm mb-8">Last updated: <?= date('F j, Y') ?></p>
  <div class="prose prose-invert max-w-none text-gray-300 text-sm leading-relaxed space-y-6">
    <?php foreach(['Information We Collect'=>'We collect information you provide directly to us, including name, email address, phone number, and shipping address when you register an account or place an order. We also collect usage data including pages visited and products viewed to improve your experience.','How We Use Your Information'=>'We use the information we collect to process orders and send you related information including purchase confirmations and invoices, respond to your comments and questions, send promotional communications (you may opt-out at any time), and to improve our services.','Information Sharing'=>'We do not sell, trade, or rent your personal information to third parties. We may share information with service providers who assist us in operations, payment processing, and delivery. All partners are bound by strict confidentiality agreements.','Data Security'=>'We implement industry-standard security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. All payment data is encrypted and processed through secure payment gateways.','Your Rights'=>'You have the right to access, update, or delete your personal information at any time. You may also opt-out of marketing communications by clicking the unsubscribe link in our emails or contacting us directly.','Contact Us'=>'If you have questions about this Privacy Policy, please contact us at privacy@ttelectro.in or call +91 98765 43210.'] as $heading => $content): ?>
    <div><h2 class="text-lg font-bold text-white mb-2"><?= $heading ?></h2><p><?= $content ?></p></div>
    <?php endforeach; ?>
  </div>
</div>
