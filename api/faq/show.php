<?php declare(strict_types=1);
$fm=new FaqModel();$id=(int)($_GET['id']??0);
if(isGet()){$f=$fm->findById($id);if(!$f)jsonError('Not found',404);jsonSuccess($f);}
if(isPatch()){requireAdmin();jsonSuccess($fm->update($id,getJsonBody()),'Updated');}
if(isDelete()){requireAdmin();$fm->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
