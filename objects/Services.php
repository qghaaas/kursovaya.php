<?php

namespace objects;

use PDO;

class Services
{
    private $conn;
    private $table_name = 'services';

    public $service_id;
    public $service_type;
    public $unit;
    public $tariff;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO $this->table_name (service_type, unit, tariff) VALUES (:service_type, :unit, :tariff) RETURNING service_id";
        $stmt = $this->conn->prepare($query);
        $this->service_type = htmlspecialchars(strip_tags($this->service_type));
        $this->unit = htmlspecialchars(strip_tags($this->unit));
        $this->tariff = htmlspecialchars(strip_tags($this->tariff));
        $stmt->bindParam(':service_type', $this->service_type);
        $stmt->bindParam(':unit', $this->unit);
        $stmt->bindParam(':tariff', $this->tariff);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['service_id'])) {
                $this->service_id = $row['service_id'];
            }
            return true;
        }
        return false;
    }

    public function update(){
        $query = "UPDATE $this->table_name SET service_type=:service_type, unit=:unit, tariff=:tariff WHERE service_id=:service_id";
        $stmt = $this->conn->prepare($query);
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $this->service_type = htmlspecialchars(strip_tags($this->service_type));
        $this->unit = htmlspecialchars(strip_tags($this->unit));
        $this->tariff = htmlspecialchars(strip_tags($this->tariff));
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':service_type', $this->service_type);
        $stmt->bindParam(':unit', $this->unit);
        $stmt->bindParam(':tariff', $this->tariff);
        return $stmt->execute();
    }

    public function delete(){
        $query = "DELETE FROM $this->table_name WHERE service_id=:service_id";
        $stmt = $this->conn->prepare($query);
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $stmt->bindParam(':service_id', $this->service_id);
        return $stmt->execute();
    }

    public function search($keyword){
        $query = "SELECT * FROM $this->table_name WHERE CAST(service_id AS TEXT) = :kw OR service_type ILIKE :kwlike";
        $stmt = $this->conn->prepare($query);
        $kw = htmlspecialchars(strip_tags($keyword));
        $kwlike = '%' . $kw . '%';
        $stmt->bindParam(':kw', $kw);
        $stmt->bindParam(':kwlike', $kwlike);
        $stmt->execute();
        return $stmt;
    }

    public function readAll(){
        $query = "SELECT * FROM $this->table_name ORDER BY service_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
