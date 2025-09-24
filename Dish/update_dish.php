<?php
use objects\Dish;
header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../config/database.php';
    include_once '../objects/Dish.php';
    $database = new Database();
    $db = $database->getConnection();
    $dish = new Dish($db);
    $data = json_decode(file_get_contents("php://input"));
    $dish->dishes_id = $data->dishes_id;
    $dish-> dishes_name = $data-> dishes_name;
    $dish->dish_type = $data->dish_type;
    $dish->output_weight = $data->output_weight;
    if($dish->update()){
        http_response_code(200);
        echo json_encode(array("message" => "Кассир обновлен."),
            JSON_UNESCAPED_UNICODE);
    }
    else
    {
        http_response_code(583);
        echo json_encode(array("message" => "Невозможно обновить блюдо."),
            JSON_UNESCAPED_UNICODE);
    }
