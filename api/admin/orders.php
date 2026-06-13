<?php declare(strict_types=1);
requireAdmin();
$limit=(int)($_GET['limit']??50); $offset=(int)($_GET['offset']??0);
jsonSuccess((new OrderModel())->all($limit,$offset));
