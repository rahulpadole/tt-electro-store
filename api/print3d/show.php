<?php declare(strict_types=1);
$pm=new Print3DModel(); $id=(int)($_GET['id']??0);
if(isGet()){$r=$pm->findById($id);if(!$r)jsonError('Not found',404);jsonSuccess($r);}
if(isPatch()){requireAdmin();jsonSuccess($pm->update($id,getJsonBody()),'Updated');}
jsonError('Method not allowed',405);
