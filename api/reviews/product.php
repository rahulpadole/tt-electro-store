<?php declare(strict_types=1);
$rm=new ReviewModel(); $pid=(int)($_GET['product_id']??0);
if(isGet()) jsonSuccess($rm->forProduct($pid));
if(isPost()){
    requireLogin();
    $d=getJsonBody();
    $v=Validator::make($d)->required('rating')->integer('rating')->in('rating',['1','2','3','4','5']);
    if($v->fails()) jsonError('Validation failed',422,$v->errors());
    jsonSuccess($rm->create($pid,getCurrentUserId(),$d),'Review submitted',201);
}
jsonError('Method not allowed',405);
