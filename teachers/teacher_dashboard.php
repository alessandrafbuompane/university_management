
<?php
session_start();
include '../config.php'; // Include the database connection

// Check if the user is authenticated and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php"); // Redirect to login if not logged in or not a teacher
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Your role: Teacher</p>

    <h3>Options</h3>
    <ul>
        <li><a href="view_courses.php">View Your Courses</a></li>
        <li><a href="view_students.php">View Students</a></li>
        <li><a href="grade_students.php">Grade Students</a></li>
        <li><a href="profile.php">View Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</body>
</html>
