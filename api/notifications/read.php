<?php declare(strict_types=1);
requireLogin();
if(isPatch()){(new NotificationModel())->markRead((int)($_GET['id']??0),getCurrentUserId());jsonSuccess(null,'Marked read');}
jsonError('Method not allowed',405);
