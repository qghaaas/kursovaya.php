<?php

use objects\Payments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Payments.php';

$database = new Database();
$db = $database->getConnection();
$pay = new Payments($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->payment_id)){
    $pay->payment_id = $data->payment_id;
    if($pay->delete()){
        http_response_code(200);
        echo json_encode(["message" => "Платёж удалён"], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось удалить платёж"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает поля: payment_id"], JSON_UNESCAPED_UNICODE);
}
