<?php declare(strict_types=1);
$cm=new CouponModel();
if(isGet()){requireAdmin();jsonSuccess($cm->all());}
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('code')->required('discount');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($cm->create($d),'Created',201);}
jsonError('Method not allowed',405);
