<?php

use objects\Dish;

header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    include_once '../config/database.php';
    include_once '../objects/Dish.php';

    $database = new Database();
    $db = $database->getConnection();
    $dish = new Dish($db);
    $keywords = isset($_GET['s']) ? $_GET['s'] : "";
    $stmt = $dish->search($keywords);
    $num = $stmt->rowCount();
    if($num>0){
        $dish_arr = array();
        $dish_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $dish_item = array(
                "dishes_id" => $dishes_id,
                "dishes_name" => $dishes_name,
                "dish_type" => $dish_type,
                "output_weight" => $output_weight
            );
            array_push($dish_arr["records"], $dish_item);
        }
        http_response_code(200);
        echo json_encode($dish_arr);
    }
    else{
        http_response_code(404);
        echo json_encode(array("message" => "Блюдо не найдено."),JSON_UNESCAPED_UNICODE);
    }
