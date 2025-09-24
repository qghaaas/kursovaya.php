<?php

use objects\Tenants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/Tenants.php';

$database = new Database();
$db = $database->getConnection();
$tenant = new Tenants($db);
$stmt = $tenant->readAll();
$num = $stmt->rowCount();

if($num>0){
    $arr = ["records"=>[]];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $arr["records"][] = [
            "account_id" => (int)$row['account_id'],
            "full_name" => $row['full_name'],
            "phone" => $row['phone'],
        ];
    }
    http_response_code(200);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(["message"=>"Квартиросъемщики не найдены"], JSON_UNESCAPED_UNICODE);
}
