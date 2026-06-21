<?php declare(strict_types=1);
if(isPost()){
    $d=getJsonBody();
    $orderNumber=$d['order_number']??$_GET['order_number']??'';
    $email=$d['email']??$_GET['email']??'';
    if(empty($orderNumber)) jsonError('Order number required',400);
    $om=new OrderModel();
    $order=$om->findByOrderNumber($orderNumber);
    if(!$order) jsonError('Order not found',404);
    // Optionally verify email
    if($email){
        $user=(new UserModel())->findById((int)$order['user_id']);
        if($user && strtolower($user['email'])!==strtolower($email)) jsonError('Order not found',404);
    }
    jsonSuccess($order);
}
if(isGet()) jsonSuccess(['message'=>'Use POST to track']);
jsonError('Method not allowed',405);
