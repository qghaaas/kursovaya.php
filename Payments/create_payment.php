<?php

use objects\Payments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Payments.php';

$database = new Database();
$db = $database->getConnection();
$pay = new Payments($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->account_id) && !empty($data->service_id) && isset($data->consumed) && !empty($data->due_date) && isset($data->paid_on_time)){
    $pay->account_id = $data->account_id;
    $pay->service_id = $data->service_id;
    $pay->consumed = $data->consumed;
    $pay->due_date = $data->due_date; 
    $pay->paid_on_time = $data->paid_on_time;
    $pay->payment_date = isset($data->payment_date) ? $data->payment_date : null;
    if($pay->create()){
        http_response_code(201);
        echo json_encode(["message" => "Оплата создана", "payment_id" => $pay->payment_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось создать оплату"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает полей: account_id, service_id, consumed, due_date (YYYY-MM-DD), paid_on_time, [payment_date]"], JSON_UNESCAPED_UNICODE);
}
