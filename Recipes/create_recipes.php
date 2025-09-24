<?php

use objects\Recipes;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Recipes.php';
$database = new Database();
$db = $database->getConnection();
$recipes = new Recipes($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->recipes_id)&&!empty($data->dishes_id)
    &&!empty($data->cooking_time)&&!empty($data->technology)) {
    $recipes->recipes_id = $data->recipes_id;
    $recipes->dishes_id = $data->dishes_id;
    $recipes->cooking_time = $data->cooking_time;
    $recipes->technology = $data->technology;

    if ($recipes->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Клиент был добавлен"),
            JSON_UNESCAPED_UNICODE);
    }
    else
    {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить клиента"),
            JSON_UNESCAPED_UNICODE);
    }
}
     else
     {
    http_response_code(400);
    echo json_encode(array("message" => "Невозможно найти клиента.",JSON_UNESCAPED_UNICODE));
     }