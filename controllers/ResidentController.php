<?php
require_once 'models/Resident.php';

class ResidentController {
    private $residentModel;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->residentModel = new Resident();
    }
    
    // Display all residents
    public function index() {
        // Get all residents
        $result = $this->residentModel->getAll();
        
        // Initialize flash messages
        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
        
        // Clear flash messages
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);
        
        // Form token for CSRF protection
        if (!isset($_SESSION['form_token'])) {
            $_SESSION['form_token'] = md5(uniqid(mt_rand(), true));
        }
        
        // Load view
        require_once 'views/residents/index.php';
    }
    
    // Handle create/update form submissions
    public function save() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: index.php");
            exit();
        }
        
        // Check if updating existing resident
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->update();
        } else {
            // Verify form token for new residents
            if (!isset($_POST['form_token']) || $_POST['form_token'] !== $_SESSION['form_token']) {
                $_SESSION['error_message'] = "Invalid form submission";
                header("Location: index.php");
                exit();
            }
            
            $this->create();
        }
        
        // Redirect to prevent form resubmission
        header("Location: index.php");
        exit();
    }
    
    // Create new resident
    private function create() {
        // Sanitize and set data
        $this->residentModel->full_name = $this->sanitizeInput($_POST['full_name']);
        $this->residentModel->dob = $this->sanitizeInput($_POST['dob']);
        $this->residentModel->nic = $this->sanitizeInput($_POST['nic']);
        $this->residentModel->gender = $this->sanitizeInput($_POST['gender']);
        $this->residentModel->address = $this->sanitizeInput($_POST['address']);
        $this->residentModel->phone = $this->sanitizeInput($_POST['phone']);
        $this->residentModel->email = $this->sanitizeInput($_POST['email']);
        $this->residentModel->occupation = $this->sanitizeInput($_POST['occupation'] ?? '');
        
        // Create resident
        if ($this->residentModel->create()) {
            $_SESSION['success_message'] = "Resident added successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to add resident";
        }
        
        // Generate new token after submission
        $_SESSION['form_token'] = md5(uniqid(mt_rand(), true));
    }
    
    // Update existing resident
    private function update() {
        // Validate ID
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        
        if ($id === false) {
            $_SESSION['error_message'] = "Invalid resident ID";
            return;
        }
        
        // Sanitize and set data
        $this->residentModel->id = $id;
        $this->residentModel->full_name = $this->sanitizeInput($_POST['full_name']);
        $this->residentModel->dob = $this->sanitizeInput($_POST['dob']);
        $this->residentModel->nic = $this->sanitizeInput($_POST['nic']);
        $this->residentModel->gender = $this->sanitizeInput($_POST['gender']);
        $this->residentModel->address = $this->sanitizeInput($_POST['address']);
        $this->residentModel->phone = $this->sanitizeInput($_POST['phone']);
        $this->residentModel->email = $this->sanitizeInput($_POST['email']);
        $this->residentModel->occupation = $this->sanitizeInput($_POST['occupation'] ?? '');
        
        // Update resident
        if ($this->residentModel->update()) {
            $_SESSION['success_message'] = "Resident updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update resident";
        }
    }
    
    // Delete resident
    public function delete() {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "No resident ID provided";
            header("Location: index.php");
            exit();
        }
        
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        
        if ($id === false) {
            $_SESSION['error_message'] = "Invalid resident ID";
            header("Location: index.php");
            exit();
        }
        
        if ($this->residentModel->delete($id)) {
            $_SESSION['success_message'] = "Resident deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete resident";
        }
        
        header("Location: index.php");
        exit();
    }
    
    // Sanitize inputs
    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}
