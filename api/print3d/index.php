<?php declare(strict_types=1);
$pm=new Print3DModel(); $uid=getCurrentUserId();
if(isGet()){
    if(isAdmin()) jsonSuccess($pm->all());
    requireLogin(); jsonSuccess($pm->forUser($uid));
}
if(isPost()){
    requireLogin(); $d=getJsonBody();
    $v=Validator::make($d)->required('material')->required('quantity')->integer('quantity');
    if($v->fails()) jsonError('Validation failed',422,$v->errors());
    jsonSuccess($pm->create($uid,$d),'Request submitted',201);
}
jsonError('Method not allowed',405);
