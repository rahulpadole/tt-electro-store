<?php declare(strict_types=1);
$bm=new BrandModel();
if(isGet()) jsonSuccess($bm->all());
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('name');if($v->fails()) jsonError('Validation failed',422,$v->errors());jsonSuccess($bm->create($d),'Created',201);}
jsonError('Method not allowed',405);
