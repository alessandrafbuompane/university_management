<?php
session_start();
include '../config.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $course_credits = $_POST['course_credits'];

    // Insert new course into the database
    $sql = "INSERT INTO courses (name, description, credits) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $course_name, $course_description, $course_credits);

    if ($stmt->execute()) {
        echo "Course added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
</head>
<body>

    <h2>Add New Course</h2>
    <form action="add_course.php" method="POST">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" id="course_name" required><br><br>

        <label for="course_description">Course Description:</label>
        <textarea name="course_description" id="course_description" required></textarea><br><br>

        <label for="course_credits">Course Credits:</label>
        <input type="number" name="course_credits" id="course_credits" required><br><br>

        <input type="submit" value="Add Course">
    </form>

    <a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
