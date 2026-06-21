<?php declare(strict_types=1);
$cm=new CartModel(); $uid=getCurrentUserId(); $id=(int)($_GET['item_id']??0);
if(isPatch()){$d=getJsonBody();$item=$cm->updateItem($id,$uid,(int)($d['quantity']??1));if(!$item)jsonError('Not found',404);jsonSuccess($item,'Updated');}
if(isDelete()){$cm->removeItem($id,$uid);jsonSuccess(null,'Removed');}
jsonError('Method not allowed',405);
