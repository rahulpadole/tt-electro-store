<?php declare(strict_types=1);
requireAdmin(); jsonSuccess((new OrderModel())->all(10,0));
