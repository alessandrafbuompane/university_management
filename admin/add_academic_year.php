<?php
session_start();
include '../config.php';  // Adjust the path if needed

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is authenticated and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $year = $_POST['year'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // SQL query to insert the new academic year into the database
    $sql = "INSERT INTO academic_years (year, start_date, end_date, status) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $year, $start_date, $end_date, $status);

    if ($stmt->execute()) {
        echo "New academic year has been added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- HTML form for adding a new academic year -->
<form method="POST" action="">
    <label for="year">Academic Year:</label><br>
    <input type="text" name="year" required><br><br>

    <label for="start_date">Start Date:</label><br>
    <input type="date" name="start_date" required><br><br>

    <label for="end_date">End Date:</label><br>
    <input type="date" name="end_date" required><br><br>

    <label for="status">Status:</label><br>
    <select name="status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select><br><br>

    <input type="submit" value="Add Academic Year">
</form>
