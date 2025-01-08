<?php
session_start();
include '../config.php';  // Adjust the path if needed

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Query to get the courses the user is already enrolled in
$sql_enrolled = "
    SELECT courses.id, courses.name, courses.description, courses.credits, courses.teacher_name 
    FROM enrollments 
    INNER JOIN courses ON enrollments.course_id = courses.id 
    WHERE enrollments.student_id = ?";
$stmt_enrolled = $conn->prepare($sql_enrolled);
$stmt_enrolled->bind_param("i", $user_id);
$stmt_enrolled->execute();
$result_enrolled = $stmt_enrolled->get_result();

// Query to get all available courses
$sql_courses = "SELECT * FROM courses";
$result_courses = $conn->query($sql_courses);

// Check if the query is successful
if (!$result_courses) {
    die("Query failed: " . $conn->error);
}

if (!$result_enrolled) {
    die("Query failed: " . $conn->error);
}

// Store the course IDs the user is enrolled in
$enrolled_courses = [];
if ($result_enrolled && $result_enrolled->num_rows > 0) {
    while ($course = $result_enrolled->fetch_assoc()) {
        $enrolled_courses[] = $course['id'];  // Store the IDs of the courses the user is enrolled in
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
</head>
<body>
    <h1>Available Courses</h1>
    
    <!-- Show the courses the user is enrolled in -->
    <h2>My Courses</h2>
    <?php
    if ($result_enrolled && $result_enrolled->num_rows > 0) {
        while ($course = $result_enrolled->fetch_assoc()) {
            echo "<div>";
            echo "<strong>" . htmlspecialchars($course['name']) . "</strong><br>";
            echo "Description: " . htmlspecialchars($course['description']) . "<br>";
            echo "Credits: " . $course['credits'] . "<br>";
            echo "Teacher: " . htmlspecialchars($course['teacher_name']) . "<br>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>You are not enrolled in any courses.</p>";  // Message if no courses are enrolled
    }
    ?>

    <hr>

    <!-- Show the available courses to enroll in -->
    <h2>Available Courses to Enroll</h2>
    <?php
    if ($result_courses && $result_courses->num_rows > 0) {
        while ($course = $result_courses->fetch_assoc()) {
            echo "<div>";
            echo "<strong>" . htmlspecialchars($course['name']) . "</strong><br>";
            echo "Description: " . htmlspecialchars($course['description']) . "<br>";
            echo "Credits: " . $course['credits'] . "<br>";
            echo "Teacher: " . htmlspecialchars($course['teacher_name']) . "<br>";

            // Check if the user is already enrolled in this course
            if (in_array($course['id'], $enrolled_courses)) {
                echo "<p>You are already enrolled in this course.</p>";
            } else {
                echo "<form action='enroll.php' method='POST'>";
                echo "<input type='hidden' name='course_id' value='" . $course['id'] . "'>";
                echo "<input type='submit' value='Enroll'>";
                echo "</form>";
            }
            echo "</div><hr>";
        }
    } else {
        echo "<p>No courses available to enroll in.</p>";
    }
    ?>
    
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

