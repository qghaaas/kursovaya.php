<?php

use objects\Dishproducts;

header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    include_once '../config/database.php';
    include_once '../objects/Dishproducts.php';

    $database = new Database();
    $db = $database->getConnection();
    $dishpr = new Dishproducts($db);
    $keywords = isset($_GET['s']) ? $_GET['s'] : "";
    $stmt = $dishpr->search($keywords);
    $num = $stmt->rowCount();
    if($num>0){
        $dishpr_arr = array();
        $dishpr_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $dishpr_item = array(
                "dishes_id" => $dishes_id,
                "id_product" => $id_product,
                "quantity" => $quantity,
            );
            array_push($dishpr_arr["records"], $dishpr_item);
        }
        http_response_code(200);
        echo json_encode($dishpr_arr);
    }
    else{
        http_response_code(404);
        echo json_encode(array("message" => "Кассир не найден."),JSON_UNESCAPED_UNICODE);
    }
