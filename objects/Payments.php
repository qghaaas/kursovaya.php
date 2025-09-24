<?php

namespace objects;

use PDO;

class Payments
{
    private $conn;
    private $table_name = 'payments';

    public $payment_id;
    public $account_id;
    public $service_id;
    public $consumed;       
    public $due_date;       
    public $paid_on_time;  
    public $payment_date;    

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO $this->table_name (account_id, service_id, consumed, due_date, paid_on_time, payment_date) VALUES (:account_id, :service_id, :consumed, :due_date, :paid_on_time, :payment_date) RETURNING payment_id";
        $stmt = $this->conn->prepare($query);
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $this->consumed = htmlspecialchars(strip_tags($this->consumed));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        $this->paid_on_time = filter_var($this->paid_on_time, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        $this->payment_date = $this->payment_date ? htmlspecialchars(strip_tags($this->payment_date)) : null;
        $stmt->bindParam(':account_id', $this->account_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':consumed', $this->consumed);
        $stmt->bindParam(':due_date', $this->due_date);
        $stmt->bindParam(':paid_on_time', $this->paid_on_time);
        $stmt->bindParam(':payment_date', $this->payment_date);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['payment_id'])) {
                $this->payment_id = $row['payment_id'];
            }
            return true;
        }
        return false;
    }

    public function update(){
        $query = "UPDATE $this->table_name SET account_id=:account_id, service_id=:service_id, consumed=:consumed, due_date=:due_date, paid_on_time=:paid_on_time, payment_date=:payment_date WHERE payment_id=:payment_id";
        $stmt = $this->conn->prepare($query);
        $this->payment_id = htmlspecialchars(strip_tags($this->payment_id));
        $this->account_id = htmlspecialchars(strip_tags($this->account_id));
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $this->consumed = htmlspecialchars(strip_tags($this->consumed));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        $this->paid_on_time = filter_var($this->paid_on_time, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        $this->payment_date = $this->payment_date ? htmlspecialchars(strip_tags($this->payment_date)) : null;
        $stmt->bindParam(':payment_id', $this->payment_id);
        $stmt->bindParam(':account_id', $this->account_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':consumed', $this->consumed);
        $stmt->bindParam(':due_date', $this->due_date);
        $stmt->bindParam(':paid_on_time', $this->paid_on_time);
        $stmt->bindParam(':payment_date', $this->payment_date);
        return $stmt->execute();
    }

    public function delete(){
        $query = "DELETE FROM $this->table_name WHERE payment_id=:payment_id";
        $stmt = $this->conn->prepare($query);
        $this->payment_id = htmlspecialchars(strip_tags($this->payment_id));
        $stmt->bindParam(':payment_id', $this->payment_id);
        return $stmt->execute();
    }

    public function search($keyword){
        $query = "SELECT * FROM $this->table_name WHERE CAST(payment_id AS TEXT) = :kw OR CAST(account_id AS TEXT) = :kw OR CAST(service_id AS TEXT) = :kw";
        $stmt = $this->conn->prepare($query);
        $kw = htmlspecialchars(strip_tags($keyword));
        $stmt->bindParam(':kw', $kw);
        $stmt->execute();
        return $stmt;
    }

    public function readAll(){
        $query = "SELECT * FROM $this->table_name ORDER BY payment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function calculateAmountAndPenalty($payment_id){
        $query = "SELECT p.payment_id, p.account_id, p.service_id, p.consumed, p.due_date, p.paid_on_time, p.payment_date, s.tariff
                  FROM payments p JOIN services s ON p.service_id = s.service_id
                  WHERE p.payment_id = :payment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            return null;
        }
    $amount = (float)$row['consumed'] * (float)$row['tariff'];

    
    $today = new \DateTime();
    $due = new \DateTime($row['due_date']);
    $pot = $row['paid_on_time'];
    $paidOnTime = ($pot === true) || ($pot === 1) || ($pot === '1') || ($pot === 't') || ($pot === 'true');
        $paymentDate = $row['payment_date'] ? new \DateTime($row['payment_date']) : ($paidOnTime ? $due : null);

        $penalty = 0.0;
       
        if ($paymentDate === null || $paymentDate > $due) {
            $endDate = $paymentDate ?: $today;
            if ($endDate > $due) {
                $diffDays = (int)$due->diff($endDate)->format('%a');
                $penalty = $amount * 0.001 * $diffDays; 
            }
        }

        return [
            'payment_id' => (int)$row['payment_id'],
            'account_id' => (int)$row['account_id'],
            'service_id' => (int)$row['service_id'],
            'consumed' => (float)$row['consumed'],
            'tariff' => (float)$row['tariff'],
            'amount' => round($amount, 2),
            'due_date' => $row['due_date'],
            'paid_on_time' => (bool)$paidOnTime,
            'payment_date' => $row['payment_date'],
            'penalty' => round($penalty, 2),
            'total_due' => round($amount + $penalty, 2),
        ];
    }
}
