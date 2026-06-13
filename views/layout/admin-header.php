<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? clean($pageTitle).' – Admin Panel' : 'Admin Panel – '.APP_NAME ?></title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="icon" href="/assets/logo-icon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: { extend: {
        colors: {
          primary: { DEFAULT: '#3b82f6', dark: '#2563eb' },
          surface: {
            '900': 'hsl(222,47%,5%)',
            '800': 'hsl(222,47%,8%)',
            '750': 'hsl(222,47%,10%)',
            '700': 'hsl(222,47%,12%)',
            '600': 'hsl(222,47%,16%)',
          }
        },
        fontFamily: { sans: ['Inter','ui-sans-serif'] }
      }}
    }
  </script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <style>
    *,*::before,*::after{box-sizing:border-box}
    body{font-family:'Inter',sans-serif;-webkit-font-smoothing:antialiased}
    ::-webkit-scrollbar{width:4px;height:4px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:hsl(222,47%,20%);border-radius:2px}
    ::-webkit-scrollbar-thumb:hover{background:hsl(222,47%,28%)}
    .toast-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem}
    .toast{padding:.75rem 1.25rem;border-radius:.625rem;color:#fff;font-size:.875rem;max-width:22rem;background:hsl(222,47%,12%);border-left:4px solid #3b82f6;box-shadow:0 10px 30px rgba(0,0,0,.5);animation:slide-in .25s ease}
    .toast.success{border-left-color:#22c55e;background:#14532d}
    .toast.error{border-left-color:#ef4444;background:#450a0a}
    @keyframes slide-in{from{opacity:0;transform:translateX(1.5rem)}to{opacity:1;transform:none}}
    .admin-nav-link{display:flex;align-items:center;gap:.75rem;padding:.625rem .875rem;border-radius:.625rem;font-size:.8125rem;font-weight:500;color:#94a3b8;transition:all .15s;text-decoration:none;margin:0 .5rem}
    .admin-nav-link:hover{color:#e2e8f0;background:rgba(255,255,255,.06)}
    .admin-nav-link.active{color:#93c5fd;background:rgba(59,130,246,.15);font-weight:600}
    .admin-nav-link .nav-icon{width:1.125rem;text-align:center;flex-shrink:0;font-size:.9rem}
    .stat-card{background:hsl(222,47%,10%);border:1px solid rgba(255,255,255,.06);border-radius:.875rem;padding:1.25rem;transition:border-color .2s,box-shadow .2s}
    .stat-card:hover{border-color:rgba(59,130,246,.3);box-shadow:0 8px 24px rgba(0,0,0,.35)}
    .input-admin{background:hsl(222,47%,12%);border:1px solid rgba(255,255,255,.08);color:#e2e8f0;border-radius:.5rem;padding:.5rem .875rem;font-size:.875rem;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
    .input-admin:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.15)}
    .input-admin::placeholder{color:#475569}
    .btn-admin-primary{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1.125rem;border-radius:.5rem;font-size:.8125rem;font-weight:600;background:#2563eb;color:#fff;border:none;cursor:pointer;transition:all .15s}
    .btn-admin-primary:hover{background:#1d4ed8;box-shadow:0 4px 12px rgba(37,99,235,.4)}
    .btn-admin-danger{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1.125rem;border-radius:.5rem;font-size:.8125rem;font-weight:600;background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.2);cursor:pointer;transition:all .15s}
    .btn-admin-danger:hover{background:rgba(239,68,68,.25)}
    .admin-table{width:100%;border-collapse:collapse}
    .admin-table th{padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;border-bottom:1px solid rgba(255,255,255,.06)}
    .admin-table td{padding:.75rem 1rem;font-size:.8125rem;color:#cbd5e1;border-bottom:1px solid rgba(255,255,255,.04)}
    .admin-table tr:hover td{background:rgba(255,255,255,.02)}
    .badge-status{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .65rem;border-radius:9999px;font-size:.7rem;font-weight:600}
    [x-cloak]{display:none!important}
  </style>
</head>
<body class="dark bg-[hsl(222,47%,5%)] text-slate-200 min-h-screen" x-data="{sidebarOpen:true,sidebarMobile:false}">
<div class="toast-container" id="toastContainer"></div>
<div class="flex min-h-screen">
  <!-- Sidebar -->
  <?php require __DIR__ . '/admin-sidebar.php'; ?>
  <!-- Main -->
  <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
    <!-- Top bar -->
    <header class="h-14 bg-[hsl(222,47%,8%)] border-b border-white/[0.06] flex items-center justify-between px-5 flex-shrink-0 sticky top-0 z-40">
      <div class="flex items-center gap-3">
        <button @click="sidebarOpen=!sidebarOpen"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-white/8 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm">
          <a href="/admin" class="text-slate-500 hover:text-slate-300 transition-colors">Admin</a>
          <?php if(isset($pageTitle) && $pageTitle !== 'Dashboard'): ?>
          <i class="fa-solid fa-chevron-right text-slate-600 text-[9px]"></i>
          <span class="text-slate-200 font-medium"><?= clean($pageTitle) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="/" target="_blank"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-slate-400 hover:text-white hover:bg-white/8 transition-all">
          <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> View Store
        </a>
        <div class="h-5 w-px bg-white/10"></div>
        <!-- Admin avatar -->
        <div class="flex items-center gap-2 pl-1">
          <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            <?= strtoupper(substr($currentUser['name']??'A',0,1)) ?>
          </div>
          <span class="text-sm text-slate-300 font-medium hidden sm:block"><?= clean($currentUser['name']??'Admin') ?></span>
          <a href="#" onclick="fetch('/api/auth/logout',{method:'POST'}).then(()=>location.href='/admin/login')"
             class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all ml-1">
            <i class="fa-solid fa-right-from-bracket text-[10px]"></i>
            <span class="hidden sm:block">Logout</span>
          </a>
        </div>
      </div>
    </header>
    <!-- Page content -->
    <main class="flex-1 p-5 overflow-auto">
