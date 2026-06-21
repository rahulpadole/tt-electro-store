<?php
/**
 * TT Electro Store — Dynamic XML Sitemap
 * Served at /sitemap.xml via index.php router
 */
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=86400'); // 24h cache

$base = rtrim(SITE_URL, '/');

$pm = new ProductModel();
$bm = new BlogModel();
$cm = new CategoryModel();

$products = $pm->all([], 1, 500)['items'] ?? [];
$blogs    = $bm->all(1, 200)['items'] ?? [];
$cats     = $cm->all();

$now = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

  <!-- Static pages -->
  <url><loc><?= $base ?>/</loc><changefreq>daily</changefreq><priority>1.0</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/products</loc><changefreq>daily</changefreq><priority>0.9</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/blogs</loc><changefreq>weekly</changefreq><priority>0.7</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/diy-kits</loc><changefreq>weekly</changefreq><priority>0.7</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/3d-printing</loc><changefreq>monthly</changefreq><priority>0.6</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/offers</loc><changefreq>daily</changefreq><priority>0.8</priority><lastmod><?= $now ?></lastmod></url>
  <url><loc><?= $base ?>/about</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
  <url><loc><?= $base ?>/contact</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
  <url><loc><?= $base ?>/faq</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
  <url><loc><?= $base ?>/track-order</loc><changefreq>monthly</changefreq><priority>0.4</priority></url>

  <!-- Category pages -->
  <?php foreach($cats as $cat): ?>
  <url>
    <loc><?= $base ?>/products?category_id=<?= (int)$cat['id'] ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  <?php endforeach; ?>

  <!-- Product pages -->
  <?php foreach($products as $p):
    $lastmod = !empty($p['updated_at']) ? date('Y-m-d', strtotime($p['updated_at'])) : $now;
  ?>
  <url>
    <loc><?= $base ?>/products/<?= (int)$p['id'] ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    <lastmod><?= $lastmod ?></lastmod>
    <?php if(!empty($p['thumbnail'])): ?>
    <image:image>
      <image:loc><?= htmlspecialchars($p['thumbnail']) ?></image:loc>
      <image:title><?= htmlspecialchars($p['name']) ?></image:title>
    </image:image>
    <?php endif; ?>
  </url>
  <?php endforeach; ?>

  <!-- Blog posts -->
  <?php foreach($blogs as $b):
    $lastmod = !empty($b['updated_at']) ? date('Y-m-d', strtotime($b['updated_at'])) : $now;
  ?>
  <url>
    <loc><?= $base ?>/blogs/<?= htmlspecialchars($b['slug']) ?></loc>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
    <lastmod><?= $lastmod ?></lastmod>
    <?php if(!empty($b['thumbnail'])): ?>
    <image:image>
      <image:loc><?= htmlspecialchars($b['thumbnail']) ?></image:loc>
      <image:title><?= htmlspecialchars($b['title']) ?></image:title>
    </image:image>
    <?php endif; ?>
  </url>
  <?php endforeach; ?>

</urlset>
