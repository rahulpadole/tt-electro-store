<?php declare(strict_types=1);
$fm=new FaqModel();
if(isGet()) jsonSuccess($fm->all());
if(isPost()){requireAdmin();$d=getJsonBody();$v=Validator::make($d)->required('question')->required('answer');if($v->fails())jsonError('Validation failed',422,$v->errors());jsonSuccess($fm->create($d),'Created',201);}
jsonError('Method not allowed',405);
