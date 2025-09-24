<?php

namespace objects;

use PDO;

class Products
{
    private $conn;
    private $table_name = 'products';
    public $id_product;
    public $product_name;
    public $calories;
    public $weight;
    public $price;
//    public $time_deal;
//    public $amount_currency_sold;
//    public $amount_purchased_currency;
//    public $id_currency_sold;
    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query="insert into ".$this->table_name." (id_product,product_name,calories,weight,price) 
        values (:id_product,:product_name,:calories,:weight,:price)";
        $stmt = $this->conn->prepare($query);
        $this->id_product=htmlspecialchars(strip_tags($this->id_product));
        $this->product_name=htmlspecialchars(strip_tags($this->product_name));
        $this->calories=htmlspecialchars(strip_tags($this->calories));
        $this->weight=htmlspecialchars(strip_tags($this->weight));
        $this->price=htmlspecialchars(strip_tags($this->price));

        $stmt->bindParam(':id_product',$this->id_product);
        $stmt->bindParam(':product_name',$this->product_name);
        $stmt->bindParam(':calories',$this->calories);
        $stmt->bindParam(':weight',$this->weight);
        $stmt->bindParam(':price',$this->price);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "update " . $this->table_name . " set id_product=:id_product,
                                                 product_name=:product_name,
                                                 calories=:calories,
                                                 weight=:weight,
                                                 price=:price,
                                                 where id_product=:id_product";
        $stmt = $this->conn->prepare($query);
        $this->id_product=htmlspecialchars(strip_tags($this->id_product));
        $this->product_name=htmlspecialchars(strip_tags($this->product_name));
        $this->calories=htmlspecialchars(strip_tags($this->calories));
        $this->weight=htmlspecialchars(strip_tags($this->weight));
        $this->price=htmlspecialchars(strip_tags($this->price));

        $stmt->bindParam(':id_product',$this->id_product);
        $stmt->bindParam(':product_name',$this->product_name);
        $stmt->bindParam(':calories',$this->calories);
        $stmt->bindParam(':weight',$this->weight);
        $stmt->bindParam(':price',$this->price);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function delete()
    {
        $query = "delete from ".$this->table_name." where id_product=:id_product";
        $stmt = $this->conn->prepare($query);
        $this->id_product = htmlspecialchars(strip_tags($this->id_product));
        $stmt->bindParam(':id_product',$this->id_product);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    function  search($keyword)
    {
        $query="select * from ".$this->table_name." where id_product = ?";

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
