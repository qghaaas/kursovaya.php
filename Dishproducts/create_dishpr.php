<?php

use objects\Dishproducts;

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../config/database.php';
    include_once '../objects/Dishproducts.php';
    $database = new Database();
    $db = $database->getConnection();
    $dishpr = new Dishproducts($db);
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->dishes_id)&&!empty($data->id_product)
        &&!empty($data->quantity)) {
        $dishpr->dishes_id = $data->dishes_id;
        $dishpr->id_product = $data->id_product;
        $dishpr->quantity = $data->quantity;

        if ($dishpr->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Кассир был добавлен"),
                JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно добавить кассира"),
            JSON_UNESCAPED_UNICODE);
        }
    }
    else
    {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно найти кассира.",JSON_UNESCAPED_UNICODE));
    }