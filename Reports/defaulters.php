<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$sql = "SELECT t.account_id, t.full_name, t.phone,
           p.payment_id, p.due_date, p.payment_date, p.consumed,
           s.service_id, s.service_type, s.tariff,
           (p.consumed * s.tariff) AS amount
    FROM payments p
    JOIN tenants t ON p.account_id = t.account_id
    JOIN services s ON p.service_id = s.service_id
    WHERE (
        p.paid_on_time = false
        OR (p.payment_date IS NULL AND CURRENT_DATE > p.due_date)
        OR (p.payment_date IS NOT NULL AND p.payment_date > p.due_date)
    )";

$stmt = $db->prepare($sql);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($rows){
    $result = ["records"=>[]];
    foreach($rows as $row){
        $result["records"][] = [
            "account_id" => (int)$row['account_id'],
            "full_name" => $row['full_name'],
            "phone" => $row['phone'],
            "payment_id" => (int)$row['payment_id'],
            "service_id" => (int)$row['service_id'],
            "service_type" => $row['service_type'],
            "consumed" => (float)$row['consumed'],
            "tariff" => (float)$row['tariff'],
            "amount" => (float)$row['amount'],
            "due_date" => $row['due_date'],
            "payment_date" => $row['payment_date'],
        ];
    }
    http_response_code(200);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(200);
    echo json_encode(["records"=>[]], JSON_UNESCAPED_UNICODE);
}
