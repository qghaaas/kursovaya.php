<?php

use objects\Dailypreparations;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Dailypreparations.php";

$database = new Database();
$db = $database->getConnection();

$daily= new \objects\Dailypreparations($db);

$stmt = $daily->readAll();
$num = $stmt->rowCount();

if ($num > 0) {

    $daily_arr = array();
    $daily_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        $daily_item = array(
            "daily_id" =>$daily_id,
            "dishes_id" =>$dishes_id,
            "portions" =>$portions,
            "preparation_date" =>$preparation_date,
        );
        $daily_arr["records"][] = $daily_item;
    }
    http_response_code(200);

    echo json_encode($daily_arr);
} else {

    http_response_code(404);


    echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);}
