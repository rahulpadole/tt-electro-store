<!DOCTYPE html>
<html lang="en" x-data x-init="
  (function(){
    const saved   = localStorage.getItem('theme');
    const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark  = saved ? saved === 'dark' : sysDark;
    document.documentElement.classList.toggle('dark',  isDark);
    document.documentElement.classList.toggle('light', !isDark);
  })();
">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
    $siteUrl    = APP_URL;
    $siteName   = APP_NAME;
    $title      = isset($pageTitle) ? clean($pageTitle).' – '.$siteName : $siteName.' | Electronic Components Store Amravati';
    $desc       = isset($pageDesc)  ? clean($pageDesc)  : 'TT Electro Store – Buy electronic components, Arduino, Raspberry Pi, sensors, 3D printing services and DIY kits online. Trusted electronics store in Amravati, Maharashtra with fast India-wide delivery.';
    $canonical  = $siteUrl . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ogImage    = $siteUrl . '/assets/logo.png';
  ?>

  <title><?= $title ?></title>
  <meta name="description" content="<?= $desc ?>">
  <meta name="keywords" content="TT Electro, TT Electronics, TT Electro Store, electronics store, electronics components, electronic components store, buy electronics online india, electronics store amravati, electronics components amravati, tt electro amravati, arduino, raspberry pi, sensors, microcontrollers, ESP32, NodeMCU, diy kits, embedded systems, 3d printing amravati, 3d printing service india, electronic components india, best electronics store india">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="author" content="TT Electro Store">
  <meta name="geo.region" content="IN-MH">
  <meta name="geo.placename" content="Amravati, Maharashtra, India">
  <meta name="rating" content="general">
  <meta name="revisit-after" content="7 days">

  <!-- Canonical -->
  <link rel="canonical" href="<?= $canonical ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= $canonical ?>">
  <meta property="og:title"       content="<?= $title ?>">
  <meta property="og:description" content="<?= $desc ?>">
  <meta property="og:image"       content="<?= $ogImage ?>">
  <meta property="og:site_name"   content="<?= $siteName ?>">
  <meta property="og:locale"      content="en_IN">

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= $title ?>">
  <meta name="twitter:description" content="<?= $desc ?>">
  <meta name="twitter:image"       content="<?= $ogImage ?>">

  <!-- Favicon -->
  <link rel="icon"       href="/assets/logo-icon.png" type="image/png">
  <link rel="shortcut icon" href="/assets/logo-icon.png" type="image/png">
  <link rel="apple-touch-icon" href="/assets/logo-icon.png">

  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary:  { DEFAULT: '#2563EB', light: '#3b82f6', dark: '#1d4ed8', foreground: '#fff' },
            accent:   { DEFAULT: '#06B6D4', foreground: '#000' },
            surface: {
              light: '#ffffff',
              'light-2': '#f8fafc',
              'light-3': '#f1f5f9',
              dark:  'hsl(222,47%,8%)',
              'dark-2': 'hsl(222,47%,11%)',
              'dark-3': 'hsl(222,47%,14%)',
            },
          },
          fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
          boxShadow: {
            'card': '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.06)',
            'card-hover': '0 10px 25px -5px rgb(0 0 0 / 0.12), 0 4px 6px -4px rgb(0 0 0 / 0.08)',
            'card-dark': '0 4px 20px rgb(0 0 0 / 0.4)',
            'card-dark-hover': '0 16px 40px rgb(0 0 0 / 0.5)',
          },
        }
      }
    }
  </script>

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <!-- Global reactive cart/wishlist counts (registered before Alpine boots) -->
  <script>
    window.__isLoggedIn = <?= isLoggedIn() ? 'true' : 'false' ?>;
    document.addEventListener('alpine:init', () => {
      Alpine.store('counts', {
        cart: <?= (int)$__cartCount ?>,
        wishlist: <?= (int)$__wishlistCount ?>
      });
    });
  </script>

  <link rel="stylesheet" href="/assets/css/custom.css">

  <!-- Organisation + ElectronicsStore Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": ["Organization","ElectronicsStore"],
        "@id": "<?= $siteUrl ?>/#organization",
        "name": "TT Electro Store",
        "alternateName": ["TT Electronics","TT Electro","TT Electro Store","TTElectro","T.T. Electronics Amravati"],
        "url": "<?= $siteUrl ?>",
        "logo": {
          "@type": "ImageObject",
          "url": "<?= $ogImage ?>",
          "width": 512,
          "height": 512
        },
        "description": "TT Electro Store is India's trusted online electronics components store based in Amravati, Maharashtra. Shop Arduino, Raspberry Pi, ESP32, sensors, modules, 3D printing services, DIY kits and more with fast nationwide delivery.",
        "slogan": "Your One-Stop Electronics Components Store",
        "telephone": "+91-7721892429",
        "email": "support@ttelectro.in",
        "foundingDate": "2020",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "First Floor, Office No. 31, Trademark Complex, near Gadge Baba Temple",
          "addressLocality": "Amravati",
          "addressRegion": "Maharashtra",
          "postalCode": "444603",
          "addressCountry": "IN"
        },
        "geo": {
          "@type": "GeoCoordinates",
          "latitude": "20.9374",
          "longitude": "77.7796"
        },
        "openingHoursSpecification": {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
          "opens": "09:00",
          "closes": "18:00"
        },
        "contactPoint": {
          "@type": "ContactPoint",
          "telephone": "+91-7721892429",
          "contactType": "customer service",
          "areaServed": "IN",
          "availableLanguage": ["English","Hindi","Marathi"]
        },
        "priceRange": "₹-₹₹₹",
        "currenciesAccepted": "INR",
        "paymentAccepted": "UPI, Credit Card, Debit Card, Net Banking, Wallets",
        "areaServed": {
          "@type": "Country",
          "name": "India"
        },
        "hasOfferCatalog": {
          "@type": "OfferCatalog",
          "name": "Electronics Components Catalog",
          "itemListElement": [
            {"@type":"OfferCatalog","name":"Microcontrollers & Development Boards"},
            {"@type":"OfferCatalog","name":"Sensors & Modules"},
            {"@type":"OfferCatalog","name":"3D Printing Services"},
            {"@type":"OfferCatalog","name":"DIY Kits"},
            {"@type":"OfferCatalog","name":"Electronic Components"}
          ]
        },
        "knowsAbout": [
          "Electronic Components","Arduino","Raspberry Pi","ESP32","NodeMCU","Sensors","Microcontrollers","DIY Electronics","3D Printing","Embedded Systems","Robotics","IoT Modules"
        ],
        "sameAs": [
          "https://wa.me/917721892429"
        ]
      },
      {
        "@type": "WebSite",
        "@id": "<?= $siteUrl ?>/#website",
        "url": "<?= $siteUrl ?>",
        "name": "TT Electro Store",
        "alternateName": "TT Electronics | Electronics Components Store",
        "description": "Buy electronic components, Arduino, Raspberry Pi, ESP32, sensors, 3D printing and DIY kits online from TT Electro Store — Amravati's best electronics store.",
        "inLanguage": "en-IN",
        "publisher": {"@id": "<?= $siteUrl ?>/#organization"},
        "potentialAction": {
          "@type": "SearchAction",
          "target": {
            "@type": "EntryPoint",
            "urlTemplate": "<?= $siteUrl ?>/products?q={search_term_string}"
          },
          "query-input": "required name=search_term_string"
        }
      }
    ]
  }
  </script>
</head>
<body x-data="appState()">

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Navbar -->
<?php require __DIR__ . '/navbar.php'; ?>
