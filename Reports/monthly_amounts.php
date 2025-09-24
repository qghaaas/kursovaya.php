<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$month = isset($_GET['month']) ? $_GET['month'] : null; 
if(!$month){
    http_response_code(400);
    echo json_encode(["message"=>"Укажите month=YYYY-MM"], JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "SELECT t.account_id, t.full_name, s.service_id, s.service_type, s.tariff,
               p.payment_id, p.consumed, p.due_date, p.paid_on_time, p.payment_date,
               (p.consumed * s.tariff) AS amount
        FROM payments p
        JOIN tenants t ON p.account_id = t.account_id
        JOIN services s ON p.service_id = s.service_id
        WHERE to_char(p.due_date, 'YYYY-MM') = :month
        ORDER BY t.account_id, s.service_id";

$stmt = $db->prepare($sql);
$stmt->bindParam(':month', $month);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$today = new DateTime();
$result = ["records"=>[]];
foreach($rows as $row){
    $amount = (float)$row['amount'];
    $due = new DateTime($row['due_date']);
    $paidOnTime = filter_var($row['paid_on_time'], FILTER_VALIDATE_BOOLEAN);
    $paymentDate = $row['payment_date'] ? new DateTime($row['payment_date']) : ($paidOnTime ? $due : null);
    $penalty = 0.0;
    if ($paymentDate === null || $paymentDate > $due) {
        $endDate = $paymentDate ?: $today;
        if ($endDate > $due) {
            $diffDays = (int)$due->diff($endDate)->format('%a');
            $penalty = $amount * 0.001 * $diffDays;
        }
    }
    $result["records"][] = [
        "account_id" => (int)$row['account_id'],
        "full_name" => $row['full_name'],
        "service_id" => (int)$row['service_id'],
        "service_type" => $row['service_type'],
        "payment_id" => (int)$row['payment_id'],
        "consumed" => (float)$row['consumed'],
        "tariff" => (float)$row['tariff'],
        "amount" => round($amount, 2),
        "penalty" => round($penalty, 2),
        "total_due" => round($amount + $penalty, 2),
        "due_date" => $row['due_date'],
        "payment_date" => $row['payment_date'],
    ];
}

http_response_code(200);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
