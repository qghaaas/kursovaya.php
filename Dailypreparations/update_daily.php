<?php
use objects\Dailypreparations;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Dailypreparations.php';
$database = new Database();
$db = $database->getConnection();
$daily = new \objects\Dailypreparations($db);
$data = json_decode(file_get_contents("php://input"));
$daily->daily_id = $data->daily_id;
$daily->dishes_id = $data->dishes_id;
$daily->portions = $data->portions;
$daily->preparation_date = $data->preparation_date;
if($daily->update()){
    http_response_code(200);
    echo json_encode(array("message" => "Валюта была обновленна."),
        JSON_UNESCAPED_UNICODE);
}
else
{
    http_response_code(583);
    echo json_encode(array("message" => "Невозможно обновить валюту."),
        JSON_UNESCAPED_UNICODE);
}