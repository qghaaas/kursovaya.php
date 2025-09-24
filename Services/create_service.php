<?php

use objects\Services;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Services.php';

$database = new Database();
$db = $database->getConnection();
$svc = new Services($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->service_type) && !empty($data->unit) && isset($data->tariff)){
    $svc->service_type = $data->service_type;
    $svc->unit = $data->unit;
    $svc->tariff = $data->tariff;
    if($svc->create()){
        http_response_code(201);
        echo json_encode(["message" => "Услуга создана", "service_id" => $svc->service_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось создать услугу"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает полей: service_type, unit, tariff"], JSON_UNESCAPED_UNICODE);
}
