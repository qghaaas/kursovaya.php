<?php

use objects\Payments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/Payments.php';

$database = new Database();
$db = $database->getConnection();
$pay = new Payments($db);

$payment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($payment_id<=0){
    http_response_code(400);
    echo json_encode(["message"=>"Укажите id платежа в параметре id"], JSON_UNESCAPED_UNICODE);
    exit;
}

$res = $pay->calculateAmountAndPenalty($payment_id);
if($res){
    http_response_code(200);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(["message"=>"Платёж не найден"], JSON_UNESCAPED_UNICODE);
}
