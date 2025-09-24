<?php

use objects\Apartments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/Apartments.php';

$database = new Database();
$db = $database->getConnection();
$ap = new Apartments($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->apartment_id)){
    $ap->apartment_id = $data->apartment_id;
    if($ap->delete()){
        http_response_code(200);
        echo json_encode(["message" => "Квартира удалена"], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Не удалось удалить квартиру"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Не хватает поля: apartment_id"], JSON_UNESCAPED_UNICODE);
}
