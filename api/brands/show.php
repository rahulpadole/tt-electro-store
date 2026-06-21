<?php declare(strict_types=1);
$bm=new BrandModel();$id=(int)($_GET['id']??0);
if(isGet()){$b=$bm->findById($id);if(!$b)jsonError('Not found',404);jsonSuccess($b);}
if(isPatch()){requireAdmin();jsonSuccess($bm->update($id,getJsonBody()),'Updated');}
if(isDelete()){requireAdmin();$bm->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
