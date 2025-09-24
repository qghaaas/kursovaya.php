<?php

use objects\Dish;

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../config/database.php';
    include_once '../objects/Dish.php';
    $database = new Database();
    $db = $database->getConnection();
    $dish = new Dish($db);
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->dishes_id)&&!empty($data->dishes_name)
        &&!empty($data->dish_type)&&!empty($data->output_weight)) {
        $dish->dishes_id = $data->dishes_id;
        $dish->dishes_name = $data->dishes_name;
        $dish->dish_type = $data->dish_type;
        $dish->output_weight = $data->output_weight;

        if ($dish->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Блюдо добавлено"),
                JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно добавить блюдо"),
            JSON_UNESCAPED_UNICODE);
        }
    }
    else
    {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно найти блюдо.",JSON_UNESCAPED_UNICODE));
    }
