<?php
class Database
{
    private $host = "localhost";
    private $db_name = "postgres";
    private $username = "postgres";
    private $password = "1234";
    private $schema = "jec"; 
    public $conn;
    
    public function getConnection()
    {
        $this->conn = null;
        try
        {
            $dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            $this->conn->exec("SET search_path TO $this->schema");
        }
        catch (PDOException $e) {
            echo "Ошибка подключения: ".$e->getMessage();
        }
        return $this->conn;
    }
}