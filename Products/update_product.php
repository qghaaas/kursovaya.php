<?php
use objects\Products;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Products.php';
$database = new Database();
$db = $database->getConnection();
$product = new \objects\Products($db);
$data = json_decode(file_get_contents("php://input"));
$product->id_product = $data->id_product;
$product->product_name= $data->product_name;
$product->calories= $data->calories;
$product->weight = $data->weight;
$product->price = $data->price;
if($product->update()){
    http_response_code(200);
    echo json_encode(array("message" => "Сделка обновленна."),
        JSON_UNESCAPED_UNICODE);
}
else
{
    http_response_code(583);
    echo json_encode(array("message" => "Невозможно обновить сделку."),
        JSON_UNESCAPED_UNICODE);
}