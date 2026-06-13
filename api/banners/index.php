<?php declare(strict_types=1);
$bm=new BannerModel();
if(isGet()) jsonSuccess($bm->active());
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('title')->required('image');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($bm->create($d),'Created',201);}
jsonError('Method not allowed',405);
