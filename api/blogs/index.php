<?php declare(strict_types=1);
$bm=new BlogModel();
if(isGet()){
    $page=(int)($_GET['page']??1); $perPage=(int)($_GET['per_page']??ITEMS_PER_PAGE); $cat=$_GET['category']??'';
    jsonSuccess($bm->all($page,$perPage,$cat));
}
if(isPost()){
    requireAdmin(); $d=getJsonBody();
    $v=Validator::make($d)->required('title')->required('content');
    if($v->fails()) jsonError('Validation failed',422,$v->errors());
    jsonSuccess($bm->create($d),'Created',201);
}
jsonError('Method not allowed',405);
