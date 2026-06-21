<?php declare(strict_types=1);
requireAdmin();
$limit=(int)($_GET['limit']??100); $offset=(int)($_GET['offset']??0);
jsonSuccess((new UserModel())->all($limit,$offset));
