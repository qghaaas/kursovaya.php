<?php

namespace objects;

use PDO;

class Dailypreparations
{
    private $conn;
    private $table_name = 'dailypreparations';
    public $daily_id;
    public $dishes_id;
    public $portions;
    public $preparation_date;
    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query="insert into ".$this->table_name." (daily_id,dishes_id,portions,preparation_date) values (:daily_id,:dishes_id,:portions,:preparation_date)";
        $stmt = $this->conn->prepare($query);
        $this->daily_id=htmlspecialchars(strip_tags($this->daily_id));
        $this->dishes_id=htmlspecialchars(strip_tags($this->dishes_id));
        $this->portions=htmlspecialchars(strip_tags($this->portions));
        $this->preparation_date=htmlspecialchars(strip_tags($this->preparation_date));
        $stmt->bindParam(':daily_id',$this->daily_id);
        $stmt->bindParam(':dishes_id',$this->dishes_id);
        $stmt->bindParam(':portions',$this->portions);
        $stmt->bindParam(':preparation_date',$this->preparation_date);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "update " . $this->table_name . " set daily_id=:daily_id,
                                                 dishes_id=:dishes_id,
                                                 portions=:portions,
                                                 preparation_date=:preparation_date 
                                                 where daily_id=:daily_id";

        $stmt = $this->conn->prepare($query);
        $this->daily_id=htmlspecialchars(strip_tags($this->daily_id));
        $this->dishes_id=htmlspecialchars(strip_tags($this->dishes_id));
        $this->portions=htmlspecialchars(strip_tags($this->portions));
        $this->preparation_date=htmlspecialchars(strip_tags($this->preparation_date));

        $stmt->bindParam(':daily_id',$this->daily_id);
        $stmt->bindParam(':dishes_id',$this->dishes_id);
        $stmt->bindParam(':portions',$this->portions);
        $stmt->bindParam(':preparation_date',$this->preparation_date);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function delete()
    {
        $query = "delete from ".$this->table_name." where daily_id=:daily_id";
        $stmt = $this->conn->prepare($query);
        $this->daily_id = htmlspecialchars(strip_tags($this->daily_id));
        $stmt->bindParam(':daily_id',$this->daily_id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function  search($keyword)
    {
        $query="select * from ".$this->table_name." where daily_id = ?";

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
    }
}
