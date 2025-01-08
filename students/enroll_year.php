<?php
session_start();
include '../config.php';  // Include the database connection file

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Check if a year_id is provided for enrollment
if (isset($_POST['year_id']) && !empty($_POST['year_id'])) {
    $year_id = $_POST['year_id'];  // Get the academic year ID

    // Insert the student into the student_academic_years table
    $sql = "INSERT INTO student_academic_years (student_id, year_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $year_id);

    if ($stmt->execute()) {
        echo "You have successfully enrolled in the academic year!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No academic year selected!";
}
?>

<a href="dashboard.php">Back to Dashboard</a>
