<?php

namespace objects;

use PDO;

class Dishproducts
{
    private $conn;
    private $table_name = 'dishproducts';
    public $dishes_id;
    public $id_product;
    public $quantity;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "insert into " . $this->table_name . " (dishes_id,id_product,quantity) 
        values (:dishes_id,:id_product,:quantity)";
        $stmt = $this->conn->prepare($query);
        $this->dishes_id = htmlspecialchars(strip_tags($this->dishes_id));
        $this->id_product = htmlspecialchars(strip_tags($this->id_product));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));

        $stmt->bindParam(':dishes_id', $this->dishes_id);
        $stmt->bindParam(':id_product', $this->id_product);
        $stmt->bindParam(':quantity', $this->quantity);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "update " . $this->table_name . " set dishes_id=:dishes_id,
                                                 id_product=:id_product,
                                                 quantity=:quantity,
                                                 where dishes_id=:dishes_id";
        $stmt = $this->conn->prepare($query);
        $this->dishes_id = htmlspecialchars(strip_tags($this->dishes_id));
        $this->id_product = htmlspecialchars(strip_tags($this->id_product));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));

        $stmt->bindParam(':dishes_id', $this->dishes_id);
        $stmt->bindParam(':id_product', $this->id_product);
        $stmt->bindParam(':quantity', $this->quantity);

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