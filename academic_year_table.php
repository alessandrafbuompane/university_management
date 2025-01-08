<?php
// Start the session to access session variables
session_start();

// Include the database connection (adjust the path as needed)
include 'config.php';

// Check if the user is authenticated by checking the session variable
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if not authenticated
    exit();
}

// Example data to insert into the table
$year = '2025/2026';  // Academic year
$start_date = '2025-09-01';  // Start date of the academic year
$end_date = '2026-06-30';  // End date of the academic year
$status = 'active';  // Status of the academic year

// SQL query to insert data into the academic_years table
$sql = "INSERT INTO academic_years (year, start_date, end_date, status) 
        VALUES (?, ?, ?, ?)";

// Prepare the query to prevent SQL injection
$stmt = $conn->prepare($sql);

// Bind the variables to the prepared statement
$stmt->bind_param("ssss", $year, $start_date, $end_date, $status);

// Execute the query and check the result
if ($stmt->execute()) {
    echo "New academic year has been added successfully!";  // Success message
} else {
    echo "Error: " . $stmt->error;  // Display error if the query fails
}

// Close the prepared statement and the connection
$stmt->close();
$conn->close();
?>
