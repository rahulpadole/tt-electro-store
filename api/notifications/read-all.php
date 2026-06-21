<?php declare(strict_types=1);
requireLogin();
if(isPatch()){(new NotificationModel())->markAllRead(getCurrentUserId());jsonSuccess(null,'All marked read');}
jsonError('Method not allowed',405);
