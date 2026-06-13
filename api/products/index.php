<?php
declare(strict_types=1);
$pm = new ProductModel();
if(isGet()){
    $filters = ['q'=>$_GET['q']??'','category_id'=>$_GET['category_id']??'','brand_id'=>$_GET['brand_id']??'','min_price'=>$_GET['min_price']??'','max_price'=>$_GET['max_price']??'','sort'=>$_GET['sort']??'newest'];
    $page = (int)($_GET['page']??1);
    $perPage = (int)($_GET['per_page']??ITEMS_PER_PAGE);
    jsonSuccess($pm->all($filters,$page,$perPage));
}
if(isPost()){
    requireAdmin();
    $d = getJsonBody();
    $v = Validator::make($d)->required('name')->required('price')->numeric('price')->required('category_id');
    if($v->fails()) jsonError('Validation failed',422,$v->errors());
    jsonSuccess($pm->create($d),'Product created',201);
}
jsonError('Method not allowed',405);
