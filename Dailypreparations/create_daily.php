<?php

use objects\Dailypreparations;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Dailypreparations.php';
$database = new Database();
$db = $database->getConnection();
$daily = new \objects\Dailypreparations($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->daily_id)&&!empty($data->dishes_id)
    &&!empty($data->portions)&&!empty($data->preparation_date)) {
    $daily->daily_id = $data->daily_id;
    $daily->dishes_id = $data->dishes_id;
    $daily->portions = $data->portions;
    $daily->preparation_date = $data->preparation_date;

    if ($daily->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Валюты была добавлена"),
            JSON_UNESCAPED_UNICODE);
    }
    else
    {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить валюту"),
            JSON_UNESCAPED_UNICODE);
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("message" => "Невозможно найти валюту.",JSON_UNESCAPED_UNICODE));
}