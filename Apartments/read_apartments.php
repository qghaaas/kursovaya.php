<?php

use objects\Apartments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/Apartments.php';

$database = new Database();
$db = $database->getConnection();
$ap = new Apartments($db);
$stmt = $ap->readAll();
$num = $stmt->rowCount();

if($num>0){
    $arr = ["records"=>[]];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $arr["records"][] = [
            "apartment_id" => (int)$row['apartment_id'],
            "address" => $row['address'],
            "residents" => (int)$row['residents'],
            "area" => (float)$row['area'],
            "account_id" => isset($row['account_id']) ? (int)$row['account_id'] : null,
        ];
    }
    http_response_code(200);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(["message"=>"Квартиры не найдены"], JSON_UNESCAPED_UNICODE);
}
