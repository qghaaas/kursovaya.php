<?php

namespace objects;

use PDO;

class Dish
{
    private $conn;
    private $table_name = "dishes";
    public $dishes_id;
    public $dishes_name;
    public $dish_type;
    public $output_weight;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "insert into " . $this->table_name . " (dishes_id,dishes_name,dish_type,output_weight) 
        values (:dishes_id,:dishes_name,:dish_type,:output_weight)";
        $stmt = $this->conn->prepare($query);
        $this->dishes_id = htmlspecialchars(strip_tags($this->dishes_id));
        $this->dishes_name = htmlspecialchars(strip_tags($this->dishes_name));
        $this->dish_type = htmlspecialchars(strip_tags($this->dish_type));
        $this->output_weight = htmlspecialchars(strip_tags($this->output_weight));
        $stmt->bindParam(':dishes_id', $this->dishes_id);
        $stmt->bindParam(':dishes_name', $this->dishes_name);
        $stmt->bindParam(':dish_type', $this->dish_type);
        $stmt->bindParam(':output_weight', $this->output_weight);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "update " . $this->table_name . " set dishes_id=:dishes_id,
                                                 dishes_name=:dishes_name,
                                                 dish_type=:dish_type,
                                                 output_weight=:output_weight
                                                 where dishes_id=:dishes_id";
        $stmt = $this->conn->prepare($query);
        $this->dishes_id = htmlspecialchars(strip_tags($this->dishes_id));
        $this->dishes_name = htmlspecialchars(strip_tags($this->dishes_name));
        $this->dish_type = htmlspecialchars(strip_tags($this->dish_type));
        $this->output_weight = htmlspecialchars(strip_tags($this->output_weight));

        $stmt->bindParam(':dishes_id', $this->dishes_id);
        $stmt->bindParam(':dishes_name', $this->dishes_name);
        $stmt->bindParam(':dish_type', $this->dish_type);
        $stmt->bindParam(':output_weight', $this->output_weight);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete()
    {
        $query = "delete from " . $this->table_name . " where dishes_id=:dishes_id";
        $stmt = $this->conn->prepare($query);
        $this->dishes_id = htmlspecialchars(strip_tags($this->dishes_id));
        $stmt->bindParam(':dishes_id', $this->dishes_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function search($keyword)
    {
        $query = "select * from " . $this->table_name. " where dishes_id = ?";

        $stmt = $this->conn->prepare($query);
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "$keyword";
        $stmt->bindParam(1, $keyword);
//        $stmt->bindParam(2, $keyword);
//        $stmt->bindParam(3, $keyword);
        $stmt->execute();
        return $stmt;
    }

    public function readAll()
    {
        $query = "select * from ".$this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}