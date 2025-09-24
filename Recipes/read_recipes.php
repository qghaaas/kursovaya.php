<?php

use objects\Recipes;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Recipes.php";

$database = new Database();
$db = $database->getConnection();

$recipes= new Recipes($db);

$stmt = $recipes->readAll();
$num = $stmt->rowCount();

if ($num > 0) {

    $recipes_arr = array();
    $recipes_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        $recipes_item = array(
            "recipes_id" => $recipes_id,
            "dishes_id" => $dishes_id,
            "cooking_time" => $cooking_time,
            "technology" => $technology,
        );
        $recipes_arr["records"][] = $recipes_item;
    }
    http_response_code(200);

    echo json_encode($recipes_arr);
} else {

    http_response_code(404);

    echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);}
