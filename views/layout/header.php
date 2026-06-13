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
  <meta name="keywords" content="electronic components, electronic store, electronics store amravati, 3d printing amravati, arduino, raspberry pi, sensors, microcontrollers, diy kits, embedded systems, buy electronics online india, 3d printing service india, electronic components india">
  <meta name="robots" content="index, follow">
  <meta name="author" content="TT Electro Store">

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

  <style>
    :root {
      --primary: #2563EB;
      --primary-light: #3b82f6;
      --accent: #06B6D4;
      --radius: 0.75rem;
    }

    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
    body { background-color: #f8fafc; color: #0f172a; }
    .dark body { background-color: hsl(222,47%,6%); color: #e2e8f0; }

    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
    .dark ::-webkit-scrollbar-thumb { background: #334155; }
    ::-webkit-scrollbar-thumb:hover { background: #64748b; }

    .toast-container { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:.5rem; }
    .toast {
      padding:.75rem 1.25rem; border-radius:.625rem; color:#fff; font-size:.875rem; font-weight:500;
      max-width:22rem; background:#1e293b; border-left:4px solid var(--primary);
      box-shadow:0 10px 30px rgba(0,0,0,.35); animation: slide-in .25s cubic-bezier(.16,1,.3,1);
    }
    .toast.success { border-left-color:#22c55e; background:#14532d; }
    .toast.error   { border-left-color:#ef4444; background:#450a0a; }
    @keyframes slide-in { from{ opacity:0; transform:translateY(.5rem) scale(.97); } to{ opacity:1; transform:none; } }

    .fade-up { animation: fadeUp .45s cubic-bezier(.16,1,.3,1); }
    @keyframes fadeUp { from{ opacity:0; transform:translateY(12px); } to{ opacity:1; transform:none; } }

    .logo-img { filter: none; transition: filter .2s; }
    .dark .logo-img { filter: brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(355deg); }

    .section-divider {
      border: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(148,163,184,.2), transparent);
    }
    .dark .section-divider {
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.06), transparent);
    }

    .card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: var(--radius);
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.07);
      transition: box-shadow .2s, transform .2s, border-color .2s;
    }
    .dark .card {
      background: hsl(222,47%,10%);
      border-color: rgba(255,255,255,.06);
      box-shadow: none;
    }
    .card:hover {
      box-shadow: 0 8px 24px -4px rgb(0 0 0 / 0.12);
      transform: translateY(-2px);
    }
    .dark .card:hover {
      box-shadow: 0 12px 32px -6px rgb(0 0 0 / 0.5);
      border-color: rgba(37,99,235,.25);
    }

    .input-base {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      color: #0f172a;
      border-radius: .5rem;
      transition: border-color .15s, box-shadow .15s;
    }
    .input-base::placeholder { color: #94a3b8; }
    .input-base:focus { outline: none; border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .dark .input-base { background: rgba(255,255,255,.07); border-color: rgba(255,255,255,.1); color: #e2e8f0; }
    .dark .input-base::placeholder { color: #475569; }
    .dark .input-base:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }

    .btn-primary {
      background: #2563EB;
      color: #fff;
      border: none;
      border-radius: .625rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s, transform .1s, box-shadow .15s;
    }
    .btn-primary:hover { background: #1d4ed8; box-shadow: 0 4px 14px rgba(37,99,235,.35); }
    .btn-primary:active { transform: scale(.97); }

    .gradient-text {
      background: linear-gradient(135deg, #2563EB, #06B6D4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .section-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
    .dark .section-title { color: #f1f5f9; }
    .section-subtitle { font-size: .875rem; color: #64748b; margin-top: .25rem; }
    .dark .section-subtitle { color: #64748b; }

    .badge { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .65rem; border-radius:9999px; font-size:.75rem; font-weight:600; }

    .section-label {
      display: inline-flex; align-items: center; gap: .5rem;
      font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
      color: #2563eb; margin-bottom: .75rem;
    }
    .dark .section-label { color: #3b82f6; }
  </style>
  <link rel="stylesheet" href="/assets/css/custom.css">

  <!-- Local Business Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ElectronicsStore",
    "name": "TT Electro Store",
    "url": "<?= $siteUrl ?>",
    "logo": "<?= $ogImage ?>",
    "description": "Buy electronic components, Arduino, Raspberry Pi, sensors, 3D printing services and DIY kits online. Trusted electronics store in Amravati, Maharashtra.",
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
    "priceRange": "₹₹",
    "servesCuisine": null,
    "sameAs": ["https://wa.me/917721892429"]
  }
  </script>
</head>
<body x-data="appState()">

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Navbar -->
<?php require __DIR__ . '/navbar.php'; ?>
