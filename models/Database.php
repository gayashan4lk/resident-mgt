<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // Make sure we're including the config file with the correct path
        require_once dirname(__DIR__) . '/config/config.php';
        
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    // Get singleton instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Get database connection
    public function getConnection() {
        return $this->conn;
    }
    
    // Prepare statement with query
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    // Execute simple query
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    // Get resulted rows
    public function affected_rows() {
        return $this->conn->affected_rows;
    }
    
    // Get last inserted id
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
    
    // Get error
    public function error() {
        return $this->conn->error;
    }
    
    // Close connection
    public function close() {
        $this->conn->close();
    }
}
