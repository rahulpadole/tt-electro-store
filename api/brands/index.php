<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

try {

    $bm = new BrandModel();

    if (isGet()) {
        jsonSuccess($bm->all());
    }

    if (isPost()) {

        requireAdmin();

        $d = getJsonBody();

        $v = Validator::make($d)
            ->required('name');

        if ($v->fails()) {
            jsonError('Validation failed', 422, $v->errors());
        }

        $brand = $bm->createAndReturn($d);

        jsonSuccess($brand, 'Created', 201);
    }

    jsonError('Method not allowed', 405);

} catch (Throwable $e) {

    jsonError($e->getMessage(), 500);

}
