<?php declare(strict_types=1);
requireAdmin();
jsonSuccess(['orders'=>(new OrderModel())->count(),'products'=>(new ProductModel())->count(),'users'=>(new UserModel())->count(),'revenue'=>(new OrderModel())->totalRevenue()]);
