<?php

use objects\Products;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../config/database.php';
include_once '../objects/Products.php';

$database = new Database();
$db = $database->getConnection();
$product = new \objects\Products($db);
$keywords = isset($_GET['s']) ? $_GET['s'] : "";
$stmt = $product->search($keywords);
$num = $stmt->rowCount();
if($num>0){
    $product_arr = array();
    $product_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $product_item = array(
            "id_product" => $id_product,
            "product_name" => $product_name,
            "calories" => $calories,
            "weight" => $weight,
            "price" => $price,
        );
        $product_arr["records"][] = $product_item;
    }
    http_response_code(200);
    echo json_encode($product_arr);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "Сделка не найдена."),JSON_UNESCAPED_UNICODE);
}
