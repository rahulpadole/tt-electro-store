<?php declare(strict_types=1);
$dm=new DiyKitModel();$id=(int)($_GET['id']??0);
if(isGet()){$k=$dm->findById($id);if(!$k)jsonError('Not found',404);jsonSuccess($k);}
if(isPatch()){requireAdmin();jsonSuccess($dm->update($id,getJsonBody()),'Updated');}
if(isDelete()){requireAdmin();$dm->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
