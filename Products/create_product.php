<?php

use objects\Products;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/Products.php';
$database = new Database();
$db = $database->getConnection();
$product = new \objects\Products($db);
$data = json_decode(file_get_contents("php://input"));
//    echo $data->product_name;
////    echo $data->description;
//    echo $data->price;
//    echo $data->category_id;
if(!empty($data->id_product)&&!empty($data->product_name)
    &&!empty($data->calories)&&!empty($data->weight) &&!empty($data->price))
{
    $product->id_product = $data->id_product;
    $product->product_name= $data->product_name;
    $product->calories= $data->calories;
    $product->weight = $data->weight;
    $product->price = $data->price;

    if ($product->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Сделка была добавлена"),
            JSON_UNESCAPED_UNICODE);
    }
    else
    {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить сделку"),
            JSON_UNESCAPED_UNICODE);
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("message" => "Невозможно найти сделку.",JSON_UNESCAPED_UNICODE));
}