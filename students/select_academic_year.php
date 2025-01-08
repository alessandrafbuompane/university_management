<?php
session_start();
include '../config.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Retrieve all available academic years
$sql = "SELECT * FROM academic_years";
$result = $conn->query($sql);

echo "<h1>Select Academic Year</h1>";

if ($result->num_rows > 0) {
    echo '<form action="enroll_year.php" method="POST">';
    echo '<label for="year_id">Select Academic Year:</label>';
    echo '<select name="year_id" id="year_id">';
    
    // Loop through available years and display them in the dropdown
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['year_name']) . "</option>";
    }
    
    echo '</select>';
    echo '<input type="submit" value="Enroll">';
    echo '</form>';
} else {
    echo "<p>No academic years available for enrollment.</p>";
}

echo "<a href='dashboard.php'>Back to Dashboard</a>";
?>
