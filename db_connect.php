<?php
// Database connection file

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'resident_database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
