<?php
// Start session for flash messages
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'resident_database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    if ($id === false) {
        $_SESSION['error_message'] = "Invalid resident ID";
        header("Location: index.php");
        exit();
    }
    
    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM residents WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Resident deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting resident: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error_message'] = "No resident ID provided";
}

// Redirect back to index
header("Location: index.php");
exit();
