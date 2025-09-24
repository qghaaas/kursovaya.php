<?php

namespace objects;

use PDO;

class Recipes
{
    private $conn;
    private $table_name = 'recipes';
    public $recipes_id;
    public $dishes_id;
    public $cooking_time;
    public $technology;
    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query="insert into ".$this->table_name." (recipes_id,dishes_id,cooking_time,technology) 
        values (:recipes_id,:dishes_id,:cooking_time,:technology)";
        $stmt = $this->conn->prepare($query);
        $this->recipes_id=htmlspecialchars(strip_tags($this->recipes_id));
        $this->dishes_id=htmlspecialchars(strip_tags($this->dishes_id));
        $this->cooking_time=htmlspecialchars(strip_tags($this->cooking_time));
        $this->technology=htmlspecialchars(strip_tags($this->technology));
        $stmt->bindParam(':recipes_id',$this->recipes_id);
        $stmt->bindParam(':dishes_id',$this->dishes_id);
        $stmt->bindParam(':cooking_time',$this->cooking_time);
        $stmt->bindParam(':technology',$this->technology);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "update " . $this->table_name . " set recipes_id=:recipes_id,
                                                 dishes_id=:dishes_id,
                                                 cooking_time=:cooking_time,
                                                 technology=:technology,
                                                 where recipes_id=:recipes_id";
        $stmt = $this->conn->prepare($query);
        $this->recipes_id=htmlspecialchars(strip_tags($this->recipes_id));
        $this->dishes_id=htmlspecialchars(strip_tags($this->dishes_id));
        $this->cooking_time=htmlspecialchars(strip_tags($this->cooking_time));
        $this->technology=htmlspecialchars(strip_tags($this->technology));

        $stmt->bindParam(':recipes_id',$this->recipes_id);
        $stmt->bindParam(':dishes_id',$this->dishes_id);
        $stmt->bindParam(':cooking_time',$this->cooking_time);
        $stmt->bindParam(':technology',$this->technology);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function delete()
    {
        $query = "delete from ".$this->table_name." where recipes_id=:recipes_id";
        $stmt = $this->conn->prepare($query);
        $this->recipes_id = htmlspecialchars(strip_tags($this->recipes_id));
        $stmt->bindParam(':id_currency_sold',$this->recipes_id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function  search($keyword)
    {
        $query="select * from ".$this->table_name." where recipes_id = ?";

        $stmt = $this->conn->prepare($query);
        $keyword=htmlspecialchars(strip_tags($keyword));
        $keyword="$keyword";
        $stmt->bindParam(1,$keyword);
        $stmt->execute();
        return $stmt;
    }
    public function readAll()
    {
        $query = "SELECT * FROM $this->table_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }}
