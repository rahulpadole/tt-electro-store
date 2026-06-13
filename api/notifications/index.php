<?php declare(strict_types=1);
$nm=new NotificationModel();
if(isGet()){requireLogin();jsonSuccess($nm->forUser(getCurrentUserId()));}
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('title')->required('message');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($nm->create($d),'Created',201);}
jsonError('Method not allowed',405);
