<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: delete");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Recipes.php';
$database = new Database();
$db = $database->getConnection();
$recipes = new \objects\Recipes($db);
$data = json_decode(file_get_contents("php://input"));
$recipes->recipes_id = $data->recipes_id;
if($recipes->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Клиент удален."),
        JSON_UNESCAPED_UNICODE);
}
else
{
    http_response_code(503);
    echo json_encode(array("message" => "Невозможно удалить клиента."),
        JSON_UNESCAPED_UNICODE);
}
