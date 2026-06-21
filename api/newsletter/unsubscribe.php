<?php declare(strict_types=1);
$id=(int)($_GET['id']??0);
if(isDelete()){requireAdmin();(new NewsletterModel())->unsubscribe($id);jsonSuccess(null,'Unsubscribed');}
jsonError('Method not allowed',405);
