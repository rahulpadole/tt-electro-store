<?php
declare(strict_types=1);
$pm = new ProductModel();
$id = (int)($_GET['id']??0);
if(isGet()){
    $p = $pm->findById($id);
    if(!$p) jsonError('Product not found',404);
    jsonSuccess($p);
}
if(isPatch()){
    requireAdmin();
    $d = getJsonBody();
    $p = $pm->update($id,$d);
    if(!$p) jsonError('Product not found',404);
    jsonSuccess($p,'Updated');
}
if(isDelete()){
    requireAdmin();
    $pm->delete($id);
    jsonSuccess(null,'Deleted');
}
jsonError('Method not allowed',405);
