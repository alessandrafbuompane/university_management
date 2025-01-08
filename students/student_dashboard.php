<?php
session_start();
include '../config.php'; // Include the database connection

// Check if the user is authenticated and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php"); // Redirect to login if not logged in or not a student
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Your role: Student</p>

    <h3>Options</h3>
    <ul>
        <li><a href="my_courses.php">My Courses</a></li>
        <li><a href="grades.php">My Grades</a></li>
        <li><a href="enrolled_year.php">Enrolled Year</a></li>
        <li><a href="upcoming_events.php">Upcoming Events</a></li>
        <li><a href="select_academic_year.php">Select Academic Year</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</body>
</html>
