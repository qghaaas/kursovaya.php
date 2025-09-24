<?php

namespace objects;

use PDO;

class Apartments
{
    private $conn;
    private $table_name = 'apartments';

    public $apartment_id;
    public $address;
    public $residents;
    public $area;
    public $account_id; // FK -> tenants.account_id (unique 1:1)

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO $this->table_name (address, residents, area, account_id) VALUES (:address, :residents, :area, :account_id) RETURNING apartment_id";
        $stmt = $this->conn->prepare($query);
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->residents = htmlspecialchars(strip_tags($this->residents));
        $this->area = htmlspecialchars(strip_tags($this->area));
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':residents', $this->residents);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':account_id', $this->account_id);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['apartment_id'])) {
                $this->apartment_id = $row['apartment_id'];
            }
            return true;
        }
        return false;
    }

    public function update(){
        $query = "UPDATE $this->table_name SET address=:address, residents=:residents, area=:area, account_id=:account_id WHERE apartment_id=:apartment_id";
        $stmt = $this->conn->prepare($query);
        $this->apartment_id = htmlspecialchars(strip_tags($this->apartment_id));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->residents = htmlspecialchars(strip_tags($this->residents));
        $this->area = htmlspecialchars(strip_tags($this->area));
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $stmt->bindParam(':apartment_id', $this->apartment_id);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':residents', $this->residents);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':account_id', $this->account_id);
        return $stmt->execute();
    }

    public function delete(){
        $query = "DELETE FROM $this->table_name WHERE apartment_id=:apartment_id";
        $stmt = $this->conn->prepare($query);
        $this->apartment_id = htmlspecialchars(strip_tags($this->apartment_id));
        $stmt->bindParam(':apartment_id', $this->apartment_id);
        return $stmt->execute();
    }

    public function search($keyword){
        $query = "SELECT * FROM $this->table_name WHERE CAST(apartment_id AS TEXT) = :kw OR address ILIKE :kwlike";
        $stmt = $this->conn->prepare($query);
        $kw = htmlspecialchars(strip_tags($keyword));
        $kwlike = '%' . $kw . '%';
        $stmt->bindParam(':kw', $kw);
        $stmt->bindParam(':kwlike', $kwlike);
        $stmt->execute();
        return $stmt;
    }

    public function readAll(){
        $query = "SELECT * FROM $this->table_name ORDER BY apartment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
