<?php
$host = "localhost";  // Database host (phpMyAdmin runs on localhost)
$username = "root";   // Default username for phpMyAdmin
$password = "";       // Default password is empty
$database = "support_system"; // Name of your database

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!";
}
?>
