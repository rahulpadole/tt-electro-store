<?php
$pageTitle = 'About TT Electro Store – Electronic Components Store in Amravati';
$pageDesc  = 'Learn about TT Electro Store, Amravati\'s premier electronic components and 3D printing store. Serving makers, engineers and DIY enthusiasts across Maharashtra with quality products.';
?>
<div class="max-w-4xl mx-auto px-4 py-12">

  <!-- Header -->
  <div class="text-center mb-14">
    <a href="/" class="inline-block mb-6">
      <img src="/assets/logo.png" alt="TT Electro Store – Electronic Components Amravati" class="h-14 w-auto mx-auto logo-img">
    </a>
    <p class="section-label justify-center"><i class="fa-solid fa-store"></i> Our Story</p>
    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-4 tracking-tight">About TT Electro Store</h1>
    <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
      Amravati's trusted electronic components store — empowering makers, engineers and DIY enthusiasts across Maharashtra with premium electronics, 3D printing services, and hands-on kits.
    </p>
  </div>

  <!-- Mission / Vision / Values -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-12">
    <?php
    $pillars = [
      ['fa-bullseye','blue','Our Mission','To make premium electronic components and tools accessible to every maker, engineer, and DIY enthusiast across India.'],
      ['fa-eye','purple','Our Vision','To become India\'s most trusted electronic store for the next generation of innovators and tech creators.'],
      ['fa-lightbulb','amber','Our Values','Quality first, community-driven, and committed to education, innovation, and customer satisfaction.'],
    ];
    foreach($pillars as [$icon,$color,$title,$text]): ?>
    <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-6 text-center shadow-sm hover:shadow-md dark:hover:shadow-none transition-all">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4
        <?= match($color) {
          'blue'   => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400',
          'purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400',
          'amber'  => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
          default  => 'bg-slate-100 dark:bg-white/8 text-slate-500',
        } ?>">
        <i class="fa-solid fa-<?= $icon ?> text-2xl"></i>
      </div>
      <h2 class="font-bold text-slate-900 dark:text-white mb-2"><?= $title ?></h2>
      <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed"><?= $text ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Our Story -->
  <div class="rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-8 mb-8 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
      <i class="fa-solid fa-book-open text-blue-600 dark:text-blue-400 text-base"></i> Our Story
    </h2>
    <div class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed space-y-4">
      <p>TT Electro Store was founded in Amravati, Maharashtra by a passionate team of engineers and makers who saw a gap in quality electronic components availability in the Vidarbha region. Located at Trademark Complex, Gadge Nagar — near Gadge Baba Temple — we set out to bring the best electronics directly to our community.</p>
      <p>Today, we serve thousands of customers across India — from hobbyists building their first Arduino project to professional engineers designing complex embedded systems. Our curated selection of microcontrollers, sensors, displays, communication modules, robotics parts, and 3D printing services ensures you always get the right component for your project.</p>
      <p>We're more than just an electronics store. We're a community of makers helping each other build amazing things. From DIY kits with step-by-step guides to custom 3D printing services, we support your journey from idea to reality. Join us and power your next idea!</p>
    </div>
  </div>

  <!-- Address card -->
  <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/15 dark:to-indigo-900/15 border border-blue-200 dark:border-blue-500/20 p-7 mb-8">
    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
      <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400"></i> Visit Our Store
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <i class="fa-solid fa-building text-blue-500 mt-1 text-sm flex-shrink-0"></i>
          <div>
            <p class="font-semibold text-slate-800 dark:text-white text-sm">TT Electronics</p>
            <p class="text-slate-600 dark:text-slate-400 text-sm">First Floor, Office No. 31<br>Trademark Complex, near Gadge Baba Temple<br>Gadge Nagar, Amravati<br>Maharashtra – 444603</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-phone text-green-500 text-sm flex-shrink-0"></i>
          <a href="tel:+917721892429" class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-blue-600 dark:hover:text-blue-400 transition-colors">+91 77218 92429</a>
        </div>
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-envelope text-purple-500 text-sm flex-shrink-0"></i>
          <a href="mailto:support@ttelectro.in" class="text-slate-700 dark:text-slate-300 text-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors">support@ttelectro.in</a>
        </div>
        <div class="flex items-center gap-3">
          <i class="fa-regular fa-clock text-amber-500 text-sm flex-shrink-0"></i>
          <p class="text-slate-600 dark:text-slate-400 text-sm">Mon–Sat, 9am – 6pm IST</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <a href="https://wa.me/917721892429" target="_blank" rel="noopener"
           class="flex items-center gap-2 px-5 py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold text-sm transition-all self-start">
          <i class="fa-brands fa-whatsapp text-lg"></i> Chat on WhatsApp
        </a>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
    <?php foreach([['5000+','Happy Customers','fa-users','blue'],['10000+','Products','fa-microchip','purple'],['50+','Brands','fa-tag','amber'],['99%','Satisfaction','fa-star','green']] as [$num,$label,$icon,$color]): ?>
    <div class="text-center rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 p-5 shadow-sm">
      <i class="fa-solid fa-<?= $icon ?> text-<?= $color ?>-500 dark:text-<?= $color ?>-400 text-xl mb-2 block"></i>
      <p class="text-2xl font-extrabold gradient-text"><?= $num ?></p>
      <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 font-medium"><?= $label ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- CTA -->
  <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-center shadow-xl shadow-blue-500/20">
    <h3 class="text-xl font-bold text-white mb-2">Ready to Start Making?</h3>
    <p class="text-blue-200 text-sm mb-6">Join thousands of makers building amazing projects with TT Electro Store – Amravati's #1 electronic components store.</p>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-bold text-sm transition-all">
        <i class="fa-solid fa-microchip text-xs"></i> Browse Electronic Components
      </a>
      <a href="/contact" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-white/25 text-white hover:bg-white/10 font-semibold text-sm transition-all">
        <i class="fa-solid fa-envelope text-xs"></i> Contact Us
      </a>
    </div>
  </div>
</div>

<!-- Local Business Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ElectronicsStore",
  "name": "TT Electro Store",
  "description": "Premium electronic components, Arduino, Raspberry Pi, sensors, DIY kits and 3D printing services in Amravati, Maharashtra.",
  "url": "<?= APP_URL ?>",
  "telephone": "+91-7721892429",
  "email": "support@ttelectro.in",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "First Floor, Office No. 31, Trademark Complex, near Gadge Baba Temple",
    "addressLocality": "Gadge Nagar, Amravati",
    "addressRegion": "Maharashtra",
    "postalCode": "444603",
    "addressCountry": "IN"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
    "opens": "09:00",
    "closes": "18:00"
  },
  "sameAs": ["https://wa.me/917721892429"]
}
</script>
