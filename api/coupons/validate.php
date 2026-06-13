<?php declare(strict_types=1);
if(!isPost()) jsonError('Method not allowed',405);
$d=getJsonBody();
$v=Validator::make($d)->required('code');
if($v->fails()) jsonError('Validation failed',422,$v->errors());
$result=(new CouponModel())->validate($d['code'],(float)($d['order_amount']??0));
if(!$result['valid']) jsonError($result['message'],400);
jsonSuccess($result);
