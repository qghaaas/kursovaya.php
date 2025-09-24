<?php

use objects\Apartments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Apartments.php';

$database = new Database();
$db = $database->getConnection();
$ap = new Apartments($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->address) && isset($data->residents) && isset($data->area) && !empty($data->account_id)){
    $ap->address = $data->address;
    $ap->residents = $data->residents;
    $ap->area = $data->area;
    $ap->account_id = $data->account_id;
    if($ap->create()){
        http_response_code(201);
        echo json_encode(["message" => "Квартира создана", "apartment_id" => $ap->apartment_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось создать квартиру. Проверьте уникальность account_id"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает полей: address, residents, area, account_id"], JSON_UNESCAPED_UNICODE);
}
