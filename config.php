<?php
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if there's no password for root
$dbname = "university_management"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully!";
?>

