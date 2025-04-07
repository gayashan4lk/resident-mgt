<?php
require_once 'Database.php';

class Resident {
    private $db;
    private $table = 'residents';
    
    // Properties
    public $id;
    public $full_name;
    public $dob;
    public $nic;
    public $gender;
    public $address;
    public $phone;
    public $email;
    public $occupation;
    public $registered_date;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all residents
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY full_name ASC";
        $result = $this->db->query($sql);
        return $result;
    }
    
    // Get single resident
    public function getSingle($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    // Create resident
    public function create() {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
                               (full_name, dob, nic, gender, address, phone, email, occupation) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param('ssssssss', 
            $this->full_name, 
            $this->dob, 
            $this->nic, 
            $this->gender, 
            $this->address, 
            $this->phone, 
            $this->email, 
            $this->occupation
        );
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Update resident
    public function update() {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
                               SET full_name = ?, dob = ?, nic = ?, gender = ?, 
                                   address = ?, phone = ?, email = ?, occupation = ? 
                               WHERE id = ?");
        
        $stmt->bind_param('ssssssssi', 
            $this->full_name, 
            $this->dob, 
            $this->nic, 
            $this->gender, 
            $this->address, 
            $this->phone, 
            $this->email, 
            $this->occupation,
            $this->id
        );
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete resident
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
