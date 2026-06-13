<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';
$method = strtoupper($_SERVER['REQUEST_METHOD']);

// ── API Routes ──────────────────────────────────────────────────────────────
if (str_starts_with($uri, '/api/')) {
    header('Content-Type: application/json; charset=utf-8');
    // CORS for dev
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,POST,PATCH,DELETE,OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    if ($method === 'OPTIONS') { http_response_code(200); exit; }

    $apiPath = substr($uri, 4); // strip /api

    // Auth
    if ($apiPath === '/auth/register')          { require __DIR__ . '/api/auth/register.php'; exit; }
    if ($apiPath === '/auth/login')             { require __DIR__ . '/api/auth/login.php'; exit; }
    if ($apiPath === '/auth/logout')            { require __DIR__ . '/api/auth/logout.php'; exit; }
    if ($apiPath === '/auth/me')                { require __DIR__ . '/api/auth/me.php'; exit; }
    if ($apiPath === '/auth/me/update')         { require __DIR__ . '/api/auth/update.php'; exit; }
    if ($apiPath === '/auth/check-email')       { require __DIR__ . '/api/auth/check-email.php'; exit; }
    if ($apiPath === '/auth/change-password')   { require __DIR__ . '/api/auth/change-password.php'; exit; }
    if ($apiPath === '/auth/otp/send')          { require __DIR__ . '/api/auth/otp-send.php'; exit; }
    if ($apiPath === '/auth/otp/verify')        { require __DIR__ . '/api/auth/otp-verify.php'; exit; }
    if ($apiPath === '/auth/forgot-password')   { require __DIR__ . '/api/auth/forgot-password.php'; exit; }
    if ($apiPath === '/auth/reset-password')    { require __DIR__ . '/api/auth/reset-password.php'; exit; }

    // Products
    if ($apiPath === '/products')               { require __DIR__ . '/api/products/index.php'; exit; }
    if ($apiPath === '/products/featured')      { require __DIR__ . '/api/products/featured.php'; exit; }
    if ($apiPath === '/products/trending')      { require __DIR__ . '/api/products/trending.php'; exit; }
    if ($apiPath === '/products/best-sellers')  { require __DIR__ . '/api/products/best-sellers.php'; exit; }
    if ($apiPath === '/products/flash-sale')    { require __DIR__ . '/api/products/flash-sale.php'; exit; }
    if (preg_match('#^/products/(\d+)/reviews$#', $apiPath, $m)) {
        $_GET['product_id'] = $m[1]; require __DIR__ . '/api/reviews/product.php'; exit;
    }
    if (preg_match('#^/products/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/products/show.php'; exit;
    }

    // Categories
    if ($apiPath === '/categories')             { require __DIR__ . '/api/categories/index.php'; exit; }
    if (preg_match('#^/categories/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/categories/show.php'; exit;
    }

    // Brands
    if ($apiPath === '/brands')                 { require __DIR__ . '/api/brands/index.php'; exit; }
    if (preg_match('#^/brands/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/brands/show.php'; exit;
    }

    // Cart
    if ($apiPath === '/cart')                   { require __DIR__ . '/api/cart/index.php'; exit; }
    if (preg_match('#^/cart/(\d+)$#', $apiPath, $m)) {
        $_GET['item_id'] = $m[1]; require __DIR__ . '/api/cart/item.php'; exit;
    }

    // Orders
    if ($apiPath === '/orders')                 { require __DIR__ . '/api/orders/index.php'; exit; }
    if ($apiPath === '/orders/track')           { require __DIR__ . '/api/orders/track.php'; exit; }
    if (preg_match('#^/orders/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/orders/show.php'; exit;
    }

    // Wishlist
    if ($apiPath === '/wishlist')               { require __DIR__ . '/api/wishlist/index.php'; exit; }
    if (preg_match('#^/wishlist/(\d+)$#', $apiPath, $m)) {
        $_GET['product_id'] = $m[1]; require __DIR__ . '/api/wishlist/item.php'; exit;
    }

    // Reviews
    if ($apiPath === '/reviews')                { require __DIR__ . '/api/reviews/index.php'; exit; }
    if (preg_match('#^/reviews/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/reviews/show.php'; exit;
    }

    // Blogs
    if ($apiPath === '/blogs')                  { require __DIR__ . '/api/blogs/index.php'; exit; }
    if (preg_match('#^/blogs/([^/]+)$#', $apiPath, $m)) {
        $_GET['slug'] = $m[1]; require __DIR__ . '/api/blogs/show.php'; exit;
    }

    // 3D Printing
    if ($apiPath === '/print3d')                { require __DIR__ . '/api/print3d/index.php'; exit; }
    if (preg_match('#^/print3d/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/print3d/show.php'; exit;
    }

    // DIY Kits
    if ($apiPath === '/diy-kits')               { require __DIR__ . '/api/diy-kits/index.php'; exit; }
    if (preg_match('#^/diy-kits/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/diy-kits/show.php'; exit;
    }

    // Banners
    if ($apiPath === '/banners')                { require __DIR__ . '/api/banners/index.php'; exit; }
    if (preg_match('#^/banners/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/banners/show.php'; exit;
    }

    // Offers & Coupons
    if ($apiPath === '/offers')                 { require __DIR__ . '/api/offers/index.php'; exit; }
    if (preg_match('#^/offers/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/offers/show.php'; exit;
    }
    if ($apiPath === '/coupons')                { require __DIR__ . '/api/coupons/index.php'; exit; }
    if ($apiPath === '/coupons/validate')       { require __DIR__ . '/api/coupons/validate.php'; exit; }
    if (preg_match('#^/coupons/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/coupons/show.php'; exit;
    }

    // Notifications
    if ($apiPath === '/notifications')          { require __DIR__ . '/api/notifications/index.php'; exit; }
    if ($apiPath === '/notifications/read-all') { require __DIR__ . '/api/notifications/read-all.php'; exit; }
    if (preg_match('#^/notifications/(\d+)/read$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/notifications/read.php'; exit;
    }
    if (preg_match('#^/notifications/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/notifications/show.php'; exit;
    }

    // Newsletter
    if ($apiPath === '/newsletter/subscribe')   { require __DIR__ . '/api/newsletter/subscribe.php'; exit; }
    if ($apiPath === '/newsletter/subscribers') { require __DIR__ . '/api/newsletter/subscribers.php'; exit; }
    if (preg_match('#^/newsletter/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/newsletter/unsubscribe.php'; exit;
    }

    // Contact
    if ($apiPath === '/contact')                { require __DIR__ . '/api/contact/index.php'; exit; }

    // FAQ
    if ($apiPath === '/faq')                    { require __DIR__ . '/api/faq/index.php'; exit; }
    if (preg_match('#^/faq/(\d+)$#', $apiPath, $m)) {
        $_GET['id'] = $m[1]; require __DIR__ . '/api/faq/show.php'; exit;
    }

    // Dashboard
    if ($apiPath === '/dashboard/summary')      { require __DIR__ . '/api/dashboard/summary.php'; exit; }
    if ($apiPath === '/dashboard/stats')        { require __DIR__ . '/api/dashboard/stats.php'; exit; }
    if ($apiPath === '/dashboard/recent-orders'){ require __DIR__ . '/api/dashboard/recent-orders.php'; exit; }
    if ($apiPath === '/dashboard/top-products') { require __DIR__ . '/api/dashboard/top-products.php'; exit; }
    if ($apiPath === '/dashboard/sales-by-category') { require __DIR__ . '/api/dashboard/sales-by-category.php'; exit; }

    // Admin
    if ($apiPath === '/admin/users')            { require __DIR__ . '/api/admin/users.php'; exit; }
    if ($apiPath === '/admin/orders')           { require __DIR__ . '/api/admin/orders.php'; exit; }
    if ($apiPath === '/admin/print3d-requests') { require __DIR__ . '/api/admin/print3d-requests.php'; exit; }
    if ($apiPath === '/admin/inventory')        { require __DIR__ . '/api/admin/inventory.php'; exit; }
    if ($apiPath === '/admin/reviews')          { require __DIR__ . '/api/admin/reviews.php'; exit; }

    // Health
    if ($apiPath === '/healthz')                { jsonSuccess(['status' => 'ok']); }

    jsonError('API endpoint not found', 404);
}

// ── Static Files ─────────────────────────────────────────────────────────────
if (str_starts_with($uri, '/assets/') || str_starts_with($uri, '/storage/')) {
    $file = __DIR__ . $uri;
    if (file_exists($file) && is_file($file)) {
        $mime = mime_content_type($file) ?: 'application/octet-stream';
        header("Content-Type: {$mime}");
        readfile($file);
        exit;
    }
    http_response_code(404); exit;
}

// ── Page Routes ──────────────────────────────────────────────────────────────
function render(string $view, array $data = []): void {
    extract($data);
    $csrfToken = generateCsrfToken();
    $currentUser = getCurrentUser();
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . "/views/{$view}.php";
    require __DIR__ . '/views/layout/footer.php';
}

function renderAdmin(string $view, array $data = []): void {
    requireAdmin();
    extract($data);
    $csrfToken = generateCsrfToken();
    $currentUser = getCurrentUser();
    require __DIR__ . '/views/layout/admin-header.php';
    require __DIR__ . "/views/admin/{$view}.php";
    require __DIR__ . '/views/layout/admin-footer.php';
}

function renderAuth(string $view, array $data = []): void {
    extract($data);
    $csrfToken = generateCsrfToken();
    $currentUser = getCurrentUser();
    require __DIR__ . "/views/{$view}.php";
}

// Public routes
$routes = [
    '/'               => ['view' => 'home',          'auth' => false],
    '/products'       => ['view' => 'products',       'auth' => false],
    '/cart'           => ['view' => 'cart',           'auth' => false],
    '/checkout'       => ['view' => 'checkout',       'auth' => true],
    '/orders'         => ['view' => 'orders',         'auth' => true],
    '/wishlist'       => ['view' => 'wishlist',       'auth' => true],
    '/blogs'          => ['view' => 'blogs',          'auth' => false],
    '/diy-kits'       => ['view' => 'diy-kits',       'auth' => false],
    '/3d-printing'    => ['view' => 'print3d',        'auth' => false],
    '/offers'         => ['view' => 'offers',         'auth' => false],
    '/dashboard'      => ['view' => 'dashboard',      'auth' => true],
    '/notifications'  => ['view' => 'notifications',  'auth' => true],
    '/track-order'    => ['view' => 'track-order',    'auth' => false],
    '/about'          => ['view' => 'about',          'auth' => false],
    '/contact'        => ['view' => 'contact',        'auth' => false],
    '/faq'            => ['view' => 'faq',            'auth' => false],
    '/privacy-policy' => ['view' => 'privacy-policy', 'auth' => false],
    '/terms'          => ['view' => 'terms',          'auth' => false],
    '/login'          => ['view' => 'login',          'auth' => false, 'plain' => true],
    '/register'       => ['view' => 'register',       'auth' => false, 'plain' => true],
];

// Admin routes
$adminRoutes = [
    '/admin'              => 'index',
    '/admin/products'     => 'products',
    '/admin/orders'       => 'orders',
    '/admin/inventory'    => 'inventory',
    '/admin/categories'   => 'categories',
    '/admin/brands'       => 'brands',
    '/admin/banners'      => 'banners',
    '/admin/blogs'        => 'blogs',
    '/admin/faq'          => 'faq',
    '/admin/offers'       => 'offers',
    '/admin/diy-kits'     => 'diy-kits',
    '/admin/reviews'      => 'reviews',
    '/admin/newsletter'   => 'newsletter',
    '/admin/notifications'=> 'notifications',
    '/admin/print3d'      => 'print3d',
];

// ── Google OAuth routes (redirects, not API JSON) ──────────────────────────
if ($uri === '/auth/google') {
    require __DIR__ . '/api/auth/google.php'; exit;
}
if ($uri === '/auth/google/callback') {
    require __DIR__ . '/api/auth/google-callback.php'; exit;
}

// Admin login (plain, no admin layout)
if ($uri === '/admin/login') {
    if (isAdmin()) redirect('/admin');
    renderAuth('admin-login');
    exit;
}

// Admin pages
if (array_key_exists($uri, $adminRoutes)) {
    renderAdmin($adminRoutes[$uri]);
    exit;
}

// Product detail  /products/{id}
if (preg_match('#^/products/(\d+)$#', $uri, $m)) {
    $productId = (int)$m[1];
    render('product-detail', ['productId' => $productId]);
    exit;
}

// Order detail  /orders/{id}
if (preg_match('#^/orders/(\d+)$#', $uri, $m)) {
    requireLogin();
    $orderId = (int)$m[1];
    render('order-detail', ['orderId' => $orderId]);
    exit;
}

// Blog detail  /blogs/{slug}
if (preg_match('#^/blogs/([^/]+)$#', $uri, $m)) {
    render('blog-detail', ['blogSlug' => $m[1]]);
    exit;
}

// Dashboard sub-pages (legacy redirects → new unified dashboard)
if ($uri === '/dashboard/orders')  { requireLogin(); redirect('/dashboard?tab=orders');  exit; }
if ($uri === '/dashboard/profile') { requireLogin(); redirect('/dashboard?tab=profile'); exit; }

// Phone verification (post Google OAuth)
if ($uri === '/verify-phone') {
    if (!isLoggedIn()) redirect('/login');
    renderAuth('verify-phone');
    exit;
}

// Forgot password
if ($uri === '/forgot-password') {
    renderAuth('forgot-password');
    exit;
}

// Matched route
if (array_key_exists($uri, $routes)) {
    $route = $routes[$uri];
    if ($route['auth']) requireLogin();
    if (isset($route['plain']) && $route['plain']) {
        renderAuth($route['view']);
    } else {
        render($route['view']);
    }
    exit;
}

// Sitemap
if ($uri === '/sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

// 404
http_response_code(404);
render('not-found');
