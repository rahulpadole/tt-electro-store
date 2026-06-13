<?php declare(strict_types=1);
$om=new OfferModel();$id=(int)($_GET['id']??0);
if(isGet()){$o=$om->findById($id);if(!$o)jsonError('Not found',404);jsonSuccess($o);}
if(isPatch()){requireAdmin();jsonSuccess($om->update($id,getJsonBody()),'Updated');}
if(isDelete()){requireAdmin();$om->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
