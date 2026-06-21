<?php declare(strict_types=1);
$bm=new BlogModel(); $slug=$_GET['slug']??'';
if(isGet()){
    // Try as slug first, then numeric id
    $blog=is_numeric($slug)?$bm->findById((int)$slug):$bm->findBySlug($slug);
    if(!$blog) jsonError('Not found',404);
    $bm->incrementViews((int)$blog['id']);
    jsonSuccess($blog);
}
if(isPatch()){requireAdmin();$id=(int)$slug;$blog=$bm->update($id,getJsonBody());jsonSuccess($blog,'Updated');}
if(isDelete()){requireAdmin();$bm->delete((int)$slug);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
