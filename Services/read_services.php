<?php

use objects\Services;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/Services.php';

$database = new Database();
$db = $database->getConnection();
$svc = new Services($db);
$stmt = $svc->readAll();
$num = $stmt->rowCount();

if($num>0){
    $arr = ["records"=>[]];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $arr["records"][] = [
            "service_id" => (int)$row['service_id'],
            "service_type" => $row['service_type'],
            "unit" => $row['unit'],
            "tariff" => (float)$row['tariff'],
        ];
    }
    http_response_code(200);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(["message"=>"Услуги не найдены"], JSON_UNESCAPED_UNICODE);
}
