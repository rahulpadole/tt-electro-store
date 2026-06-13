<?php declare(strict_types=1);
requireAdmin();
$om=new OrderModel(); $pm=new ProductModel(); $um=new UserModel(); $nm=new NewsletterModel();
jsonSuccess([
    'total_orders'   => $om->count(),
    'total_revenue'  => $om->totalRevenue(),
    'total_products' => $pm->count(),
    'total_users'    => $um->count(),
    'subscribers'    => $nm->count(),
    'revenue_by_month'  => $om->revenueByMonth(),
    'sales_by_category' => $om->salesByCategory(),
    'top_products'      => $om->topProducts(5),
    'low_stock'         => $pm->lowStock(10),
    'recent_orders'     => $om->all(5,0),
]);
