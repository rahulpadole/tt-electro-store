<?php declare(strict_types=1);
$cm=new CategoryModel(); $id=(int)($_GET['id']??0);
if(isGet()){$c=$cm->findById($id);if(!$c) jsonError('Not found',404);jsonSuccess($c);}
if(isPatch()){requireAdmin();$c=$cm->update($id,getJsonBody());jsonSuccess($c,'Updated');}
if(isDelete()){requireAdmin();$cm->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
