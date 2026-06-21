<?php declare(strict_types=1);
if(!isPost()) jsonError('Method not allowed',405);
$d=getJsonBody();
$v=Validator::make($d)->required('email')->email('email');
if($v->fails()) jsonError('Validation failed',422,$v->errors());
$result=(new NewsletterModel())->subscribe($d['email']);
if(isset($result['exists'])) jsonSuccess(null,'Already subscribed');
jsonSuccess($result,'Subscribed successfully',201);
