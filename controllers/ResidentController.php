<?php
require_once 'models/Resident.php';

class ResidentController {
    private $residentModel;
    private $errors = [];
    
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
        
        // Set the content to render within the layout
        $content = 'views/residents/index.php';
        
        // Load the main layout, which will include the content
        require_once 'views/layouts/main.php';
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
        // Sanitize inputs first
        $sanitizedData = $this->sanitizeFormData($_POST);
        
        // Validate the data
        if (!$this->validateResidentData($sanitizedData)) {
            // Set error message with all validation errors as a list
            $_SESSION['error_message'] = "<ul class='mb-0 ps-3 text-start'>" . 
                implode('', array_map(function($error) { 
                    return "<li>$error</li>";
                }, $this->errors)) . 
                "</ul>";
            $_SESSION['form_data'] = $_POST; // Preserve form data for refilling
            header("Location: index.php");
            exit();
        }
        
        // Set data
        $this->residentModel->full_name = $sanitizedData['full_name'];
        $this->residentModel->dob = $sanitizedData['dob'];
        $this->residentModel->nic = $sanitizedData['nic'];
        $this->residentModel->gender = $sanitizedData['gender'];
        $this->residentModel->address = $sanitizedData['address'];
        $this->residentModel->phone = $sanitizedData['phone'];
        $this->residentModel->email = $sanitizedData['email'];
        $this->residentModel->occupation = $sanitizedData['occupation'] ?? '';
        
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
            header("Location: index.php");
            exit();
        }
        
        // Sanitize inputs first
        $sanitizedData = $this->sanitizeFormData($_POST);
        
        // Validate the data
        if (!$this->validateResidentData($sanitizedData)) {
            // Set error message with all validation errors as a list
            $_SESSION['error_message'] = "<ul class='mb-0 ps-3 text-start'>" . 
                implode('', array_map(function($error) { 
                    return "<li>$error</li>";
                }, $this->errors)) . 
                "</ul>";
            $_SESSION['form_data'] = $_POST; // Preserve form data for refilling
            header("Location: index.php");
            exit();
        }
        
        // Set data
        $this->residentModel->id = $id;
        $this->residentModel->full_name = $sanitizedData['full_name'];
        $this->residentModel->dob = $sanitizedData['dob'];
        $this->residentModel->nic = $sanitizedData['nic'];
        $this->residentModel->gender = $sanitizedData['gender'];
        $this->residentModel->address = $sanitizedData['address'];
        $this->residentModel->phone = $sanitizedData['phone'];
        $this->residentModel->email = $sanitizedData['email'];
        $this->residentModel->occupation = $sanitizedData['occupation'] ?? '';
        
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
    
    // Sanitize all form data at once
    private function sanitizeFormData($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = $this->sanitizeInput($value);
        }
        return $sanitized;
    }
    
    // Validate resident data
    private function validateResidentData($data) {
        $this->errors = []; // Reset errors array
        
        // Full Name validation - at least 3 chars, only letters, spaces, and some special chars
        if (empty($data['full_name']) || strlen($data['full_name']) < 3) {
            $this->errors[] = 'Full name must be at least 3 characters long';
        } elseif (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ .\'\-]+$/', $data['full_name'])) {
            $this->errors[] = 'Full name contains invalid characters (only letters, spaces, dots, apostrophes and hyphens allowed)';
        }
        
        // Date of Birth validation
        if (empty($data['dob'])) {
            $this->errors[] = 'Date of birth is required';
        } else {
            $dob = date_create($data['dob']);
            $now = date_create('now');
            if (!$dob) {
                $this->errors[] = 'Invalid date of birth format (use YYYY-MM-DD)';
            } elseif ($dob > $now) {
                $this->errors[] = 'Date of birth cannot be in the future';
            } elseif (date_diff($dob, $now)->y > 120) {
                $this->errors[] = 'Date of birth seems unrealistic (age > 120 years)';
            }
        }
        
        // NIC validation (assuming a specific format - adjust as needed)
        if (empty($data['nic'])) {
            $this->errors[] = 'NIC is required';
        } elseif (!preg_match('/^[0-9]{9}[vVxX]$|^[0-9]{12}$/', $data['nic'])) {
            $this->errors[] = 'NIC format is invalid (must be 9 digits followed by v/V/x/X OR 12 digits)';
        }
        
        // Gender validation
        if (empty($data['gender'])) {
            $this->errors[] = 'Gender is required';
        } elseif (!in_array($data['gender'], ['Male', 'Female', 'Other'])) {
            $this->errors[] = 'Invalid gender selection (must be Male, Female, or Other)';
        }
        
        // Address validation
        if (empty($data['address']) || strlen($data['address']) < 5) {
            $this->errors[] = 'Address is required and must be at least 5 characters long';
        }
        
        // Phone validation
        if (empty($data['phone'])) {
            $this->errors[] = 'Phone number is required';
        } elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $data['phone'])) {
            $this->errors[] = 'Phone number format is invalid (must be 7-15 characters with only numbers, +, -, and spaces)';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $this->errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email format is invalid (example: name@example.com)';
        }
        
        // Return true if no errors
        return empty($this->errors);
    }
    
    // Sanitize inputs
    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}
