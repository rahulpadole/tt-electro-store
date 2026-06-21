<?php
$pageTitle = 'Dashboard';
$om = new OrderModel(); $pm = new ProductModel(); $um = new UserModel(); $nm = new NewsletterModel();
$totalOrders   = $om->count();
$totalRevenue  = $om->totalRevenue();
$totalProducts = $pm->count();
$totalUsers    = $um->count();
$subscribers   = $nm->count();
$recentOrders  = $om->all(6,0);
$topProducts   = $om->topProducts(5);
$revenueByMonth= $om->revenueByMonth();
$salesByCat    = $om->salesByCategory();
$lowStock      = $pm->lowStock(5);
$statusColors  = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
?>

<!-- Page header -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-xl font-bold text-white">Dashboard</h1>
    <p class="text-slate-500 text-sm mt-0.5">Welcome back, <?= clean($currentUser['name']??'Admin') ?>. Here's what's happening.</p>
  </div>
  <div class="flex items-center gap-2">
    <span class="text-xs text-slate-500 bg-[hsl(222,47%,12%)] px-3 py-1.5 rounded-lg border border-white/6">
      <i class="fa-regular fa-clock mr-1.5"></i><?= date('D, d M Y') ?>
    </span>
  </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
  <?php
  $stats = [
    ['Total Revenue',    '₹'.number_format($totalRevenue,0),  'fa-indian-rupee-sign', 'text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/15', 'All time earnings'],
    ['Total Orders',     number_format($totalOrders),          'fa-clipboard-list',    'text-blue-400',    'bg-blue-500/10',    'border-blue-500/15',    'Across all statuses'],
    ['Products',         number_format($totalProducts),        'fa-box',               'text-purple-400',  'bg-purple-500/10',  'border-purple-500/15',  'Active listings'],
    ['Customers',        number_format($totalUsers),           'fa-users',             'text-cyan-400',    'bg-cyan-500/10',    'border-cyan-500/15',    'Registered users'],
  ];
  foreach($stats as [$label,$val,$icon,$textColor,$bg,$border,$sub]): ?>
  <div class="stat-card border <?= $border ?>">
    <div class="flex items-start justify-between mb-3">
      <div class="w-10 h-10 rounded-xl <?= $bg ?> flex items-center justify-center flex-shrink-0">
        <i class="fa-solid <?= $icon ?> <?= $textColor ?> text-base"></i>
      </div>
      <span class="text-xs text-slate-600 font-medium"><?= $sub ?></span>
    </div>
    <p class="text-2xl font-bold <?= $textColor ?> leading-none"><?= $val ?></p>
    <p class="text-xs text-slate-500 mt-1.5 font-medium"><?= $label ?></p>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
  <!-- Revenue Chart -->
  <div class="xl:col-span-2 bg-[hsl(222,47%,10%)] border border-white/[0.06] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="font-semibold text-white text-sm">Revenue Trend</h2>
        <p class="text-slate-500 text-xs mt-0.5">Last 12 months</p>
      </div>
      <span class="text-xs text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg font-semibold border border-emerald-500/15">
        ₹<?= number_format($totalRevenue/1000,1) ?>K total
      </span>
    </div>
    <canvas id="revenueChart" height="90"></canvas>
  </div>

  <!-- Sales by Category (donut) -->
  <div class="bg-[hsl(222,47%,10%)] border border-white/[0.06] rounded-2xl p-5">
    <div class="mb-4">
      <h2 class="font-semibold text-white text-sm">Sales by Category</h2>
      <p class="text-slate-500 text-xs mt-0.5">Revenue distribution</p>
    </div>
    <canvas id="catChart" height="165"></canvas>
  </div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">

  <!-- Recent Orders -->
  <div class="bg-[hsl(222,47%,10%)] border border-white/[0.06] rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
      <h2 class="font-semibold text-white text-sm">Recent Orders</h2>
      <a href="/admin/orders" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 transition-colors">
        View all <i class="fa-solid fa-arrow-right text-[9px]"></i>
      </a>
    </div>
    <div class="divide-y divide-white/[0.04]">
      <?php if(empty($recentOrders)): ?>
      <div class="px-5 py-8 text-center text-slate-500 text-sm">No orders yet.</div>
      <?php else: foreach($recentOrders as $order):
        $sc = $statusColors[$order['status']] ?? 'gray';
      ?>
      <div class="flex items-center justify-between px-5 py-3 hover:bg-white/[0.02] transition-colors">
        <div class="min-w-0 flex-1 mr-3">
          <p class="text-sm font-semibold text-white truncate"><?= clean($order['order_number']) ?></p>
          <p class="text-xs text-slate-500 mt-0.5"><?= clean($order['user_name']??'Guest') ?> · <?= date('d M',strtotime($order['created_at'])) ?></p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
          <span class="badge-status bg-<?= $sc ?>-500/15 text-<?= $sc ?>-400 border border-<?= $sc ?>-500/20 capitalize"><?= clean($order['status']) ?></span>
          <span class="text-sm font-bold text-white">₹<?= number_format((float)$order['total'],0) ?></span>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <!-- Low Stock -->
  <div class="bg-[hsl(222,47%,10%)] border border-white/[0.06] rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
      <h2 class="font-semibold text-white text-sm flex items-center gap-2">
        <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
        Low Stock Alerts
      </h2>
      <a href="/admin/inventory" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 transition-colors">
        Manage <i class="fa-solid fa-arrow-right text-[9px]"></i>
      </a>
    </div>
    <?php if(empty($lowStock)): ?>
    <div class="px-5 py-8 text-center">
      <i class="fa-solid fa-circle-check text-emerald-400 text-2xl mb-2 block"></i>
      <p class="text-slate-400 text-sm font-medium">All products stocked!</p>
      <p class="text-slate-600 text-xs mt-1">No low inventory alerts.</p>
    </div>
    <?php else: ?>
    <div class="divide-y divide-white/[0.04]">
      <?php foreach($lowStock as $p): ?>
      <div class="flex items-center gap-3 px-5 py-3 hover:bg-white/[0.02] transition-colors">
        <?php if($p['thumbnail']): ?>
        <img src="<?= clean($p['thumbnail']) ?>" class="w-9 h-9 rounded-lg object-cover flex-shrink-0 bg-white/5">
        <?php else: ?>
        <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 text-slate-500">
          <i class="fa-solid fa-box text-sm"></i>
        </div>
        <?php endif; ?>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-slate-200 truncate font-medium"><?= clean($p['name']) ?></p>
          <p class="text-xs text-slate-500">SKU #<?= $p['id'] ?></p>
        </div>
        <span class="badge-status flex-shrink-0 <?= $p['stock']<=0 ? 'bg-red-500/15 text-red-400 border border-red-500/20' : 'bg-amber-500/15 text-amber-400 border border-amber-500/20' ?>">
          <?= $p['stock'] ?> left
        </span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Quick Stats Footer Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
  <?php foreach([
    ['Newsletter Subs',   $subscribers,         'fa-envelope',   'text-blue-400'],
    ['Top Products',      count($topProducts),   'fa-trophy',     'text-amber-400'],
    ['Low Stock Items',   count($lowStock),      'fa-triangle-exclamation','text-red-400'],
    ['Avg Order Value',   $totalOrders>0?'₹'.number_format($totalRevenue/$totalOrders,0):'₹0','fa-chart-line','text-emerald-400'],
  ] as [$l,$v,$i,$tc]): ?>
  <div class="bg-[hsl(222,47%,10%)] border border-white/[0.06] rounded-xl p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
      <i class="fa-solid <?= $i ?> <?= $tc ?> text-sm"></i>
    </div>
    <div>
      <p class="text-white font-bold text-sm"><?= is_numeric($v) ? number_format((int)$v) : $v ?></p>
      <p class="text-slate-500 text-xs"><?= $l ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<script>
