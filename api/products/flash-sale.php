<?php declare(strict_types=1);
$limit = (int)($_GET['limit']??8);
jsonSuccess((new ProductModel())->flashSale($limit));
