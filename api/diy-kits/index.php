<?php declare(strict_types=1);
$dm=new DiyKitModel();
if(isGet()) jsonSuccess($dm->all());
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('name')->required('price');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($dm->create($d),'Created',201);}
jsonError('Method not allowed',405);
