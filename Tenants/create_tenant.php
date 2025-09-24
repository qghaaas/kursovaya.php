<?php

use objects\Tenants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Tenants.php';

$database = new Database();
$db = $database->getConnection();
$tenant = new Tenants($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->full_name) && !empty($data->phone)){
    $tenant->full_name = $data->full_name;
    $tenant->phone = $data->phone;
    if($tenant->create()){
        http_response_code(201);
        echo json_encode(["message" => "Квартиросъемщик создан", "account_id" => $tenant->account_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось создать квартиросъемщика"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает полей: full_name, phone"], JSON_UNESCAPED_UNICODE);
}
