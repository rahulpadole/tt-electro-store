<?php declare(strict_types=1);
requireAdmin();
$db=Database::getConnection();
$st=$db->query('SELECT p.id,p.name,p.stock,p.thumbnail,c.name as category_name,b.name as brand_name,p.price FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN brands b ON p.brand_id=b.id WHERE p.is_active=true ORDER BY p.stock ASC');
jsonSuccess($st->fetchAll());
