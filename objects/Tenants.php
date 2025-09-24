<?php

namespace objects;

use PDO;

class Tenants
{
    private $conn;
    private $table_name = 'tenants';

    public $account_id;
    public $full_name;
    public $phone;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO $this->table_name (full_name, phone) VALUES (:full_name, :phone) RETURNING account_id";
        $stmt = $this->conn->prepare($query);
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':phone', $this->phone);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['account_id'])) {
                $this->account_id = $row['account_id'];
            }
            return true;
        }
        return false;
    }

    public function update(){
        $query = "UPDATE $this->table_name SET full_name=:full_name, phone=:phone WHERE account_id=:account_id";
        $stmt = $this->conn->prepare($query);
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $stmt->bindParam(':account_id', $this->account_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':phone', $this->phone);
        return $stmt->execute();
    }

    public function delete(){
        $query = "DELETE FROM $this->table_name WHERE account_id=:account_id";
        $stmt = $this->conn->prepare($query);
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $stmt->bindParam(':account_id', $this->account_id);
        return $stmt->execute();
    }

    public function search($keyword){
      
        $query = "SELECT * FROM $this->table_name WHERE CAST(account_id AS TEXT) = :kw OR full_name ILIKE :kwlike";
        $stmt = $this->conn->prepare($query);
        $kw = htmlspecialchars(strip_tags($keyword));
        $kwlike = '%' . $kw . '%';
        $stmt->bindParam(':kw', $kw);
        $stmt->bindParam(':kwlike', $kwlike);
        $stmt->execute();
        return $stmt;
    }

    public function readAll(){
        $query = "SELECT * FROM $this->table_name ORDER BY account_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
