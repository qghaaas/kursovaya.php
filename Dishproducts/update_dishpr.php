<?php
use objects\Dishproducts;
header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../config/database.php';
    include_once '../objects/Dishproducts.php';
    $database = new Database();
    $db = $database->getConnection();
    $dishpr = new Dishproducts($db);
    $data = json_decode(file_get_contents("php://input"));
    $dishpr->dishes_id = $data->dishes_id;
    $dishpr->id_product = $data->id_product;
    $dishpr->quantity = $data->quantity;
    if($dishpr->update()){
        http_response_code(200);
        echo json_encode(array("message" => "Кассир обновлен."),
            JSON_UNESCAPED_UNICODE);
    }
    else
    {
        http_response_code(583);
        echo json_encode(array("message" => "Невозможно обновить кассира."),
            JSON_UNESCAPED_UNICODE);
    }