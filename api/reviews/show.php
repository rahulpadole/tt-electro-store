<?php declare(strict_types=1);
$id=(int)($_GET['id']??0);
if(isDelete()){requireAdmin();(new ReviewModel())->delete($id);jsonSuccess(null,'Deleted');}
jsonError('Method not allowed',405);
