<?php
session_start();
include '../config.php'; // Include the database connection

// Check if the user is authenticated and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Your role: Admin</p>

    <h3>Options</h3>
    <ul>
        <li><a href="add_academic_year.php">Add Academic Year</a></li>
        <li><a href="add_event.php">Add Event</a></li>
        <li><a href="add_course.php">Add Course</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</body>
</html>