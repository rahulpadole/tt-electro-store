<?php declare(strict_types=1);
if(!isPost()) jsonError('Method not allowed',405);
$d=getJsonBody();
$v=Validator::make($d)->required('name')->required('email')->email('email')->required('message');
if($v->fails()) jsonError('Validation failed',422,$v->errors());
(new ContactModel())->create($d);
jsonSuccess(null,'Message sent! We\'ll get back to you soon.');
