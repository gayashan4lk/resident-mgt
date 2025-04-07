<?php
require_once 'models/Database.php';

class Resident {
    // Database props
    private $db;
    private $table = 'residents';
    
    // Resident props
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
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all residents
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY full_name ASC";
        return $this->db->query($query);
    }
    
    // Search residents by multiple fields
    public function search($searchTerm) {
        // Sanitize the search term
        $searchTerm = '%' . $searchTerm . '%';
        
        $query = "SELECT * FROM {$this->table} 
                 WHERE full_name LIKE ? 
                 OR nic LIKE ? 
                 OR phone LIKE ? 
                 OR email LIKE ? 
                 OR address LIKE ? 
                 ORDER BY full_name ASC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssss', 
            $searchTerm, 
            $searchTerm, 
            $searchTerm, 
            $searchTerm, 
            $searchTerm
        );
        
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get single resident
    public function getById($id) {
        $id = (int) $id; // Sanitize ID
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Create resident
    public function create() {
        $query = "INSERT INTO {$this->table} (full_name, dob, nic, gender, address, phone, email, occupation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
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
        
        return $stmt->execute();
    }
    
    // Update resident
    public function update() {
        $query = "UPDATE {$this->table} 
                SET full_name = ?, dob = ?, nic = ?, gender = ?, 
                    address = ?, phone = ?, email = ?, occupation = ? 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
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
        
        return $stmt->execute();
    }
    
    // Delete resident
    public function delete($id) {
        $id = (int) $id; // Sanitize ID
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        
        return $stmt->execute();
    }
}
