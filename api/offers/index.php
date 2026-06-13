<?php declare(strict_types=1);
$om=new OfferModel();
if(isGet()) jsonSuccess($om->active());
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('title');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($om->create($d),'Created',201);}
jsonError('Method not allowed',405);
