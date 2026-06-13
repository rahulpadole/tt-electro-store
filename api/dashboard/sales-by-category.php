<?php declare(strict_types=1);
requireAdmin(); jsonSuccess((new OrderModel())->salesByCategory());
