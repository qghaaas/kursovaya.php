<?php

use objects\Payments;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/Payments.php';

$database = new Database();
$db = $database->getConnection();
$pay = new Payments($db);

$keywords = isset($_GET['s']) ? $_GET['s'] : "";
$stmt = $pay->search($keywords);
$num = $stmt->rowCount();

if($num>0){
    $arr = ["records"=>[]];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $arr["records"][] = [
            "payment_id" => (int)$row['payment_id'],
            "account_id" => (int)$row['account_id'],
            "service_id" => (int)$row['service_id'],
            "consumed" => (float)$row['consumed'],
            "due_date" => $row['due_date'],
            "paid_on_time" => (bool)$row['paid_on_time'],
            "payment_date" => $row['payment_date'],
        ];
    }
    http_response_code(200);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(["message"=>"Платёж не найден"], JSON_UNESCAPED_UNICODE);
}