const revenueData = <?= json_encode($revenueByMonth) ?>;
const catData     = <?= json_encode($salesByCat) ?>;

Chart.defaults.color = '#64748b';

new Chart(document.getElementById('revenueChart'), {
  type:'line',
  data:{
    labels: revenueData.map(r=>r.month),
    datasets:[{
      label:'Revenue (₹)',
      data: revenueData.map(r=>parseFloat(r.revenue)||0),
      borderColor:'#3b82f6',
      backgroundColor:'rgba(59,130,246,0.08)',
      tension:.4, fill:true,
      pointBackgroundColor:'#3b82f6',
      pointBorderColor:'hsl(222,47%,10%)',
      pointBorderWidth:2,
      pointRadius:4,
    }]
  },
  options:{
    responsive:true,
    plugins:{ legend:{display:false}, tooltip:{
      backgroundColor:'hsl(222,47%,14%)',
      borderColor:'rgba(255,255,255,.08)',
      borderWidth:1,
      titleColor:'#e2e8f0',
      bodyColor:'#94a3b8',
      callbacks:{ label: ctx=>'₹'+ctx.raw.toLocaleString('en-IN') }
    }},
    scales:{
      x:{ grid:{color:'rgba(255,255,255,.04)'}, ticks:{color:'#475569',font:{size:11}} },
      y:{ grid:{color:'rgba(255,255,255,.04)'}, ticks:{color:'#475569',font:{size:11}, callback:v=>'₹'+(v>=1000?Math.round(v/1000)+'K':v)} }
    }
  }
});

new Chart(document.getElementById('catChart'), {
  type:'doughnut',
  data:{
    labels: catData.map(c=>c.name),
    datasets:[{
      data: catData.map(c=>parseFloat(c.total)||0),
      backgroundColor:['#3b82f6','#06b6d4','#8b5cf6','#f59e0b','#22c55e','#ef4444','#ec4899','#f97316'],
      borderWidth:0, hoverOffset:6,
    }]
  },
  options:{
    responsive:true,
    cutout:'68%',
    plugins:{
      legend:{
        position:'bottom',
        labels:{color:'#64748b',font:{size:10},boxWidth:8,padding:10,usePointStyle:true}
      },
      tooltip:{
        backgroundColor:'hsl(222,47%,14%)',
        borderColor:'rgba(255,255,255,.08)',
        borderWidth:1,
        titleColor:'#e2e8f0',
        bodyColor:'#94a3b8',
        callbacks:{ label: ctx=>' ₹'+ctx.raw.toLocaleString('en-IN') }
      }
    }
  }
});
</script>
