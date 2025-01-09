<?php
$servername = "localhost";
$username = "root"; // My database username
$password = "";     // My database password
$dbname = "university_management"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
